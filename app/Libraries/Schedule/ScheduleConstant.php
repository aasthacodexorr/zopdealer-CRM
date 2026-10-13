<?php

namespace App\Libraries\Schedule;

/**
 * ScheduleConstant  This class has the list of modules for Schedule
 */
abstract class ScheduleConstant
{
    const INVENTORY_REPORT = "INVENTORY_REPORT";
    const DEAL_REPORT = "DEAL_REPORT";
    const LEAD_REPORT = "LEAD_REPORT";
    const PAYMENT_REPORT = "PAYMENT_REPORT";
    const INVENTORY_COST_REPORT = "INVENTORY_COST_REPORT";
    const INVENTORY_PURCHASE_REPORT = "INVENTORY_PURCHASE_REPORT";
    const CUSTOMER_REPORT = "CUSTOMER_REPORT";

    const INACTIVE = "INACTIVE";
    const DISABLED = "DISABLED";
    const ACTIVE = "ACTIVE";

    const DAILY = "Daily";
    const WEEKLY = "Weekly";
    const MONTHLY = "Monthly";
    const QUARTERLY = "Quarterly";
    const YEARLY = "Yearly";
}
