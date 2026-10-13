<?php

namespace App\Libraries\CloudStorage;

/**
 * CloudStorageFactory This Factory shall be used for retrieving CloudStorage Service.
 */
class CloudStorageFactory
{

    private static $instance = null;

    private static $secure_instance = null;

    public static function getInstance(): CloudStorageInterface
    {
        if (self::$instance == null) {
            self::$instance = new BunnyEdgeCloudStorage();
        }
        return self::$instance;
    }

    public static function getSecureInstance(): CloudStorageInterface
    {
        if (self::$secure_instance == null) {
            self::$secure_instance = new GoogleCloudStorage();
        }
        
        return self::$secure_instance;
    }
}
