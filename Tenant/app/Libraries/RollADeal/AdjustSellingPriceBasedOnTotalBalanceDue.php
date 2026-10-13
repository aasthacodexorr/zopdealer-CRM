<?php

namespace Tenant\app\Libraries\RollADeal;

use Tenant\App\Entities\DealWorksheet;

class AdjustSellingPriceBasedOnTotalBalanceDue implements AdjustorInterface
{
    public function adjust(DealWorksheet $worksheet): float
    {
        if (!$this->checkConditions($worksheet->toArray())) {
            throw new \Exception("Sorry we weren't able to roll a deal due to conditions not meet.", 400);
        }

        [
            'trade_value' => $trade_value,
            'purchase_tax_percentage' => $purchase_tax_percentage,
            'purchase_tax_percentage_secondary' => $purchase_tax_percentage_secondary,
            'purchase_price' => $purchase_price,
            'total_balance_due' => $total_balance_due,
            'adjust_amount' => $adjust_amount


        ] = $worksheet->toArray();


        if (!isset($trade_value)) {
            $trade_value = 0;
        }

        if (!isset($purchase_tax_percentage_secondary)) {
            $purchase_tax_percentage_secondary = 0;
        }

        $tax_percentage = (($purchase_tax_percentage_secondary + $purchase_tax_percentage) * 0.01);

        $constant_difference = $total_balance_due -
            (($purchase_price - $trade_value) * $tax_percentage + $purchase_price);

        $new_selling_price = ($adjust_amount - $constant_difference + ($tax_percentage * $trade_value)) / ($tax_percentage + 1);


        return $new_selling_price;
    }

    private function checkConditions($data): bool
    {
        //Check if data has deal_type Financed
        if ($data['deal_type'] !== 'Cash') {
            return false;
        }

        //Check if values are available 
        $attributes = ['total_balance_due', 'purchase_price'];
        foreach ($attributes as $attribute) {
            if (empty($data[$attribute])) {
                return false;
            }
        }
        return true;
    }
}
