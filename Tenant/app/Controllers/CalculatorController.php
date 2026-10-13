<?php

namespace Tenant\app\Controllers;

use Tenant\app\Libraries\Calculator\WorksheetCalculator;
use Tenant\app\Repositories\ConfigurationRepository;

class CalculatorController extends \App\Controllers\BaseController
{
    /**
     * configuration_repository
     *
     * @var ConfigurationRepository
     */
    private $configuration_repository;

    public function __construct()
    {
        $this->configuration_repository = new ConfigurationRepository();
    }

    public function calculateLineTotal()
    {
        $data = $this->request->getPost();

        $validated = $this->validate([
            'amount'  => [
                "label" => 'Amount',
                "rules" => 'required|decimal'
            ],
            'discount'  => [
                "label" => 'Discount',
                "rules" => 'permit_empty|decimal'
            ],
            'quantity'  => [
                "label" => 'Quantity',
                "rules" => 'permit_empty|decimal'
            ],
            'tax_type'  => [
                "label" => 'Primary Tax',
                "rules" => 'permit_empty'
            ],
            'tax_type_secondary'  => [
                "label" => 'Secondary Tax',
                "rules" => 'permit_empty'
            ],
            'tax_amount_total'  => [
                "label" => 'Total Tax Amount',
                "rules" => 'permit_empty|decimal'
            ]

        ]);

        if ($validated) {


            $response = [];

            extract($data);

            $business_tax_settings = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_tax_settings', 'value');
            $business_tax_settings = $business_tax_settings->value;
            $business_tax_settings = json_decode($business_tax_settings, true);

            $has_primary_tax_selected = array_filter($business_tax_settings, function ($tax) use ($tax_type) {
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
                foreach ($business_tax_settings as $business_tax) {
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

            $response['tax_amount'] = round($tax_amount, 2);
            $response['tax_percentage'] = round($tax_percentage, 2);

            $response['tax_type_secondary'] = $tax_type_secondary;
            $response['tax_percentage_secondary'] = round($tax_percentage_secondary, 2);
            $response['tax_amount_secondary'] = round($tax_amount_secondary, 2);

            $response['tax_amount_total'] = round($tax_amount_total, 2);

            $response['sub_total'] = round($sub_total, 2);

            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    public function calculateFinancingAmount()
    {
        $data = $this->request->getPost();

        $response = [];
        $validated = $this->validate([
            'vehicle_price'  => [
                "label" => 'Vehicle Price',
                "rules" => 'required|decimal'
            ],
            'additional_fees'  => [
                "label" => 'Additional Fees',
                "rules" => 'permit_empty|decimal'
            ],
            'trade_in_value'  => [
                "label" => 'Trade-in Value',
                "rules" => 'permit_empty|decimal'
            ],
            'lien_value'  => [
                "label" => 'Lien Value',
                "rules" => 'permit_empty|decimal'
            ],
            'downpayment'  => [
                "label" => 'Downpayment',
                "rules" => 'permit_empty|decimal'
            ],
            'duration'  => [
                "label" => 'Duration',
                "rules" => 'required|numeric'
            ],
            'tax_1'  => [
                "label" => 'Tax 1',
                "rules" => 'permit_empty|decimal'
            ],
            'tax_2'  => [
                "label" => 'Tax 2',
                "rules" => 'permit_empty|decimal'
            ],
            'interest_rate'  => [
                "label" => 'Interest Rate',
                "rules" => 'permit_empty|decimal'
            ]
        ]);

        if ($validated) {

            try {
                $vehicle_price = $this->setFloatValue($data['vehicle_price']);
                $additional_fees = $this->setFloatValue($data['additional_fees']);
                $trade_in_value = $this->setFloatValue($data['trade_in_value']);
                $lien_value = $this->setFloatValue($data['lien_value']);
                $downpayment = $this->setFloatValue($data['downpayment']);
                $months = $duration = $this->setFloatValue($data['duration']);
                $tax_1 = $this->setFloatValue($data['tax_1']);
                $tax_2 = $this->setFloatValue($data['tax_2']);
                $interest_rate = $this->setFloatValue($data['interest_rate']);

                //Taxable Amount = Vehicle Price + Additional Fees
                $taxable_amount = $vehicle_price + $additional_fees;

                //Trade In Amount is directly deducted to Taxable Amount
                $taxable_amount -= $trade_in_value;

                //Add Tax 1 & Tax 2 to the Taxable Amount
                $taxable_amount = $taxable_amount + ($taxable_amount * 0.01 * ($tax_1 + $tax_2));

                //Add Lien Amount to Taxable Amount
                $taxable_amount += $lien_value;

                $monthly_interest = $interest_rate / 1200;

                $calculate_financing_amount = $taxable_amount - $downpayment;

                $response['monthly_amount'] = $calculate_financing_amount * $monthly_interest * (pow(1 + $monthly_interest, $months) / (pow(1 + $monthly_interest, $months) - 1));

                $response['total_repayment_amount'] = $response['monthly_amount'] * $duration;

                $months = ($months / 12) * 26;

                $monthly_interest = $interest_rate / 2600;

                $response['biweekly_amount'] = $calculate_financing_amount * $monthly_interest * (pow(1 + $monthly_interest, $months) / (pow(1 + $monthly_interest, $months) - 1));

                $monthly_interest = $interest_rate / 5200;

                $months *= 2;

                $response['weekly_amount'] = $calculate_financing_amount * $monthly_interest * (pow(1 + $monthly_interest, $months) / (pow(1 + $monthly_interest, $months) - 1));

                $response['financing_amount'] = $calculate_financing_amount;
                $this->response->setContentType('application/json')->setJSON($response)->send()->setHeader('Access-Control-Allow-Origin', '*');
            } catch (\Exception $ex) {
                $this->response->setContentType('application/json')->setJSON(['monthly_amount' => 0, 'biweekly_amount' => 0, 'weekly_amount' => 0, 'financing_amount' => 0])->send()->setHeader('Access-Control-Allow-Origin', '*');
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send()->setHeader('Access-Control-Allow-Origin', '*');
        }
    }

    public function buildWorksheet()
    {
        $data = $this->request->getPost();

        $validated = $this->validate([
            'purchase_price'  => [
                "label" => 'Selling Price',
                "rules" => 'permit_empty|decimal'
            ],
            'fees_total'  => [
                "label" => 'Fees Total',
                "rules" => 'permit_empty|decimal'
            ],
            'accessories_total'  => [
                "label" => 'Accessories Total',
                "rules" => 'permit_empty|decimal'
            ],
            'warranty_total_amount'  => [
                "label" => 'Warranties Total',
                "rules" => 'permit_empty|decimal'
            ],
            'insurance_total_amount'  => [
                "label" => 'Insurances Total',
                "rules" => 'permit_empty|decimal'
            ],
            'downpayment'  => [
                "label" => 'Downpayment',
                "rules" => 'permit_empty|decimal'
            ],
            'fees_tax_total'  => [
                "label" => 'Fees Total',
                "rules" => 'permit_empty|decimal'
            ],
            'accessories_tax_total'  => [
                "label" => 'Accessories Total',
                "rules" => 'permit_empty|decimal'
            ],
            'warranty_tax_amount_total'  => [
                "label" => 'Warranty Tax Total',
                "rules" => 'permit_empty|decimal'
            ],
            'insurance_tax_amount_total'  => [
                "label" => 'Insurance Tax Total',
                "rules" => 'permit_empty|decimal'
            ],
            'licence_fee'  => [
                "label" => 'Licence Fee',
                "rules" => 'permit_empty|decimal'
            ],
            'lien_registration_fee'  => [
                "label" => 'Lien Registration Fee',
                "rules" => 'permit_empty|decimal'
            ],
            'fuel_fee'  => [
                "label" => 'Gasoline Fee',
                "rules" => 'permit_empty|decimal'
            ],
            'trade_value'  => [
                "label" => 'Trade Value',
                "rules" => 'permit_empty|decimal'
            ],
            'lien_payout'  => [
                "label" => 'Lien Amount',
                "rules" => 'permit_empty|decimal'
            ],
            'term'  => [
                "label" => 'Term',
                "rules" => 'permit_empty|is_natural'
            ],
            'interest_rate'  => [
                "label" => 'Interest Rate',
                "rules" => 'permit_empty|decimal'
            ],
            'payment_frequency'  => [
                "label" => 'Payment Frequency',
                "rules" => 'permit_empty'
            ],
            'override_finance_calculation' => [
                "label" => 'Override Finance Calculation',
                "rules" => 'permit_empty'
            ],
            'interest_amount'  => [
                "label" => 'Interest Rate',
                "rules" => 'permit_empty|decimal'
            ]
        ]);

        if ($validated) {

            $does_total_balance_cost_includes_interest = $this->configuration_repository->findConfigurationColumnsByKey('total_balance_cost_includes_interest')->value;
            $does_total_balance_cost_includes_interest = $does_total_balance_cost_includes_interest == "1" ? true : false;

            $data['does_total_balance_cost_includes_interest'] = $does_total_balance_cost_includes_interest;

            $override_finance_calculation = !empty($data['override_finance_calculation']) ? true : false;
            $data['override_finance_calculation'] = $override_finance_calculation;

            $response = (new WorksheetCalculator())->buildWorksheet($data);
            $this->response->setContentType('application/json')->setJSON($response)->send()->setHeader('Access-Control-Allow-Origin', '*');
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send()->setHeader('Access-Control-Allow-Origin', '*');
        }
    }

    private function setFloatValue($value): float
    {
        return !empty($value) ? floatval($value) : 0;
    }
}
