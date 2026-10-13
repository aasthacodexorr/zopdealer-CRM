<?php

namespace Tenant\app\Libraries\Calculator;

use ReflectionClass;

class WorksheetCalculator
{
    private float $purchase_price = 0;
    private float $discount = 0;
    private float $purchase_price_tax_amount_total = 0;

    private float $fees_total = 0;
    private float $fees_tax_total = 0;

    private float $accessories_total = 0;
    private float $accessories_tax_total = 0;

    private float $warranty_total_amount = 0;
    private float $warranty_tax_amount_total = 0;

    private float $trade_value = 0;
    private float $lien_payout = 0;
    private float $trade_equity = 0;

    private float $total_taxes = 0;

    private float $licence_fee = 0;
    private float $lien_registration_fee = 0;
    private float $fuel_fee = 0;

    private float $downpayment = 0;

    private float $insurance_total_amount = 0;
    private float $insurance_tax_amount_total = 0;

    private float $net_difference = 0;

    private bool $is_financed = false;

    private bool $does_total_balance_cost_includes_interest = false;

    private int $term = 0;

    private float $interest_rate = 0;

    private string $payment_frequency = "Monthly";

    private float $total_interest = 0;

    private float $interest_amount = 0;

    private bool $override_finance_calculation = false;

    /**
     * line_calculator
     *
     * @var LineCalculator
     */
    private $line_calculator;

    public function __construct()
    {
        $this->line_calculator = new LineCalculator();
    }


    /**
     * buildWorksheet
     *
     * @param  mixed $data
     * @return array
     */
    public function buildWorksheet(array $data): array
    {

        // subtotal + total tax + insurance tax + licence fee
        // minus total payments
        //minus trade_equity
        // add insurance total amount
        //add gasoline
        //add lien_registration_fee

        //add interest if interest in enabled.

        $this->setProperties($data);

        $subtotal = $this->primarySubTotal();

        $this->trade_equity = $this->calculateTradeInEquity();

        $vehicle_line_tax_details = $this->getTaxOnVehicleAdjustedPurchasePrice($this->purchase_price, 0, $this->trade_value);

        $this->purchase_price_tax_amount_total = $vehicle_line_tax_details['tax_amount_total'];

        $this->net_difference = $subtotal - $this->trade_value;

        $this->total_taxes = $this->purchase_price_tax_amount_total + $this->fees_tax_total + $this->accessories_tax_total + $this->warranty_tax_amount_total;

        $additional_fees = $this->lien_registration_fee + $this->licence_fee + $this->fuel_fee;

        $total_balance_due = $this->net_difference + $this->lien_payout +
            $this->total_taxes    + $additional_fees
            + $this->insurance_tax_amount_total + $this->insurance_total_amount
            - $this->downpayment;

        $response = [];

        $response['finance_amount'] = 0;

        if ($this->is_financed) {
            $response['finance_amount'] = $total_balance_due;

            if ($this->override_finance_calculation) {
                $total_balance_due += $this->interest_amount;
            } else {
                $finance_details = $this->calculateFinanceTerms($total_balance_due, $this->term, $this->interest_rate, $this->payment_frequency);
                unset($finance_details['downpayment']);

                $response = array_merge($finance_details, $response);

                if (!empty($finance_details['emi_amount'])) {
                    $this->total_interest = $this->calculateTotalInterest($finance_details['emi_amount'], $total_balance_due, $this->term, $this->payment_frequency);

                    if ($this->does_total_balance_cost_includes_interest) {
                        $total_balance_due += $this->total_interest;
                    }
                }
            }
        }

        $response['purchase_tax_type'] = $vehicle_line_tax_details['tax_type'];
        $response['purchase_tax_percentage'] = $vehicle_line_tax_details['tax_percentage'];
        $response['tax_on_purchase_price'] = $vehicle_line_tax_details['tax_amount'];
        $response['purchase_tax_type_secondary'] = $vehicle_line_tax_details['tax_type_secondary'];
        $response['tax_on_purchase_price_secondary'] = $vehicle_line_tax_details['tax_amount_secondary'];
        $response['purchase_tax_percentage_secondary'] = $vehicle_line_tax_details['tax_percentage_secondary'];
        $response['tax_on_purchase_total'] = $vehicle_line_tax_details['tax_amount_total'];

        $response['total_taxes'] = $this->total_taxes;
        $response['insurance_tax_amount_total'] = $this->insurance_tax_amount_total;
        $response['total_interest'] = $this->total_interest;
        $response['total_balance_due'] = $total_balance_due;
        $response['net_difference'] = $this->net_difference;
        $response['subtotal'] = $subtotal;
        $response['lien_payout'] = $this->lien_payout;
        $response['trade_equity'] = $this->trade_equity;
        $response['trade_value'] = $this->trade_value;
        $response['secondary_subtotal'] = $this->secondarySubTotal();
        return $response;
    }

    private function calculateFinanceTerms(float $amount = 0, int $term = 0, float $interest_rate = 0, string $payment_frequency = "Monthly", float $downpayment = 0)
    {
        try {
            $term_placeholder = $payment_frequency;
            $months =    is_numeric($term) ? (int) $term : 0;
            $annInterest =    is_numeric($interest_rate) ? (float) $interest_rate : 0;
            $monInt = $annInterest / 1200;

            switch ($term_placeholder) {
                case 'Weekly':
                    $term_placeholder = "Week";
                    $months = ($months / 12) * 52;
                    $monInt = $annInterest / 5200;
                    break;

                case 'Bi-Weekly':
                    $term_placeholder = "Bi-Week";
                    $months = ($months / 12) * 26;
                    $monInt = $annInterest / 2600;
                    break;

                default:
                    break;
            }

            $net_difference = $amount - $downpayment;
            $emi_amount = $net_difference * $monInt * (pow(1 + $monInt, $months) / (pow(1 + $monInt, $months) - 1));

            return [
                'emi_amount' => $emi_amount,
                'downpayment' => $downpayment,
                'amount' => $amount,
                'payment_frequency' => $payment_frequency,
                'interest_rate' => $interest_rate,
                'term' => $term,
            ];
        } catch (\Exception $ex) {
            return [
                'emi_amount' => '',
                'downpayment' => '',
                'amount' => '',
                'payment_frequency' => '',
                'interest_rate' => '',
                'term' => '',
            ];
        }
    }


    private function calculateTotalInterest(float $emi_amount = 0, float $amount = 0, int $term = 0, string $payment_frequency = "Monthly"): float
    {
        switch ($payment_frequency) {
            case 'Weekly':
                $term = ($term / 12) * 52;
                break;

            case 'Bi-Weekly':
                $term = ($term / 12) * 26;
                break;

            default:
                break;
        }

        $interest_amount = ($emi_amount * $term) - $amount;

        return $interest_amount > 0 ? $interest_amount : 0;
    }


    private function setProperties($data)
    {
        $reflection = new ReflectionClass($this);
        foreach ($reflection->getProperties() as $object) {
            if (!empty($data[$object->name])) {
                if (!in_array($object->name, ['payment_frequency', 'does_total_balance_cost_includes_interest', 'is_financed', 'override_finance_calculation'])) {
                    $this->{$object->name} = floatval($data[$object->name]);
                } else {
                    $this->{$object->name} = $data[$object->name];
                }
            }
        }
    }

    private function primarySubTotal(): float
    {
        $subtotal = $this->purchase_price + $this->fees_total + $this->accessories_total + $this->warranty_total_amount;
        $subtotal -= $this->discount;
        return $subtotal;
    }

    private function secondarySubTotal(): float
    {
        // let secondarySubTotal =
        // net_difference +
        // hst_on_net_difference +
        // licence_fee +
        // gasoline +
        // lien_payout;
        $secondary_subtotal = $this->net_difference + $this->total_taxes + $this->licence_fee + $this->fuel_fee + $this->lien_payout;
        return $secondary_subtotal;
    }

    private function calculateTradeInEquity(): float
    {
        return $this->trade_value - $this->lien_payout;
    }

    private function getTaxOnVehicleAdjustedPurchasePrice(float $selling_price, float $discount =  0, float $trade_value)
    {

        $adjusted_selling_price = $selling_price - $discount - $trade_value;

        $vehicle_regional_tax_types = $this->line_calculator->getLineDefaultTaxTypes();
        $vehicle_line_total = $this->line_calculator->calculate(
            $adjusted_selling_price,
            $discount,
            1,
            $vehicle_regional_tax_types['tax_type'],
            $vehicle_regional_tax_types['tax_type_secondary']
        );

        return $vehicle_line_total;
    }
}
