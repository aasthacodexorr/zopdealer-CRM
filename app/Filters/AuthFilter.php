<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

use CodeIgniter\API\ResponseTrait;

/**
 * AuthFilter This is similar to middleware which checks If the User Is Authenticated.
 */
class AuthFilter implements FilterInterface
{
    use ResponseTrait;
    public function before(RequestInterface $request, $arguments = null)
    {
        (service('authPolicy'))->checkAuthentication();
        (service('authPolicy'))->checkIfUserIsActive();

        if (!(service('authPolicy'))->isSuperOwner()) {
            if (!(service('tenantAccountPolicy'))->isAccountActive()) {
                header('HTTP/1.1 401 Unauthorized', true, 401);
                die(json_encode(['error' => 'Account Suspended']));
            }
        }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
