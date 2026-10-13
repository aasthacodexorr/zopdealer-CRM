<?php

namespace Tenant\App\Entities;

use CodeIgniter\Entity;

class DealWorksheet extends Entity
{
    protected $casts = [
        'emi_amount' => '?float',
        'finance_amount' => '?float',
        'override_finance_calculation' => '?integer',
        'term' => '?integer',
        'payment_frequency' => '?string',
        'deal_type' => '?string',
        'adjust_amount' => 'float',
        'total_balance_due' => '?float',
        'adjust_column' => '?string',
        'trade_value' => '?float',
        'purchase_price' => '?float',
        'purchase_tax_percentage' => '?float',
        'purchase_tax_percentage_secondary' => '?float',
        'total_balance_due' => '?float'

    ];
}
