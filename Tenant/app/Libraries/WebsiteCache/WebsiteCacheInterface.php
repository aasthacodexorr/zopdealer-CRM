<?php

namespace Tenant\app\Libraries\WebsiteCache;

/***
 * WebsiteCacheInterface defines the methods associated with cache on the websites
 */
interface WebsiteCacheInterface
{
    /**
     * purge
     *
     * @param  string $url Accepts URL as a string to clean up the website cache
     * @return void
     */
    public function purge(string $url): void;

    
    /**
     * getBulkDeleteURL
     *
     * @param  String $base_url
     * @param  String $slug
     * @return string
     */
    public function getBulkDeleteURL(String $base_url, String $slug = "inventory"): string;
}
