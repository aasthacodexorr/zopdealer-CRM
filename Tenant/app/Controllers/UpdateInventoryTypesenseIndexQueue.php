<?php

namespace Tenant\app\Controllers;

use App\Models\ClientModel;
use App\Repositories\ClientRepository;
use Carbon\Carbon;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Controller;
use Tenant\app\Models\InventoryWithDeletedModel;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\InventoryRepository;

set_time_limit(0);

/**
 * UpdateInventoryTypesenseIndexQueue This method iterates through clients for update Index.
 */
class UpdateInventoryTypesenseIndexQueue extends Controller
{

    private $client_repository;

    public function __construct()
    {
        $this->client_repository = new ClientRepository();
    }


    public function process()
    {
        $clients = $this->client_repository->scheduleQueueBasedOnColumn('update_inventory_typesense_index_schedule', 25);
        $db = \Config\Database::connect();

        $clients = $clients['data_list'];

        foreach ($clients as $client) {
            try {

                $minutes = rand(10, 40);
                $minutes += 420;
                $current = Carbon::now('UTC');
                $scheduled_datetime = $current->copy()->addMinutes($minutes);
                $this->updateSchedule($client['client_id'], $scheduled_datetime);

                $client_database = $client['subdomain'] . '_' . $client['client_hash'];
                $db->setDatabase($client_database);
                CLI::write("Request for:" . $client_database);

                $business_website = (new ConfigurationRepository())->findConfigurationColumnsByKeyAsArray('business_website', ['value']);

                if (!empty($business_website['value'])) {
                    exec("php index.php UpdateInventoryTypesenseIndexQueue processIsolatedInventoryTypesenseIndex $client_database > /dev/null 2>&1 &");
                    sleep(30);
                }

                CLI::write("Complete Request for:" . $client_database);
            } catch (\Exception $ex) {
                CLI::write("Exception was thrown : " . $ex->getMessage());

                $minutes = rand(600, 840);
                $current = Carbon::now('UTC');
                $scheduled_datetime = $current->copy()->addMinutes($minutes);
                $this->updateSchedule($client['client_id'], $scheduled_datetime);
            }
        }
    }

    private function updateSchedule($client_id, $scheduled_datetime = NULL): void
    {
        $db = \Config\Database::connect();
        $db->setDatabase(getenv('database.default.database'));
        $builder = $db->table('clients');
        $builder->where('client_id', $client_id);
        $builder->update(['update_inventory_typesense_index_schedule' => $scheduled_datetime]);
    }

    public function processIsolatedInventoryTypesenseIndex(...$arg): void
    {
        if (!empty($arg[0])) {

            try {

                $db = \Config\Database::connect();
                $db->setDatabase($arg[0]);

                $inventoryRepository = new InventoryRepository();
                $inventoryRepository->setModel(new InventoryWithDeletedModel());

                $i = 1;
                $inventory_ids = [];
                $start = time();
                $inventoryRepository->getModel()->chunk(300, static function ($data) use (&$i, &$inventory_ids, &$start) {
                    $i++;
                    array_push($inventory_ids, $data->inventory_id);
                    if ($i % 250 === 0) {
                        CLI::write(json_encode($inventory_ids));
                        (new InventoryRepository())->updateTypesenseIndexService($inventory_ids);
                        (new InventoryRepository())->purgeWebsiteCache([]);
                        $inventory_ids = [];

                        $end = time();
                        CLI::write("Execution time: " . ($end - $start) . " seconds");
                    }
                });

                if (!empty($inventory_ids)) {
                    CLI::write("After Loop");
                    CLI::write(json_encode($inventory_ids));
                    (new InventoryRepository())->updateTypesenseIndexService($inventory_ids);
                    (new InventoryRepository())->purgeWebsiteCache([]);
                }
            } catch (\Exception $ex) {
                log_message('notice', $ex->getMessage());
            }
        }
    }
}
