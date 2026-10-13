<?php

namespace App\Libraries\DealerTrack\Import\Lender;

class SantadorConsumerInc extends DefaultLender
{
    protected array $taxable_fees = ['InstallationDeliveryOtherCosts', 'installation_and_delivery', 'document_fee'];

    protected array $non_taxable_fees = ["DealerAdministrationFee", 'administration_fee'];

    protected array $taxable_warranties = [
        'ExtendedWarranty',
        'ReplacementWarranty',
        'extended_warranty',
        'replacement_warranty'
    ];

    protected array $non_taxable_warranties = [];

    protected array $taxable_insurances = [
        'LifeInsurance',
        'AHInsurance',
        'life_insurance',
        'ah_insurance'
    ];

    protected array $warranties_as_fees = [];

    //DOCUMENTATION FEE is missing for direct integration

    protected bool $has_other_aftermarket_fee = false;
}
