<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class GooglePlacesThrottle implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {
        $throttler = Services::throttler(false);

        $host = $request->getServer('HTTP_HOST');

        if ($throttler->check(md5($host) . '_google_places_throttle', 1000, MONTH) === false) {
            return Services::response()->setStatusCode(429);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // ...
    }
}
