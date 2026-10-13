<?php

namespace Tenant\App\Helpers;

/**
 * Utility This class provides generic utility functions for Social.
 */
class SocialUtility
{

    /**
     * Values from ENV
     *     
     * @return string
     */
    public static function getValueFromEnv($key): string
    {
        return getEnv($key);
    }

    public static function getDbNameForState() : string
    {
        $state = db_connect()->database ?? "" ;
        return base64_encode($state);
    }
   
    /**
     * Removes all keys from associative array except the matching in the second array
     *  
     * @param array $array
     * @param array $keyToRemove    
     * @return void
     */     
    public static function keepKeysInAssociativeArray(array &$array, array $keysToKeep): void {
        foreach ($array as $key => $value) {
            if (!in_array($key, $keysToKeep)) {
                unset($array[$key]);
            }
        }
    } 

}
