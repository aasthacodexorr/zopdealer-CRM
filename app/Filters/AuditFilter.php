<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

use CodeIgniter\API\ResponseTrait;
use Tenant\app\Libraries\Audit\AuditConstant;

class AuditFilter implements FilterInterface
{

    use ResponseTrait;

    public function before(RequestInterface $request, $arguments = null)
    {

        try {
            $user_email = !empty(service('authPolicy')->currentUserEmail()) ? service('authPolicy')->currentUserEmail() : 'noemail@example.com';
            $actor_type = service('authPolicy')->isSuperOwner() ? AuditConstant::ADMIN : AuditConstant::USER;

            $user_agent = "";
            if ($request->getUserAgent()) {
                $user_agent = $request->getUserAgent()->getAgentString();
            }

            if (service('authPolicy') && service('auditContext')) {
                service('auditContext')
                    ->setDealershipUid(md5(db_connect()->getDatabase()))
                    ->setIpAddress($request->getIPAddress())
                    ->setSource(AuditConstant::WEB)
                    ->setRequestId(bin2hex(random_bytes(64)))
                    ->setUserAgent($user_agent)
                    ->setActor($actor_type, service('authPolicy')->currentUserID(), $user_email);

            }

        } catch (\Throwable $exception) {
            log_message('notice', 'Audit Log: ' . $exception->getMessage());
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }
}