<?php

namespace Tenant\app\Libraries\WebsiteCache;

/**
 * WebsiteCacheFactory This factory is responsible for Website Cache related functionality
 */
class WebsiteCacheFactory
{
    public static function getInstance(String $wordpress_type): WebsiteCacheInterface
    {
        switch ($wordpress_type) {
            case '0':
                return new WordpressTypesense();
                break;

            case '2':
                return new Phaeton();
                break;

            default:
                return new WordpressTypesense();
                break;
        }
    }
}
