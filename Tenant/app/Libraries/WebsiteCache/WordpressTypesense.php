<?php

namespace Tenant\app\Libraries\WebsiteCache;

/**
 * WordpressTypesense This defines the process request related to Typense based Wordpress websites of the clients
 */
class WordpressTypesense implements WebsiteCacheInterface
{
    public function purge($url): void
    {

        try {
            $url_components = parse_url($url);
            $api_url = $url_components['scheme'] . "://" . $url_components['host'] . '/website/cleanup';

            $curl = curl_init($api_url);
            curl_setopt($curl, CURLOPT_URL, $api_url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_FAILONERROR, TRUE);

            $headers = array(
                "Content-Type: application/x-www-form-urlencoded",
            );
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

            $data = "url=" . $url;

            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

            curl_exec($curl);
            curl_close($curl);

            $alternate_curl = curl_init($api_url);
            curl_setopt($alternate_curl, CURLOPT_URL, $api_url);
            curl_setopt($alternate_curl, CURLOPT_POST, true);
            curl_setopt($alternate_curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($alternate_curl, CURLOPT_FAILONERROR, TRUE);

            $headers = array(
                "Content-Type: application/x-www-form-urlencoded",
            );
            curl_setopt($alternate_curl, CURLOPT_HTTPHEADER, $headers);

            $data = "url=" . $url . "/";

            curl_setopt($alternate_curl, CURLOPT_POSTFIELDS, $data);

            curl_exec($alternate_curl);
            curl_close($alternate_curl);
        } catch (\Exception $ex) {
            log_message('info', $ex->getMessage());
        }
    }

    public function getBulkDeleteURL(String $base_url, String $slug = "inventory"): string
    {
        return $base_url . '/' . $slug .  '/';
    }
}
