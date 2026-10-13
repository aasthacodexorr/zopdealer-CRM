<?php

namespace Tenant\app\Libraries\ThreeDSpinner;

class Glo3D
{

    private string $authentication_hash;

    /**
     * setAuthenticationHeader - This is Base64 Hashed username & [asswprd]
     *
     * @param  mixed $authentication_hash
     * @return self
     */
    public function setAuthenticationHeader(string $authentication_hash)
    {
        $this->authentication_hash = $authentication_hash;
        return $this;
    }

    /**
     * fetchResponse
     *
     * @return array
     */
    public function fetchResponse()
    {
        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://us-central1-glo3d-c338b.cloudfunctions.net/outbound/api/v1/inventory',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded',
                    'Authorization: Basic ' . $this->authentication_hash,
                    'Content-Length: 0'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            return json_decode($response, true);
        } catch (\Exception $ex) {
            log_message('info', "Some error was there in processing this request : " + $ex->getMessage());
            return ['errors' => $ex->getMessage()];
        }
    }
}
