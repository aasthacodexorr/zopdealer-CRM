<?php

namespace App\Libraries\DealerTrack\Import\Lender;

class WindsorFamilyCreditUnion extends DefaultLender
{
    protected array $taxable_fees = ['InstallationDeliveryOtherCosts', 'installation_and_delivery'];

    protected array $non_taxable_fees = ['MembershipFee', 'membership_share'];

    protected array $taxable_warranties = ['ExtendedWarranty', 'extended_warranty'];

    protected array $non_taxable_warranties = [];

    protected array $taxable_insurances = [
        'LifeInsurance',
        'AHInsurance',
        'life_insurance',
        'ah_insurance'
    ];

    protected array $warranties_as_fees = [];

    protected bool $has_other_aftermarket_fee = false;

    protected bool $has_other_insurance = true;
}
