<?php

namespace Tenant\app\Controllers;

use App\Repositories\ClientRepository;
use Carbon\Carbon;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Controller;
use Tenant\app\Repositories\CustomerRepository;
use Tenant\app\Repositories\DealRepository;
use Tenant\app\Repositories\DigitalVehicleInspectionRepository;
use Tenant\app\Repositories\InventoryRepository;
use Tenant\app\Repositories\LeadResponseRepository;
use Tenant\app\Repositories\PaymentRepository;
use Tenant\app\Repositories\ProductRepository;
use Tenant\app\Repositories\ReviewFeedbackRepository;
use Tenant\app\Repositories\VendorRepository;
use Tenant\app\Repositories\WorkOrderRepository;

set_time_limit(0);

/**
 * UpdateAllIndexQueue This method iterates through clients for update all the indexes of the clients.
 */
class UpdateAllIndexQueue extends Controller
{

    private $client_repository;

    public function __construct()
    {
        $this->client_repository = new ClientRepository();
    }


    public function process()
    {
        $clients = $this->client_repository->scheduleQueueBasedOnColumn('update_all_index_schedule', 10);
        $db = \Config\Database::connect();

        $clients = $clients['data_list'];

        foreach ($clients as $client) {
            try {

                $hours = rand(8, 10);
                $current = Carbon::now('UTC');
                $scheduled_datetime = $current->copy()->addHours($hours);
                $this->updateSchedule($client['client_id'], $scheduled_datetime);

                $db->setDatabase($client['subdomain'] . '_' . $client['client_hash']);
                (new InventoryRepository())->updateSearchIndex();
                (new LeadResponseRepository())->updateSearchIndex();
                (new CustomerRepository())->updateSearchIndex();
                (new VendorRepository())->updateSearchIndex();
                (new DealRepository())->updateSearchIndex();
                (new DigitalVehicleInspectionRepository())->updateSearchIndex([]);
                (new PaymentRepository())->updateSearchIndex([]);
                (new ProductRepository())->updateSearchIndex([]);
                (new WorkOrderRepository())->updateSearchIndex([]);
                (new ReviewFeedbackRepository())->updateSearchIndex([]);

                sleep(20);
            } catch (\Exception $ex) {
                CLI::write("Exception was thrown : " . $ex->getMessage());

                $hours = rand(8, 10);
                $current = Carbon::now('UTC');
                $scheduled_datetime = $current->copy()->addHours($hours);
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
        $builder->update(['update_all_index_schedule' => $scheduled_datetime]);
    }
}
