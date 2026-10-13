<?php

namespace App\Libraries\DealerTrack;

interface DealerTrackImportInterface
{

    public function getPrimaryTaxRate(): float;

    public function getSecondaryTaxRate(): float;
}
