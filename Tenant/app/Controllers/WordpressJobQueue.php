<?php

namespace Tenant\app\Controllers;

use App\Repositories\WordpressJobRepository;
use Carbon\Carbon;
use CodeIgniter\Cache\CacheFactory;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Controller;
use Config\NonPersistantCache;
use Tenant\app\Libraries\WebsiteCache\WebsiteCacheFactory;

set_time_limit(0);

/**
 * WordpressJobQueue This method iterates through the jobs for the wordpress
 * 
 */
class WordpressJobQueue extends Controller
{

    /**
     * repository 
     *
     * @var WordpressJobRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new WordpressJobRepository();
    }

    public function add(...$arg)
    {
        $key = $arg[0];

        $cache_factory = CacheFactory::getHandler(new NonPersistantCache());
        $cache_data = $cache_factory->get($key);
        if ($cache_data) {
            $payload = json_decode($cache_data, true);

            if (!empty($payload['url'])) {
                $delay = !empty($payload['delay']) ? (int) $payload['delay'] : 2;
                $current = Carbon::now('UTC');
                $schedule = $current->copy()->addMinutes($delay);

                $this->repository->createOrUpdate(['url' => $payload['url'], 'schedule' => $schedule, 'website_type' => $payload['website_type']]);
            }
        }
    }

    public function process()
    {
        $jobs = $this->repository->scheduleQueueBasedOnColumn('schedule');

        foreach ($jobs['data_list'] as $job) {
            try {

                $single_job = $job;
                (WebsiteCacheFactory::getInstance($single_job['website_type']))->purge($single_job['url']);

                if (!empty($single_job)) {
                    $this->repository->delete($single_job["id"]);
                    sleep(5);
                }

            } catch (\Exception $ex) {
                CLI::write($ex->getMessage());
                 $this->repository->delete($job["id"]);
            }
        }
    }
}
