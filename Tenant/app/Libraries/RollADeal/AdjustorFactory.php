<?php

namespace Tenant\app\Libraries\RollADeal;

/**
 * AdjustorFactory This should be factory to create instances of AdjustorInterface
 */
class AdjustorFactory
{
    /**
     * getInstance
     *
     
     * @param  String $request_column
     * @param  String $adjust_column
     * @return AdjustorInterface
     */
    public static function getInstance(String $request_column, String $adjust_column): ?AdjustorInterface
    {
        try {
            if ($request_column === "emi_amount" && $adjust_column === "purchase_price") {
                return new AdjustSellingPriceBasedOnEMI();
            } else if ($request_column === "total_balance_due" && $adjust_column === "purchase_price") {
                return new AdjustSellingPriceBasedOnTotalBalanceDue();
            } else if ($request_column === "emi_amount" && $adjust_column === "term") {
                return new  AdjustTermBasedOnEMI();
            } else {
                throw new \Exception("Couldn't find the requested adjustor.", 404);
            }
        } catch (\Exception $ex) {
            return ['errors' => $ex->getMessage()];
        }
    }
}
