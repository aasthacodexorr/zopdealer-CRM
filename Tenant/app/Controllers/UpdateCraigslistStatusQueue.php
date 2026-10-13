<?php

namespace Tenant\app\Controllers;

use App\Models\ClientModel;
use App\Repositories\ClientRepository;
use Carbon\Carbon;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Controller;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\CraigslistRepository;

set_time_limit(0);

/**
 * UpdateCraigslistStatusQueue This updates the status of the Craigslist Listed Inventories.
 */
class UpdateCraigslistStatusQueue extends Controller
{
    private $client_repository;

    public function __construct()
    {
        $this->client_repository = new ClientRepository();
    }

    public function process()
    {
        $clients = $this->client_repository->scheduleQueueBasedOnColumn('craigslist_status_update_schedule');
        $db = \Config\Database::connect();

        $clients = $clients['data_list'];

        foreach ($clients as $client) {
            try {

                $minutes = rand(5, 8) * 10;
                $current = Carbon::now('UTC');
                $scheduled_datetime = $current->copy()->addMinutes($minutes);
                $this->updateSchedule($client['client_id'], $scheduled_datetime);
                $db->setDatabase($client['subdomain'] . '_' . $client['client_hash']);
                (new CraigslistRepository())->checkForInventoryUpdate();
            } catch (\Exception $ex) {
                CLI::write("Exception was thrown : " . $ex->getMessage());
                $this->updateSchedule($client['client_id']);
            }
        }
    }

    private function updateSchedule($client_id, $scheduled_datetime = NULL): void
    {
        $db = \Config\Database::connect();
        $db->setDatabase(getenv('database.default.database'));
        $builder = $db->table('clients');
        $builder->where('client_id', $client_id);
        $builder->update(['craigslist_status_update_schedule' => $scheduled_datetime]);
    }
}
