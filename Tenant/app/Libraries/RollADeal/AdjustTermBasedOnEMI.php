<?php

namespace Tenant\app\Libraries\RollADeal;

use Exception;
use Tenant\App\Entities\DealWorksheet;

class AdjustTermBasedOnEMI implements AdjustorInterface
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
        } else if ($payment_frequency == "Bi-Weekly") {
            $interest_rate  /=  2600;
        } else {
            $interest_rate  /=  1200;
        }

        //Calculations based on https://www.calculatorsoup.com/calculators/financial/loan-calculator.php

        //Complete formula      log( (adjust_emi_amount / interest_rate) / (adjust_emi_amount / interest_rate) - Finance ) / log(1 + interest_rate) 

        //PMT / ir

        $partial_equation = $adjust_amount / $interest_rate;

        $numerator_equation = log($partial_equation / ($partial_equation - $finance_amount));

        $denominator_equation = log(1 + $interest_rate);

        $new_term = $numerator_equation / $denominator_equation;

        //This will show the original term selected as payment frequency. 
        //Need to convert it back to months
        if ($payment_frequency == "Weekly") {
            $new_term *= 12;
            $new_term /= 52;
        } else if ($payment_frequency == "Bi-Weekly") {
            $new_term *= 12;
            $new_term /= 26;
        }

        return $new_term;
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
