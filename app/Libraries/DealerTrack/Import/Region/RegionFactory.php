<?php

namespace App\Libraries\DealerTrack\Import\Region;

/**
 * RegionFactory This method creates the instance of the region based on the region identifier passed by Dealer Track Import
 */
class RegionFactory
{
    public static function getInstance(String $region_identifier): ?RegionAbstract
    {
        switch ($region_identifier) {
            case 'CA-AB':
                return new Alberta();
                break;

            case 'CA-ON':
                return new Ontario();
                break;

            case 'CA-QC':
                return new Quebec();
                break;

            case 'CA-MB':
                return new Manitoba();
                break;

            case "CA-BC":
                return new BritishColumbia(); 
                break;

            default:
                return new DefaultCARegion();
                break;
        }
    }
}
