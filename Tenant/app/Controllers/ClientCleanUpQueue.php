<?php

namespace Tenant\app\Controllers;

use App\Repositories\ClientRepository;
use Carbon\Carbon;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Controller;
use Tenant\app\Repositories\InventoryRepository;

set_time_limit(0);

/**
 * ClientCleanUpQueue This method iterates through inactive clients to perform the cleanup. 
 */
class ClientCleanUpQueue extends Controller
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


        $clients = $this->client_repository->onlyDeleted()->scheduleQueueBasedOnColumn('client_cleanup_schedule');
        $db = \Config\Database::connect();

        $clients = $clients['data_list'];

        foreach ($clients as $client) {
            try {
                $current = Carbon::now('UTC');
                $delete_date = Carbon::createFromTimeString($client['deleted_at']);
                $difference = $current->diffInDays($delete_date);

                CLI::write("Deleted Client :" . $client['subdomain'] . '_' . $client['client_hash']);

                if ($difference > 120) {
                    CLI::write("Purging :" . $client['subdomain'] . '_' . $client['client_hash']);

                    //@todo Remove Syndication Queue Entries
                    //Here you can pass the $client['client_id'] or other key from the clients table
                    //to remove the data from syndication or other tables in SuperDB.

                    $db->setDatabase($client['subdomain'] . '_' . $client['client_hash']);

                    //  Resources Purge
                    $this->repository->purgeAllInventory();

                    //  Database Purge
                    $forge = \Config\Database::forge();
                    $forge->dropDatabase($client['subdomain'] . '_' . $client['client_hash']);


                    //  Remove entry from master Database

                    //  Delete Client Database and entry from cache database.

                    $db->setDatabase(getenv('database.default.database'));
                    $this->deleteClientFromSuperDB($client['client_id']);
                }

                $current = Carbon::now('UTC');
                $scheduled_datetime = $current->copy()->addDays(1);

                $this->updateSchedule($client['client_id'], $scheduled_datetime);
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
        $builder->update(['client_cleanup_schedule' => $scheduled_datetime]);
    }

    private function deleteClientFromSuperDB($client_id)
    {
        $db = \Config\Database::connect();
        $db->setDatabase(getenv('database.default.database'));
        $builder = $db->table('clients');
        $builder->where('client_id', $client_id);
        $builder->limit(1);
        $builder->delete();
    }
}
