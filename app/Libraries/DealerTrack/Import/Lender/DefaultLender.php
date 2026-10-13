<?php

namespace App\Libraries\DealerTrack\Import\Lender;

use App\Interfaces\DealerTrackLenderInterface;

class DefaultLender implements DealerTrackLenderInterface
{
    protected array $taxable_fees = [
        'InstallationDeliveryOtherCosts',
        'DealerAdministrationFee',
        'installation_and_delivery',
        'dealer_admin_fee',
        'dealer_doc_fee',
        'document_fee',
        'lease_service_fee'
    ];

    protected array $non_taxable_fees = [
        'AquisitionFee',
        'GeneralAdministrationFee',
        'LeaseServiceFee',
        'MembershipFee',
        'admin_fee',
        'membership_share',
        'finance_related_fee',
        'lender_admin_fee',
        'administration_fee'

    ];

    protected array $taxable_warranties = [
        'ExtendedWarranty',
        'ReplacementWarranty',
        'Maintenance',
        'AppearanceProtection',
        'RimTire',
        'ExcessWear',
        'extended_warranty',
        'replacement_warranty',
        'protection_warranty',
        'maintenance',
        'appearance_protection',
        'tire_and_rim',
        'excess_wear'
    ];

    protected array $non_taxable_warranties = [];

    protected array $taxable_insurances = [
        'LifeInsurance',
        'AHInsurance',
        'CriticalIllness',
        'life_insurance',
        'ah_insurance',
        'critical_illness',
        'creditor_insurance_other'
    ];

    protected array $warranties_as_fees = [];

    protected bool $has_other_aftermarket_fee = true;

    protected bool $has_licence_fee = true;

    protected bool $has_other_insurance = false;

    public function getTaxableFees(): array
    {
        return $this->taxable_fees;
    }

    public function getNonTaxableFees(): array
    {
        return $this->non_taxable_fees;
    }

    public function getTaxableWarranties(): array
    {
        return $this->taxable_warranties;
    }

    public function getNonTaxableWarranties(): array
    {
        return $this->non_taxable_warranties;
    }

    public function getWarrantiesAsFees(): array
    {
        return $this->warranties_as_fees;
    }

    public function getTaxableInsurance(): array
    {
        return $this->taxable_insurances;
    }

    public function hasLicenceFee(): bool
    {
        return $this->has_licence_fee;
    }

    public function hasOtherAfterMarketFee(): bool
    {
        return $this->has_other_aftermarket_fee;
    }

    public function hasOtherInsurance(): bool
    {
        return $this->has_other_insurance;
    }

    public function addTaxableWarranties(String $fee): void
    {
        array_push($this->taxable_warranties, $fee);
    }

    public function addNonTaxableWarranties(String $fee): void
    {
        array_push($this->non_taxable_warranties, $fee);
    }

    public function addTaxableInsurance(String $fee): void
    {
        array_push($this->taxable_insurances, $fee);
    }
}
