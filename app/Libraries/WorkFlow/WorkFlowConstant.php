<?php

namespace App\Libraries\WorkFlow;

/**
 * WorkFlowConstant  This class has the list of modules for WorkFlow
 */
abstract class WorkFlowConstant
{
    const INVENTORY = "INVENTORY";
    const LEAD = "LEAD";
    const CUSTOMER = "CUSTOMER";
    const DEAL = "DEAL";

    const PREFETCH = "PREFETCH";
    const INACTIVE = "INACTIVE";
    const DISABLED = "DISABLED";
    const ACTIVE = "ACTIVE";

    const MINUTES = "MINUTES";
    const HOURS = "HOURS";
    const DAYS = "DAYS";

    const UPDATE = "UPDATE";
    const CREATE = "CREATE";

    const SMS = "SMS";
    const EMAIL = "EMAIL";
    const ACTIVITY = "ACTIVITY";

    const WORKFLOW_TEMPLATE_DELETE = "WORKFLOW_TEMPLATE_DELETE";
}
