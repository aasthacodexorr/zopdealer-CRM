<?php


namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class PublicAPIThrottle implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {
        $throttler = Services::throttler(false);

        // Restrict an IP address to no more than 2 request
        // per three minutes.

        $unique_path = $request->getServer('HTTP_HOST') . '_' . $request->getIPAddress() . $request->uri->getPath();

        if ($throttler->check(md5($unique_path), 30, SECOND * 30) === false) {
            return Services::response()->setStatusCode(429);
        }
    }


    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // ...
    }
}
