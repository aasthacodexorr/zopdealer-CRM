<?php

namespace App\Helpers;

use CodeIgniter\Cache\CacheFactory;
use Config\NonPersistantCache;

/**
 * TenantNonPersistantCache This class wraps the initialization and working on a tenant based non persistant cache mechanism
 */
class TenantNonPersistantCache
{
    public static function delete(String $key): void
    {
        try {
            $cache_factory = CacheFactory::getHandler(new NonPersistantCache());
            $cache_key = md5(db_connect()->database) . '_' . $key;
            $cache_factory->delete($cache_key);
        } catch (\Exception $ex) {
            log_message('info', $ex->getMessage());
        }
    }

    public static function save(String $key, $data, int $ttl = 300)
    {
        try {
            $cache_factory = CacheFactory::getHandler(new NonPersistantCache());
            $cache_key = md5(db_connect()->database) . '_' . $key;
            $cache_factory->save($cache_key, $data, $ttl);
        } catch (\Exception $ex) {
            log_message('info', $ex->getMessage());
        }
    }

    public static function get(String $key)
    {
        try {
            $cache_factory = CacheFactory::getHandler(new NonPersistantCache());
            $cache_key = md5(db_connect()->database) . '_' . $key;
            return $cache_factory->get($cache_key);
        } catch (\Exception $ex) {
            log_message('info', $ex->getMessage());
        }
    }
}
