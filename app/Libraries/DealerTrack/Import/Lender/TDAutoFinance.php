<?php

namespace App\Libraries\DealerTrack\Import\Lender;

class TDAutoFinance extends DefaultLender
{
    protected array $taxable_fees = [
        'InstallationDeliveryOtherCosts',
        'DealerAdministrationFee',
        'installation_and_delivery',
        'dealer_admin_fee',
    ];

    protected array $non_taxable_fees = ["GeneralAdministrationFee", 'lender_admin_fee'];

    protected array $taxable_warranties = ['ExtendedWarranty', 'extended_warranty'];


    protected array $non_taxable_warranties = [];

    protected array $taxable_insurances = [
        'LifeInsurance',
        'AHInsurance',
        'life_insurance',
        'ah_insurance'
    ];

    protected array $warranties_as_fees = ['ReplacementWarranty', 'replacement_warranty'];


    //Warranty mapping changes when changing region to Manitoba

    protected bool $has_other_aftermarket_fee = false;
}
