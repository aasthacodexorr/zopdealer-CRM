<?php

namespace Tenant\app\Controllers;

use App\Libraries\CloudStorage\CloudStorageFactory;
use App\Repositories\ClientRepository;
use Carbon\Carbon;
use CodeIgniter\Controller;
use Tenant\app\Libraries\VinHistory\CarfaxV3;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\InventoryRepository;

set_time_limit(0);

/**
 * CarHistoryQueue This method client iterates through clients to record the car history from CarFax.
 */
class CarHistoryQueue extends Controller
{
    /**
     * carHistoryAPIInstance
     *
     * @var CarfaxV3
     */
    private $carHistoryAPIInstance;

    /**
     * repository 
     *
     * @var mixed
     */
    private $repository;

    private $client_repository;

    public function __construct()
    {
        $this->carHistoryAPIInstance = new CarfaxV3();
        $this->repository = new InventoryRepository();
        $this->client_repository = new ClientRepository();
    }

    public function process()
    {

        $clients = $this->client_repository->scheduleQueueBasedOnColumn('car_history_schedule');
        $db = \Config\Database::connect();

        $clients = $clients['data_list'];

        foreach ($clients as $client) {
            try {
                $db->setDatabase($client['subdomain'] . '_' . $client['client_hash']);

                $vehicleHistoryAPIConfig = (new ConfigurationRepository())->findConfigurationColumnsByKey('carfax_vehicle_history_api', 'value');
                $hide_vin_configuration_array = (new ConfigurationRepository())->findConfigurationColumnsByKeyAsArray('carfax_report_hide_vin', ['value']);

                $hide_vin = "false";
                if (!empty($hide_vin_configuration_array['value'])) {
                    $hide_vin = "true";
                }

                //loop through each inventory

                if (!empty($vehicleHistoryAPIConfig->value)) {

                    $complete_inventory = $this->repository
                        ->filterDataByMultipleValues('status', ["Instock", "Sold"])->onlyVisible()->findAll();

                    foreach ($complete_inventory as $inventory) {
                        $vinHistoryResult = $this->carHistoryAPIInstance->getCarfaxReport($vehicleHistoryAPIConfig->value, $inventory['vin'], $hide_vin);
                        if (!empty($vinHistoryResult['success'])) {

                            $row = $this->repository->getFile('Vehicle History Report', $inventory['inventory_id']);

                            if (count($row) > 0) {
                                $file_path = $row[0]['file_url'];
                                if (gettype($file_path) == "string") {
                                    CloudStorageFactory::getInstance()->delete($file_path);
                                }
                            }

                            $row = $this->repository->getFile('Vehicle History Badge', $inventory['inventory_id']);

                            if (count($row) > 0) {
                                $file_path = $row[0]['file_url'];
                                if (gettype($file_path) == "string") {
                                    CloudStorageFactory::getInstance()->delete($file_path);
                                }
                            }

                            $data = [];
                            $data['file_url'] = $vinHistoryResult['VhrReportUrl'];
                            $data['inventory_id'] = $inventory['inventory_id'];
                            $data['header'] = 'Vehicle History Report';
                            $data['file'] = 0;
                            $this->repository->storeFile($data);

                            $data = [];
                            $data['file_url'] = $vinHistoryResult['BadgesImageUrl'];
                            $data['inventory_id'] = $inventory['inventory_id'];
                            $data['header'] = 'Vehicle History Badge';
                            $data['file'] = 0;
                            $this->repository->storeFile($data);
                        }
                    }
                }
            } catch (\Exception $ex) {
                log_message('warning', $ex->getMessage());
            } finally {
                $db->setDatabase(getenv('database.default.database'));

                $minutes = rand(3, 5) * 10;
                $current = Carbon::now('UTC');
                $scheduled_datetime = $current->copy()->addMinutes($minutes);

                $this->updateSchedule($client['client_id'], $scheduled_datetime);
            }
        }
    }

    private function updateSchedule($client_id, $scheduled_datetime = NULL): void
    {
        try {
            $db = \Config\Database::connect();
            $db->setDatabase(getenv('database.default.database'));
            $builder = $db->table('clients');
            $builder->where('client_id', $client_id);
            $builder->update(['car_history_schedule' => $scheduled_datetime]);
        } catch (\Exception $ex) {
            log_message('warning', $ex->getMessage());
        }
    }
}
