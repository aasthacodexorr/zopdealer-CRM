<?php

namespace Tenant\app\Libraries\PdfFiller\Version1;

use App\Helpers\Utility;
use Carbon\Carbon;
use Tenant\app\Libraries\PdfFiller\PDFFillerAbstract;
use mikehaertl\pdftk\Pdf;
use Tenant\app\Libraries\PdfFiller\PDFFillerInterface;

class DealRecap extends BillOfSaleRetail implements PDFFillerInterface
{
    public function dataMap(array $data)
    {
        parent::dataMap($data);

        $deal_total_amount = 0;
        $deal_total_cost = 0;

        $total_fees = 0;
        $total_fees_cost = 0;
        if (!empty($data['fees'])) {

            foreach ($data['fees'] as $fee) {
                if (is_array($fee)) {
                    $total_fees += (float) $fee['amount'];
                    $total_fees_cost += (float) $fee['cost'];
                }
            }
        }

        $this->data['aggregate_net_fees'] = $total_fees;
        $this->data['aggregate_net_fees_cost'] = $total_fees_cost;

        $deal_total_amount += $total_fees;
        $deal_total_cost   += $total_fees_cost;

        $total_accessories = 0;
        $total_accessories_cost = 0;
        if (!empty($data['accessories'])) {
            foreach ($data['accessories'] as $accessories) {
                if (is_array($accessories)) {
                    $total_accessories += (float) $accessories['amount'];
                    $total_accessories_cost += (float) $accessories['cost'];
                }
            }
        }

        $this->data['aggregate_net_accessories'] = $total_accessories;
        $this->data['aggregate_net_accessories_cost'] = $total_accessories_cost;

        $deal_total_amount += $total_accessories;
        $deal_total_cost   += $total_accessories_cost;

        $total_guarantee = 0;
        $total_guarantee_cost = 0;
        if (!empty($data['guarantee'])) {
            foreach ($data['guarantee'] as $guarantee) {
                if (is_array($guarantee)) {
                    $total_guarantee += (float) $guarantee['amount'];
                    $total_guarantee_cost += (float) $guarantee['cost'];
                }
            }
        }

        $this->data['aggregate_net_guarantee'] = $total_guarantee;
        $this->data['aggregate_net_guarantee_cost'] = $total_guarantee_cost;

        $deal_total_amount += $total_guarantee;
        $deal_total_cost   += $total_guarantee_cost;

        $total_warranties = 0;
        $total_warranties_cost = 0;
        if (!empty($data['warranties'])) {
            foreach ($data['warranties'] as $warranty) {
                if (is_array($warranty)) {
                    $total_warranties += (float) $warranty['amount'];
                    $total_warranties_cost += (float) $warranty['cost'];
                }
            }
        }

        $this->data['aggregate_net_warranties'] = $total_warranties + $this->data['aggregate_net_guarantee'];
        $this->data['aggregate_net_warranties_cost'] = $total_warranties_cost + $this->data['aggregate_net_guarantee_cost'];

        $deal_total_amount += $total_warranties;
        $deal_total_cost   += $total_warranties_cost;

        $total_insurance = 0;
        $total_insurance_cost = 0;
        if (!empty($data['insurances'])) {
            if (is_array($data['insurances'])) {
                foreach ($data['insurances'] as $insurance) {
                    $total_insurance += (float) $insurance['amount'];
                    $total_insurance_cost += (float) $insurance['cost'];
                }
            }
        }

        $this->data['aggregate_net_insurance'] = $total_insurance;
        $this->data['aggregate_net_insurance_cost'] = $total_insurance_cost;


        $deal_total_amount += $total_insurance;
        $deal_total_cost   += $total_insurance_cost;

        $deal_selling_price = 0;
        if (!empty($data['dealbreakdown'])) {
            if (is_array($data['dealbreakdown'])) {
                foreach ($data['dealbreakdown'] as $dealbreakdown) {
                    $selling_price = is_numeric($dealbreakdown['purchase_price']) &&
                        !empty($dealbreakdown['purchase_price']) ? (float) $dealbreakdown['purchase_price'] : 0;

                    $discount = is_numeric($dealbreakdown['discount']) &&
                        !empty($dealbreakdown['discount']) ? (float) $dealbreakdown['discount'] : 0;

                    $deal_selling_price = $selling_price - $discount;
                }
            }
        }

        $this->data['deal_selling_price'] = $deal_selling_price;

        $deal_total_amount += $deal_selling_price;

        $finance_commission = 0;
        if (!empty($data['finance'])) {
            foreach ($data['finance'] as $finance) {
                if (is_array($finance) && ($data['type'] == "Financed")) {
                    $finance_commission = (float) $finance['finance_commission'];
                }
            }
        }

        $this->data['finance_commission'] = $finance_commission;

        $deal_total_amount += $finance_commission;

        $vehicle_purchase_price = 0;
        if (!empty($data['purchase'])) {
            $vehicle_purchase_price = (float) $data['purchase']['price'] - (float) $data['purchase']['discount'];
        }

        $this->data['vehicle_purchase_price'] = $vehicle_purchase_price;

        $deal_total_cost += $vehicle_purchase_price;

        $vehicle_expenses = 0;
        if (!empty($data['expenses'])) {
            foreach ($data['expenses'] as $expense) {
                if (is_array($expense)) {
                    $vehicle_expenses += ((float) $expense['price'] * (float) $expense['quantity']) - $expense['discount'];
                }
            }
        }

        $this->data['vehicle_expenses'] = $vehicle_expenses;
        $deal_total_cost += $vehicle_expenses;

        $sales_commission_cost = 0;

        if (!empty($data['sale_commissions'])) {
            if (is_array($data['sale_commissions'])) {
                foreach ($data['sale_commissions'] as $sale_commission) {
                    $sales_commission_cost += (float) $sale_commission['paid_amount'];
                }
            }
        }

        $this->data['sales_commission_cost'] = $sales_commission_cost;

        $deal_total_cost += $sales_commission_cost;

        $this->data['deal_total_amount'] = $deal_total_amount;
        $this->data['deal_total_cost'] = $deal_total_cost;

        $this->data['deal_total_amount_1'] = $deal_total_amount;
        $this->data['deal_total_cost_1'] = $deal_total_cost;

        $this->data['deal_profit_cost'] = $deal_total_amount - $deal_total_cost;

        $this->data['vehicle_profit'] = $deal_selling_price - $vehicle_expenses - $vehicle_purchase_price;

        $this->data['deal_profit_cost_exclude_sale_commission'] = $deal_total_amount - $deal_total_cost + $sales_commission_cost;

        $backend_income = $total_guarantee + $total_warranties + $total_insurance;

        $additional_income = $total_fees + $total_accessories + $backend_income;

        $backend_cost = $total_guarantee_cost + $total_warranties_cost + $total_insurance_cost;

        $additional_cost = $total_fees_cost + $total_accessories_cost + $backend_cost;

        $this->data['additional_income'] = $additional_income;

        $this->data['additional_cost'] = $additional_cost;

        $this->data['additional_income_difference'] = $additional_income - $additional_cost;

        $this->data['backend_income_difference'] = $backend_income - $backend_cost;

        return $this;
    }
}
