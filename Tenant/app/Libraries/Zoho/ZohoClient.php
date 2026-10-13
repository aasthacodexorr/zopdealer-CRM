<?php

namespace Tenant\app\Libraries\Zoho;

/**   
 * Zoho Subscription : Subscription API class functions
 * 
 * @package Zoho 
 */

class ZohoClient
{
    protected $token = null;
    protected $organizationId;
    protected $refresh_token;
    protected $client_id;
    protected $secret_id;

    const ZOHO_ACCOUNT_API_ENDPOINT = 'https://accounts.zoho.com/oauth/v2/token';
    const ZOHO_SUBSCRIPTION_API_ENDPOINT = 'https://subscriptions.zoho.com/api/v1/';

    /** 
     * Constructor for initalling required ZOHO api call parameters 
     */
    public function __construct()
    {
        $this->refresh_token = getenv('zx.api.rt');
        $this->client_id = getenv('zx.api.ct');
        $this->secret_id = getenv('zx.api.st');
        $this->organizationId = getenv('zx.api.oid');
        // keep this call at end
        if ($this->token == null) {
            $this->token = $this->getAccessToken();
        }
    }

    /** Prepare CURL request and get API response
     * 
     * @param string  $method type of method call 
     * @param string  $uri the sample data 
     * @param array   $headers additional headers 
     * @throws \Exception
     * @access protected 
     */
    protected function _call($method, $uri, array  $headers, array $body = [])
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $uri);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        }

        $response = curl_exec($curl);
        $api_response_info = curl_getinfo($curl);
        curl_close($curl);

        if ($api_response_info['http_code'] == 200) {
            return $response;
        }

        throw new \Exception('Request failed : ' .  $api_response_info['http_code']);
    }

    /** Prepare and make actual API calls 
     * 
     * @param string  $method type of method call 
     * @param string  $uri the sample data 
     * @param array   $headers additional headers 
     * @param string  $body for post data 
     * 
     * @return array all of the exciting sample options 
     * @access protected 
     */
    protected function sendRequest(string $method, string $uri, array $headers = [], array $body = [])
    {
        $request = $this->_call($method, $uri, $this->getRequestHeaders($headers), $body);
        return $request;
    }

    /** Process returned data
     * 
     * @param  object re-convert data object into usable object filterted format
     * @return object
     * @access protected 
     */
    protected function processResponse($data): object
    {
        $response = json_decode($data);
        if (!$response->code == 0) {
            throw new \Exception('Zoho Api subscription error : ' . $response->message);
        }
        return $response;
    }

    /** Header for API calls
     * 
     * @param array additional api header
     * @return array returned merged header
     * @access protected 
     */
    protected function getRequestHeaders(array $headers = []): array
    {
        $defaultHeaders = [
            'Authorization: Zoho-oauthtoken ' . $this->token,
            'X-com-zoho-subscriptions-organizationid: ' . $this->organizationId,
        ];

        return array_merge($defaultHeaders, $headers);
    }


    protected function getAccessToken()
    {
        $payload = [
            'refresh_token' => $this->refresh_token,
            'client_id'     => $this->client_id,
            'client_secret' => $this->secret_id,
            'grant_type'    => 'refresh_token',
            'redirect_uri'  => ''
        ];

        $request = $this->_call('POST', self::ZOHO_ACCOUNT_API_ENDPOINT, [], $payload);
        $access_token = json_decode($request);
        return $access_token->access_token;
    }
}
