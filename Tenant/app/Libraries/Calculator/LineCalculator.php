<?php

namespace Tenant\app\Libraries\Calculator;

use Tenant\app\Repositories\ConfigurationRepository;

class LineCalculator
{

    private $business_tax_settings;

    public function __construct()
    {
        $this->business_tax_settings = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_tax_settings', 'value');
        $this->business_tax_settings = $this->business_tax_settings->value;
        $this->business_tax_settings = json_decode($this->business_tax_settings, true);
    }


    public function getLineDefaultTaxTypes(): array
    {
        $default_taxes = array_filter($this->business_tax_settings, function ($tax) {
            return intval($tax['default']) === 1;
        });

        $default_taxes = array_values($default_taxes);

        $tax_type = count($default_taxes) > 0 ? $default_taxes[0]['name'] : '';

        $tax_type_secondary = count($default_taxes) > 1 ? $default_taxes[1]['name'] : '';
        return compact("tax_type", "tax_type_secondary");
    }

    /**
     * calculate
     *
     * @param  float $amount
     * @param  float $discount
     * @param  int $quantity
     * @param  string $tax_type
     * @param  string $tax_type_secondary
     * @param  float $tax_amount_total
     * @return array
     */
    public function calculate(float $amount, float $discount = 0, int $quantity = 1, string $tax_type = '', string $tax_type_secondary = '', float $tax_amount_total = 0): array
    {

        $has_primary_tax_selected = array_filter($this->business_tax_settings, function ($tax) use ($tax_type) {
            return $tax['name'] === $tax_type;
        });

        $sub_total = floatval($amount) * floatval($quantity);
        $sub_total -= floatval($discount);

        $tax_percentage = 0;
        $tax_percentage_secondary = 0;
        $tax_amount = 0;
        $tax_amount_secondary = 0;

        if (empty($has_primary_tax_selected)) {

            //Reset tax_type_secondary
            $tax_type_secondary = "";
        } else {
            foreach ($this->business_tax_settings as $business_tax) {
                if ($tax_type == $business_tax['name']) {
                    $tax_percentage = $business_tax['value'];
                }

                if ($tax_type_secondary == $business_tax['name']) {
                    $tax_percentage_secondary = $business_tax['value'];
                }
            }

            $tax_amount = $sub_total * floatval($tax_percentage) * 0.01;
            $tax_amount_secondary = $sub_total * floatval($tax_percentage_secondary) * 0.01;
            $tax_amount_total = $tax_amount + $tax_amount_secondary;
        }

        $sub_total += $tax_amount_total;

        $response = [];

        $response['tax_type'] = $tax_type;
        $response['tax_amount'] = round($tax_amount, 2);
        $response['tax_percentage'] = round($tax_percentage, 2);

        $response['tax_type_secondary'] = $tax_type_secondary;
        $response['tax_percentage_secondary'] = round($tax_percentage_secondary, 2);
        $response['tax_amount_secondary'] = round($tax_amount_secondary, 2);

        $response['tax_amount_total'] = round($tax_amount_total, 2);

        $response['sub_total'] = round($sub_total, 2);

        return $response;
    }
}
