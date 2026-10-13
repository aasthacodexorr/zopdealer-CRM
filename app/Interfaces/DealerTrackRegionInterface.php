<?php

namespace App\Interfaces;

use App\Libraries\DealerTrack\DealerTrackImportInterface;

/**
 * DealerTrackRegionInterface This class gives the structure of the Regional Variation for Dealer Track
 */
interface DealerTrackRegionInterface
{
    /**
     * setTaxRates This method provides tax rates for the variation regions
     *
     * @param  DealerTrackImportInterface $dealerTrackImport
     * @return array
     */
    public function setTaxRates(DealerTrackImportInterface $dealerTrackImport): array;

    /**
     * getTaxInsuranceTax   This method provides taxe rates for the insurance
     *
     * @return array
     */
    public function getAfterServiceDefaultTaxes(): array;
}
