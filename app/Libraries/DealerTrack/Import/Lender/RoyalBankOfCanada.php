<?php

namespace App\Libraries\DealerTrack\Import\Lender;

class RoyalBankOfCanada extends DefaultLender
{
    protected array $taxable_fees = ['InstallationDeliveryOtherCosts', 'installation_and_delivery'];

    protected array $non_taxable_fees = ["GeneralAdministrationFee", 'admin_fee'];

    protected array $taxable_warranties = [
        'ExtendedWarranty',
        'ReplacementWarranty',
        'AppearanceProtection',
        'RimTire',
        'extended_warranty',
        'replacement_warranty',
        'appearance_protection',
        'tire_and_rim',
        'excess_wear'

    ];

    protected array $non_taxable_warranties = [];

    protected array $taxable_insurances = [
        'LifeInsurance',
        'AHInsurance',
        'life_insurance',
        'ah_insurance',
        'critical_illness',
        'creditor_insurance_other'
    ];

    protected array $warranties_as_fees = ['Maintenance', 'maintenance'];
}
