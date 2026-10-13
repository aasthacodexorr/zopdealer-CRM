<?php

namespace Tenant\app\Libraries\WebsiteCache;

/**
 * Phaeton This defines the process request related to Phaeton based websites
 */
class Phaeton implements WebsiteCacheInterface
{
    public function purge($url): void
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, FALSE);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PURGE');
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);

            $x = curl_exec($ch);
            // Check the return value of curl_exec(), too
            if ($x === false) {
                throw new \Exception(curl_error($ch), curl_errno($ch));
            }
            curl_close($ch);
        } catch (\Exception $ex) {
            log_message('info', $ex->getMessage());
        }
    }

    public function getBulkDeleteURL(String $base_url, String $slug = "inventory"): string
    {
        return $base_url . '/' . $slug . '/*';
    }
}
