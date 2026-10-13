<?php

namespace App\Helpers;

/**
 * Utility This class provides generic utility functions for FB Lead processing.
 */
class Fbflead
{

    public static function formatRecord($data): array
    {
        $record = [];
        $dec = json_decode($data, TRUE);

        if (json_last_error() === JSON_ERROR_NONE) {
            if (is_array($dec)) {
                $record = [];

                foreach ($dec as $key => $val) {
                    $record[$val['name']] = $val['values'][0] ?? "";
                }
            }
        }

        return $record;
    }
}
