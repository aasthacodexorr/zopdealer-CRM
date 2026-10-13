<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

use CodeIgniter\API\ResponseTrait;


/**
 * Logger This Filter is used to log the user activities.
 */
class Logger implements FilterInterface
{
    use ResponseTrait;

    public function before(RequestInterface $request, $arguments = null) {}

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $response_service = \Config\Services::response();
        $request_service = \Config\Services::request();

        $user_email = !empty(service('authPolicy')->email) ? service('authPolicy')->email : 'Guest';
        $organization = !empty(service('authPolicy')->organization) ? service('authPolicy')->organization : 'None';
        $ip = $request->getIPAddress();
        $request_method = $request->getMethod();
        $uri = (string)$request->uri;
        //uri is considered as backup since REQUEST_URI may not be returned by some old browser.
        $url = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $response_code = $response->getStatusCode();
        $message = '';

        /***
         *  Message Format:
         * ip   email   organization    method  request_url     alternate_url    response_code   ?message
         */

        $log_type = 'info';
        if ($response_code >= 400) {
            $log_type = 'warning';
            $message = $response_service->getJSON();
        }

        if (strstr($uri, 'delete') && $response_code < 400) {
            $message = json_encode($request_service->getPost());
        }

        if (!file_exists(WRITEPATH . 'logs/' . ZD_DOMAIN)) {
            mkdir(WRITEPATH . 'logs/' . ZD_DOMAIN, 0777, true);
        }

        log_message($log_type, '{ip}    {email} {organization}  {method}  {uri}  {response_code}
        {url}
        {message}', [
            'ip' => $ip,
            'email' => $user_email,
            'organization'  => $organization,
            'method'    => $request_method,
            'uri'   => $uri,
            'url'   => $url,
            'response_code' => $response_code,
            'message'   => $message
        ]);
    }
}
