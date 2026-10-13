<?php

namespace Tenant\app\Interfaces;

use CodeIgniter\Format\JSONFormatter;

/***
 * VinInterface defines the structure of the VIN services used by Zop Dealer
 */
interface VinInterface
{


    /**
     * lookupVehicleByVin
     *
     * @param  mixed $vin
     * @return void
     */
    function lookupVehicleByVin(String $vin): void;

    /**
     * lookupVehicleByTrimID
     *
     * @param  mixed $trim_id
     * @return void
     */
    function lookupVehicleByTrimID(String $trim_id): void;

    function format(): void;

    function getFormattedResponse(): array;

    /**
     * setDefaultValue
     *
     * @param  int $value
     * @return void
     */
    function setDefaultValue(int $value);
}
