<?php

namespace Tenant\app\Controllers;

use App\Repositories\ClientRepository;
use Carbon\Carbon;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Controller;
use Tenant\app\Libraries\ThirdPartyImportAPI\PBS;

set_time_limit(0);

/**
 * ThirdPartyAPIBasedInventoryImportQueue 
 * 
 */
class ThirdPartyAPIBasedInventoryImportQueue extends Controller
{
    private $client_repository;

    public function __construct()
    {
        $this->client_repository = new ClientRepository();
    }


    public function process()
    {
        $clients = $this->client_repository->scheduleQueueBasedOnColumn('third_party_api_based_inventory_import_schedule', 100);
        $db = \Config\Database::connect();
        $processing_integrations = 0;

        $clients = $clients['data_list'];

        foreach ($clients as $client) {
            try {

                if ($processing_integrations > 5) {
                    continue;
                }

                $minutes = rand(10, 40);
                $minutes += 420;
                $current = Carbon::now('UTC');
                $scheduled_datetime = $current->copy()->addMinutes($minutes);
                $this->updateSchedule($client['client_id'], $scheduled_datetime);
                $client_database = $client['subdomain'] . '_' . $client['client_hash'];
                $db->setDatabase($client_database);
                CLI::write($client_database);
                $pbs = new PBS();
                $integration_available = $pbs->checkAuthorization();
                if ($integration_available) {
                    ++$processing_integrations;
                    exec("php index.php ThirdPartyAPIBasedInventoryImportQueue processIsolatedAPIBasedInventory $client_database > /dev/null 2>&1 &");
                }
            } catch (\Exception $ex) {
                CLI::write("Exception was thrown : " . $ex->getMessage());
                $this->updateSchedule($client['client_id']);
            }
        }
    }

    public function processIsolatedAPIBasedInventory(...$arg): void
    {
        if (!empty($arg[0])) {
            $db = \Config\Database::connect();
            $db->setDatabase($arg[0]);
            $pbs = new PBS();
            $pbs->savePayload();
            $pbs->processInventories();
        }
    }


    public function resetSchedule(...$arg): void
    {
        $current = Carbon::now('UTC');
        $scheduled_datetime = $current->copy()->addMinutes(3);

        if (!empty($arg[0])) {
            $db = \Config\Database::connect();
            $db->setDatabase(getenv('database.default.database'));
            $builder = $db->table('clients');
            $builder->where('subdomain', $arg[0]);
            $builder->update(['third_party_api_based_inventory_import_schedule' => $scheduled_datetime]);
        }
    }

    private function updateSchedule($client_id, $scheduled_datetime = NULL): void
    {
        $db = \Config\Database::connect();
        $db->setDatabase(getenv('database.default.database'));
        $builder = $db->table('clients');
        $builder->where('client_id', $client_id);
        $builder->update(['third_party_api_based_inventory_import_schedule' => $scheduled_datetime]);
    }
}
