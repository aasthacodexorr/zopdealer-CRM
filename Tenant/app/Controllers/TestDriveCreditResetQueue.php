<?php

namespace Tenant\app\Controllers;

use App\Repositories\ClientRepository;
use Carbon\Carbon;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Controller;
use Tenant\app\Repositories\ConfigurationRepository;

set_time_limit(0);

/**
 * CreditResetScheduleQueue This method iterates through clients to reset the credits 
 */
class TestDriveCreditResetQueue extends Controller
{

    private $client_repository;

    /**
     * configuration_repository
     *
     * @var ConfigurationRepository
     */
    private $configuration_repository;

    public function __construct()
    {
        $this->client_repository = new ClientRepository();
        $this->configuration_repository = new ConfigurationRepository();
    }


    public function monthly()
    {
        $clients = $this->client_repository->scheduleQueueBasedOnColumn('monthly_credit_reset_testdrive');
        $db = \Config\Database::connect();

        $clients = $clients['data_list'];

        foreach ($clients as $client) {
            try {

                $current = Carbon::now('UTC');
                $scheduled_datetime = $current->copy()->addMonth()->firstOfMonth();

                $this->updateSchedule($client['client_id'], $scheduled_datetime);

                $db->setDatabase($client['subdomain'] . '_' . $client['client_hash']);

                $testdrive_package = $this->configuration_repository->findConfigurationColumnsByKey('testdrive_package', 'value')->value;
                $available_testdrive_package = getenv('testdrive.package_'.$testdrive_package);

                $testdrive_available_credit = !empty($available_testdrive_package) ? $available_testdrive_package : 0; // fallback to Zero test drive
                $this->configuration_repository->setConfiguration(['testdrive_available_credit' => $testdrive_available_credit], false);
            } catch (\Exception $ex) {
                CLI::write("Exception was thrown : " . $ex->getMessage());

                $current = Carbon::now('UTC');
                $scheduled_datetime = $current->copy()->addMonth()->firstOfMonth();
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
        $builder->update(['monthly_credit_reset_testdrive' => $scheduled_datetime]);
    }
}
