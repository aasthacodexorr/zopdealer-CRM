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
class CreditResetScheduleQueue extends Controller
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
        $clients = $this->client_repository->scheduleQueueBasedOnColumn('monthly_credit_reset_schedule');
        $db = \Config\Database::connect();

        $clients = $clients['data_list'];

        foreach ($clients as $client) {
            try {

                $current = Carbon::now('UTC');
                $scheduled_datetime = $current->copy()->addMonth()->firstOfMonth();

                $this->updateSchedule($client['client_id'], $scheduled_datetime);

                $db->setDatabase($client['subdomain'] . '_' . $client['client_hash']);

                $zop_studio_monthly_recurring_credit_config = $this->configuration_repository->findConfigurationColumnsByKeyAsArray('zop_studio_monthly_recurring_credit', ['value']);
                $zop_studio_available_credit = !empty($zop_studio_monthly_recurring_credit_config['value']) ? $zop_studio_monthly_recurring_credit_config['value'] : 1000;
                $this->configuration_repository->setConfiguration(['zop_studio_available_credit' => $zop_studio_available_credit], false);
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
        $builder->update(['monthly_credit_reset_schedule' => $scheduled_datetime]);
    }
}
