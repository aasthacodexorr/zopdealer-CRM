<?php

namespace Tenant\app\Libraries\RollADeal;

use Exception;
use Tenant\App\Entities\DealWorksheet;

class AdjustSellingPriceBasedOnEMI implements AdjustorInterface
{
    public function adjust(DealWorksheet $worksheet): float
    {
        if (!$this->checkConditions($worksheet->toArray())) {
            throw new \Exception("Sorry we weren't able to roll a deal due to conditions not meet.", 400);
        }

        [
            'payment_frequency' => $payment_frequency,
            'interest_rate' => $interest_rate,
            'term' => $term,
            'emi_amount' => $emi_amount,
            'adjust_amount' => $adjust_amount,
            'trade_value' => $trade_value,
            'purchase_tax_percentage' => $purchase_tax_percentage,
            'purchase_tax_percentage_secondary' => $purchase_tax_percentage_secondary,
            'purchase_price' => $purchase_price,
            'finance_amount' => $finance_amount


        ] = $worksheet->toArray();

        if ($payment_frequency == "Weekly") {
            $interest_rate  /=  5200;
            $term /= 12;
            $term *= 52;
        } else if ($payment_frequency == "Bi-Weekly") {
            $interest_rate  /=  2600;
            $term /= 12;
            $term *= 26;
        } else {
            $interest_rate  /=  1200;
        }

        //Present value interest factor
        $PVIFA = ($interest_rate + $interest_rate / (pow(1 + $interest_rate, $term) - 1));

        $new_finance_amount = $adjust_amount / $PVIFA;

        if (!isset($trade_value)) {
            $trade_value = 0;
        }

        if (!isset($purchase_tax_percentage_secondary)) {
            $purchase_tax_percentage_secondary = 0;
        }

        $tax_percentage = (($purchase_tax_percentage_secondary + $purchase_tax_percentage) * 0.01);

        // (Purchase - Trade) * Tax + Purchase + Constant = Financed Value

        $constant_difference = $finance_amount -
            (($purchase_price - $trade_value) * $tax_percentage + $purchase_price);

        $new_selling_price = ($new_finance_amount - $constant_difference + ($tax_percentage * $trade_value)) / ($tax_percentage + 1);

        return $new_selling_price;
    }

    private function checkConditions($data): bool
    {
        //Check if data has deal_type Financed
        if ($data['deal_type'] !== 'Financed') {
            return false;
        }

        //Check override finance
        if (!empty($data['override_finance_calculation'])) {
            return false;
        }

        //Check if values are available 
        $attributes = ['emi_amount', 'finance_amount', 'term', 'payment_frequency', 'interest_rate'];
        foreach ($attributes as $attribute) {
            if (empty($data[$attribute])) {
                return false;
            }
        }
        return true;
    }
}
