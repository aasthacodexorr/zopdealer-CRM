<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

use CodeIgniter\API\ResponseTrait;
use Config\Services;
use Tenant\app\Repositories\ConfigurationRepository;

/**
 * DynamicThrottleService This checks if the service should be throttled
 * Format: throttle.tenant.uniquename.totallimit.period for complete dealership based limit
 */
class DynamicThrottleService implements FilterInterface
{
    use ResponseTrait;
    public function before(RequestInterface $request, $arguments = null)
    {

        if ($arguments) {
            if (is_array($arguments)) {
                $configuration_keys = array_filter($arguments, function ($key) {
                    return   preg_match(
                        '/^throttle\.[a-zA-Z0-9_-]+\.[a-zA-Z0-9_-]+\.\d+\.[a-zA-Z0-9_-]+$/',
                        $key
                    );
                });

                foreach ($configuration_keys as $throttle_key) {
                    [$prefix, $type, $key, $limit, $period] = explode('.', $throttle_key);
                    $throttler = Services::throttler(false);

                    if (strtoupper($type) === "TENANT") {
                        $host = $request->getServer('HTTP_HOST');

                        if ($throttler->check(md5($host) . '_' . $key, intval($limit), $this->getTimePeriod($period)) === false) {
                            return Services::response()->setStatusCode(429);
                        }
                    }
                }
            }
        }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }

    private function getTimePeriod(string $period)
    {
        switch (strtoupper($period)) {
            case 'DAILY':
                return DAY;
            default:
                return   MONTH;
        }
    }
}
