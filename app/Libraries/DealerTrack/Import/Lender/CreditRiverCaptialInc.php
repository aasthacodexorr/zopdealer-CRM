<?php

namespace App\Libraries\DealerTrack\Import\Lender;

class CreditRiverCaptialInc extends DefaultLender
{
    protected array $taxable_fees = ['InstallationDeliveryOtherCosts', 'DealerAdministrationFee'];

    protected array $non_taxable_fees = ['AquisitionFee'];

    protected array $taxable_warranties = ['ExtendedWarranty'];

    protected array $non_taxable_warranties = [];

    protected array $taxable_insurances = ['LifeInsurance', 'AHInsurance'];

    protected array $warranties_as_fees = [];

    protected bool $has_other_aftermarket_fee = false;
}
