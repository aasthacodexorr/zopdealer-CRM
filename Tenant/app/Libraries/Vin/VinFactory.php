<?php

namespace Tenant\app\Libraries\Vin;

use Tenant\app\Interfaces\VinInterface;

/**
 * VinFactory This should be called for fetching the VIN instance rather than fetching vin directly.
 */
class VinFactory
{
    public static function getVinInstance(): VinInterface
    {
        return new VinAudit();
    }
}
