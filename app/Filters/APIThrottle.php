<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class APIThrottle implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {
        $throttler = Services::throttler(false);

        // Restrict an IP address to no more than 2 request
        // per three minutes.

        $unique_path = $request->getIPAddress() . $request->uri->getPath();

        if ($throttler->check(md5($unique_path), 3, SECOND * 30) === false) {
            return Services::response()->setStatusCode(429);
        }
    }


    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // ...
    }
}
