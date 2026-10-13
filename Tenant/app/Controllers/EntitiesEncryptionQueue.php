<?php

namespace Tenant\app\Controllers;

use App\Repositories\ClientRepository;
use Carbon\Carbon;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Controller;
use Tenant\app\Repositories\CustomerCreditApplicationRepository;
use Tenant\app\Repositories\CustomerRepository;
use Tenant\app\Repositories\LeadResponseRepository;

set_time_limit(0);


/**
 * EntitiesEncryptionQueue This Queue is to Encrypt the Other Data in the Lead Response
 */
class EntitiesEncryptionQueue extends Controller
{

    private $client_repository;

    public function __construct()
    {
        $this->client_repository = new ClientRepository();
    }

    public function process()
    {
        $clients = $this->client_repository->scheduleQueueBasedOnColumn('lead_response_encryption_schedule');
        $db = \Config\Database::connect();

        $clients = $clients['data_list'];

        foreach ($clients as $client) {
            try {

                $minutes = rand(10, 20);
                $current = Carbon::now('UTC');
                $scheduled_datetime = $current->copy()->addMinutes($minutes);

                $this->updateSchedule($client['client_id'], $scheduled_datetime);
                $db->setDatabase($client['subdomain'] . '_' . $client['client_hash']);
                (new LeadResponseRepository())->encryptLeadResponses();
                (new CustomerRepository())->encryptCustomers();
                (new CustomerCreditApplicationRepository())->encryptIndividualCreditApplication();
                
            } catch (\Exception $ex) {
                CLI::write("Exception was thrown : " . $ex->getMessage());
                $minutes = rand(60, 90);
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
        $builder->update(['lead_response_encryption_schedule' => $scheduled_datetime]);
    }
}
