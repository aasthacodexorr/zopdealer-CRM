<?php

namespace App\Libraries\DealerTrack\Import\Region;

use App\Interfaces\DealerTrackRegionInterface;
use App\Libraries\DealerTrack\DealerTrackImportInterface;
use Tenant\app\Repositories\ConfigurationRepository;

/**
 * RegionAbstract This Class Contains the blueprint for the variations based on the Regions
 */
abstract class RegionAbstract implements DealerTrackRegionInterface
{
    protected array $tax_mapping = [];

    protected String $tax_type_primary = '';

    protected String $tax_type_secondary = '';

    protected float $primary_tax_rate = 0;

    protected float $secondary_tax_rate = 0;

    protected String $warranty_tax_type_primary = '';

    protected String $warranty_tax_type_secondary = '';

    protected float $warranty_primary_tax_rate = 0;

    protected float $warranty_secondary_tax_rate = 0;

    protected String $insurance_tax_type_primary = 'Tax Exempt';

    protected String $insurance_tax_type_secondary = 'Tax Exempt';

    protected float $insurance_primary_tax_rate = 0;

    protected float $insurance_secondary_tax_rate = 0;

    public function __construct()
    {
        $business_tax_settings = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_tax_settings', 'value');
        $business_tax_settings = $business_tax_settings->value;
        $business_tax_settings = json_decode($business_tax_settings, true);

        foreach ($business_tax_settings as $tax_setting) {
            $this->tax_mapping[$tax_setting['name']] = $tax_setting['value'];
        }
    }

    public function setTaxRates(DealerTrackImportInterface $dealerTrackImport): array
    {
        $primary_tax_rate_on_worksheet = $dealerTrackImport->getPrimaryTaxRate();
        $secondary_tax_rate_on_worksheet = $dealerTrackImport->getSecondaryTaxRate();

        if (!empty($primary_tax_rate_on_worksheet)) {
            $tax_type_primarys = array_filter($this->tax_mapping, function ($tax) use ($primary_tax_rate_on_worksheet) {
                return $tax == $primary_tax_rate_on_worksheet;
            });

            if (count($tax_type_primarys) > 0) {
                $this->tax_type_primary = key($tax_type_primarys);
                $this->primary_tax_rate = (float) $primary_tax_rate_on_worksheet;
            }
        }

        if (!empty($secondary_tax_rate_on_worksheet)) {
            $tax_type_secondarys = array_filter($this->tax_mapping, function ($tax) use ($secondary_tax_rate_on_worksheet) {
                return $tax == $secondary_tax_rate_on_worksheet;
            });

            if (count($tax_type_secondarys) > 0) {
                $this->tax_type_secondary = key($tax_type_secondarys);
                $this->secondary_tax_rate = (float) $secondary_tax_rate_on_worksheet;
            }
        }

        $this->warranty_tax_type_primary = $this->tax_type_primary;
        $this->warranty_primary_tax_rate = $this->primary_tax_rate;
        $this->warranty_tax_type_secondary = $this->tax_type_secondary;
        $this->warranty_secondary_tax_rate = $this->secondary_tax_rate;

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

    public function getAfterServiceDefaultTaxes(): array
    {
        return [
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
