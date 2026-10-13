<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

use CodeIgniter\API\ResponseTrait;

/**
 * TenantAccountFilter This is similiar to middleware which checks If the Account Is Active
 */
class TenantAccountFilter implements FilterInterface
{
    use ResponseTrait;
    public function before(RequestInterface $request, $arguments = null)
    {

        if (!(service('tenantAccountPolicy'))->isAccountActive()) {
            header('HTTP/1.1 401 Unauthorized', true, 401);
            die(json_encode(['error' => 'Account Suspended']));
        }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
