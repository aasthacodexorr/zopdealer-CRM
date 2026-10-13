<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

use CodeIgniter\API\ResponseTrait;

/**
 * RestrictedRoleAccess This Allows Access To Restricted Roles Only
 */
class RestrictedRoleAccess implements FilterInterface
{
    use ResponseTrait;
    public function before(RequestInterface $request, $arguments = null)
    {

        if (!(service('authPolicy'))->hasElevatedRoles()) {
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
