<?php

namespace Tenant\app\Libraries\RegionalSeed;

use Tenant\app\Interfaces\RegionalSeedInterface;

class RegionalSeedFactory
{
    private static $class_path = "Tenant\app\Libraries\RegionalSeed\\";

    public static function getInstance(string $region): ?RegionalSeedInterface
    {
        try {
            $myClass = self::$class_path . $region;
            if (class_exists($myClass)) {
                return new $myClass();
            }
        } catch (\Exception $ex) {
            echo 'Message: ' . $ex->getMessage();
        }
    }
}
