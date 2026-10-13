<?php

namespace Tenant\app\Libraries\Report;

use App\Libraries\Schedule\ScheduleConstant;

final class ReportFactory
{
    public static function getInstance($module = ""): ReportInterface
    {
        try {
            switch ($module) {
                case ScheduleConstant::DEAL_REPORT:
                    return new DealReport();
                    break;

                case ScheduleConstant::INVENTORY_REPORT:
                    return new InventoryReport();
                    break;

                case ScheduleConstant::LEAD_REPORT:
                    return new LeadReport();
                    break;

                case ScheduleConstant::PAYMENT_REPORT:
                    return new PaymentReport();
                    break;

                case ScheduleConstant::INVENTORY_COST_REPORT:
                    return new InventoryCostReport();
                    break;

                case ScheduleConstant::INVENTORY_PURCHASE_REPORT:
                    return new InventoryPurchaseReport();
                    break;

                case ScheduleConstant::CUSTOMER_REPORT;
                    return new CustomerReport();
                    break;

                default:
                    throw new \Exception("Not a valid module for report.", 1);

                    break;
            }
        } catch (\Exception $ex) {
            log_message("notice", $ex->getMessage());
        }
    }
}
