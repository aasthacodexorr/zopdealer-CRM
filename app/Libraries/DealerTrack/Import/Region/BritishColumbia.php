<?php

namespace App\Libraries\DealerTrack\Import\Region;

use App\Libraries\DealerTrack\DealerTrackImportInterface;

class BritishColumbia extends RegionAbstract
{

    public function setTaxRates(DealerTrackImportInterface $dealerTrackImport): array
    {
        parent::setTaxRates($dealerTrackImport);

        $this->warranty_tax_type_secondary = 'Tax Exempt';
        $this->warranty_secondary_tax_rate = 0;

        return [
            'tax_type_primary' => $this->tax_type_primary,
            'primary_tax_rate' => $this->primary_tax_rate,
            'tax_type_secondary' => $this->tax_type_secondary,
            'secondary_tax_rate' => $this->secondary_tax_rate,
            'warranty_tax_type_primary' => $this->warranty_tax_type_primary,
            'warranty_primary_tax_rate' => $this->warranty_primary_tax_rate,
            'warranty_tax_type_secondary' => $this->warranty_tax_type_secondary,
            'warranty_secondary_tax_rate' => $this->warranty_secondary_tax_rate,
            'insurance_tax_type_primary' => $this->insurance_tax_type_primary,
            'insurance_primary_tax_rate' => $this->insurance_primary_tax_rate,
            'insurance_tax_type_secondary' => $this->insurance_tax_type_secondary,
            'insurance_secondary_tax_rate' => $this->insurance_secondary_tax_rate
        ];
    }
}
