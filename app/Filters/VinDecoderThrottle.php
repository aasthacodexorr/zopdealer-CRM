<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class VinDecoderThrottle implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {
        $throttler = Services::throttler(false);

        $database = db_connect()->database;
        $requestIP = $request->getIPAddress();

        if (
            $throttler->check(md5($database) . '_vin_decoder_throttle', 300, MONTH) === false
            || $throttler->check(md5($requestIP) . '_vin_decoder_throttle', 15, DAY) === false
        ) {
            return Services::response()->setStatusCode(429);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // ...
    }
}
