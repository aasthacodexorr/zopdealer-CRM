<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

use CodeIgniter\API\ResponseTrait;
use Tenant\app\Repositories\ConfigurationRepository;

/**
 * AuthService This checks if the service has been enabled for the Dealership or Not
 */
class AuthService implements FilterInterface
{
    use ResponseTrait;
    public function before(RequestInterface $request, $arguments = null)
    {
        $configuration_repository = new ConfigurationRepository();

        if ($arguments) {
            if (is_array($arguments)) {
                $configuration_keys = array_filter($arguments, function ($key) {
                    return    ! preg_match(
                        '/^throttle\.[a-zA-Z0-9_-]+\.[a-zA-Z0-9_-]+\.\d+\.[a-zA-Z0-9_-]+$/',
                        $key
                    );
                });

                foreach ($configuration_keys as $configuration_key) {
                    $configuration_entity = $configuration_repository->findConfigurationColumnsByKeyAsArray($configuration_key, ['value']);
                    if (isset($configuration_entity['value'])) {
                        if (empty($configuration_entity['value'])) {
                            header('HTTP/1.1 401 Unauthorized', true, 401);
                            die(json_encode(['error' => ERROR_RESOURCE_PERMISSION]));
                        }
                    }
                }
            }
        } else {
            header('HTTP/1.1 401 Unauthorized', true, 401);
            die(json_encode(['error' => ERROR_RESOURCE_PERMISSION]));
        }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
