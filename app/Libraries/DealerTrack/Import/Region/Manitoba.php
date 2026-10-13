<?php

namespace App\Libraries\DealerTrack\Import\Region;

class Manitoba extends RegionAbstract
{
    protected String $insurance_tax_type_primary = 'RST';

    protected String $insurance_tax_type_secondary = 'Tax Exempt';

    protected float $insurance_primary_tax_rate = 7;

    protected float $insurance_secondary_tax_rate = 0;
}
