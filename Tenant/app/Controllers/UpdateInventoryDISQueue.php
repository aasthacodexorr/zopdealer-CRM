<?php

namespace Tenant\app\Controllers;

use App\Repositories\ClientRepository;
use Carbon\Carbon;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Controller;
use Tenant\app\Repositories\InventoryRepository;

set_time_limit(0);

/**
 * UpdateInventoryDISQueue This method iterates through clients for update DIS.
 */
class UpdateInventoryDISQueue extends Controller
{
    private $client_repository;

    public function __construct()
    {
        $this->client_repository = new ClientRepository();
    }


    public function process()
    {
        $clients = $this->client_repository->scheduleQueueBasedOnColumn('inventory_dis_schedule', 100);
        $db = \Config\Database::connect();

        $clients = $clients['data_list'];

        foreach ($clients as $client) {
            try {

                $current = Carbon::now('UTC');
                $scheduled_datetime = $current->copy()->addDay();
                $this->updateSchedule($client['client_id'], $scheduled_datetime);

                $db->setDatabase($client['subdomain'] . '_' . $client['client_hash']);

                (new InventoryRepository())->updateDaysInStock();
            } catch (\Exception $ex) {
                CLI::write("Exception was thrown : " . $ex->getMessage());

                $current = Carbon::now('UTC');
                $scheduled_datetime = $current->copy()->addHour();
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
        $builder->update(['inventory_dis_schedule' => $scheduled_datetime]);
    }
}
