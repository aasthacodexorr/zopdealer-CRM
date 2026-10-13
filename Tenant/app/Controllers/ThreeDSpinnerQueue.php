<?php

namespace Tenant\app\Controllers;

use App\Libraries\CloudStorage\CloudStorageFactory;
use App\Repositories\ClientRepository;
use Carbon\Carbon;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Controller;
use Tenant\app\Libraries\ThreeDSpinner\Glo3D;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\InventoryRepository;

set_time_limit(0);

/**
 * ThreeDSpinnerQueue This method client iterates through clients to check if ThreeDSpinner is Enabled
 * And If So fetch for the available vehicles.
 */
class ThreeDSpinnerQueue extends Controller
{

    /**
     * repository 
     *
     * @var mixed
     */
    private $repository;

    private $client_repository;

    public function __construct()
    {
        $this->repository = new InventoryRepository();
        $this->client_repository = new ClientRepository();
    }

    public function process()
    {
        $clients = $this->client_repository->scheduleQueueBasedOnColumn('vehicle_three_d_spinner_video_schedule');
        $db = \Config\Database::connect();

        $clients = $clients['data_list'];

        foreach ($clients as $client) {
            try {
                $db->setDatabase($client['subdomain'] . '_' . $client['client_hash']);

                $glo3dUserName = (new ConfigurationRepository())->findConfigurationColumnsByKey('glo3d_username', 'value');
                $glo3dPassword = (new ConfigurationRepository())->findConfigurationColumnsByKey('glo3d_password', 'value');

                if (!empty($glo3dUserName->value) && !empty($glo3dPassword->value)) {

                    $glo3d = new Glo3D();
                    $glo3d->setAuthenticationHeader(base64_encode($glo3dUserName->value . ':' . $glo3dPassword->value));

                    $response = $glo3d->fetchResponse();

                    if (!empty($response['data'])) {
                        foreach ($response['data'] as $row) {
                            if (!empty($row['stock_number'])) {
                                $current_inventory = (new InventoryRepository())->findSingleInventoryByStockId($row['stock_number']);

                                if (!empty($current_inventory)) {
                                    if (!empty($current_inventory['inventory_id'])) {
                                        $this->repository->storeFile([
                                            'file_url' => $row['src'],
                                            'file' => 0,
                                            'header' => '3D Spinner Video',
                                            'inventory_id' => $current_inventory['inventory_id']
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            } catch (\Exception $ex) {
                log_message('warning', $ex->getMessage());
            } finally {
                $db->setDatabase(getenv('database.default.database'));

                $minutes = rand(3, 5) * 60;
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
            $builder->update(['vehicle_three_d_spinner_video_schedule' => $scheduled_datetime]);
        } catch (\Exception $ex) {
            log_message('warning', $ex->getMessage());
        }
    }
}
