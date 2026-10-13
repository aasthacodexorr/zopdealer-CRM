<?php

namespace Tenant\app\Libraries\Shortner;

class ZopUrlShortner
{

    public static function getShortUrl(string $url): string
    {

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, getenv('zop.urlshortner.api_url'));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HEADER, 0);            // No header in the result
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return, do not echo result
            curl_setopt($ch, CURLOPT_POST, 1);              // This is a POST request
            curl_setopt($ch, CURLOPT_POSTFIELDS, array(      // Data to POST
                'url'      => $url,
                'format'   => 'json',
                'action'   => 'shorturl',
                'username' => getenv('zop.urlshortner.username'),
                'password' => getenv('zop.urlshortner.password')
            ));

            // Fetch and return content
            $response = curl_exec($ch);

            curl_close($ch);

            $response = json_decode($response, true);

            return $response['shorturl'];
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
    }
}
