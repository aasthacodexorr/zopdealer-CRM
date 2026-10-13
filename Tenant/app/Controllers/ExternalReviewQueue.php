<?php

namespace Tenant\app\Controllers;

use App\Models\ClientModel;
use App\Repositories\ClientRepository;
use Carbon\Carbon;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Controller;
use Tenant\app\Libraries\ExternalReview\ExternalReviewFactory;
use Tenant\app\Repositories\ConfigurationRepository;

set_time_limit(0);

/**
 * ExternalReviewQueue This method iterates through clients for updating the reviews
 */
class ExternalReviewQueue extends Controller
{
    
    /**
     * configuration_repository
     *
     * @var ConfigurationRepository
     */
    private $configuration_repository;
    
    /**
     * external_review_factory
     *
     * @var ExternalReviewFactory
     */
    private $external_review_factory;

    private $client_repository;

    public function __construct()
    {
        $this->configuration_repository = new ConfigurationRepository();
        $this->external_review_factory = new ExternalReviewFactory();
        $this->client_repository = new ClientRepository();
    }


    public function process()
    {
        $clients = $this->client_repository->scheduleQueueBasedOnColumn('external_review_schedule');
        $db = \Config\Database::connect();

        $clients = $clients['data_list'];

        foreach ($clients as $client) {
            try {
                $db->setDatabase($client['subdomain'] . '_' . $client['client_hash']);
               
                $enable_review_object = $this->configuration_repository->findConfigurationColumnsByKey('enable_reviews', 'value');

                if (!empty($enable_review_object->value)) {
                    ($this->external_review_factory::getInstance('google'))->fetch();
                    ($this->external_review_factory::getInstance('facebook'))->fetch();
                    sleep(240);
                }
                $current = Carbon::now('UTC');
                $scheduled_datetime = $current->copy()->addDay();
                $this->updateSchedule($client['client_id'], $scheduled_datetime);
            } catch (\Exception $ex) {
                CLI::write("Exception was thrown : " . $ex->getMessage());
                $current = Carbon::now('UTC');
                $scheduled_datetime = $current->copy()->addDay();
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
        $builder->update(['external_review_schedule' => $scheduled_datetime]);
    }
}
