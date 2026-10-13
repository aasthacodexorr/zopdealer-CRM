<?php

namespace Tenant\app\Models;

use App\Models\SearchableModel;
use CodeIgniter\Model;

class DealBaseBreakDownModel extends Model
{
    protected $table      = 'deal_base_breakdown';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'deal_id', 'purchase_price', 'discount', 'subtotal', 'purchase_tax_type',
        'trade_value', 'net_difference', 'gasoline', 'secondary_subtotal',
        'purchase_tax_percentage', 'total_tax_amount', 'tax_on_purchase_price', 'licence_fee',
        'trade_equity', 'lien_payout', 'insurance_tax', 'total_balance_due', 'plate_type',
        'lien_registration_fee', 'created_at', 'updated_at', 'deleted_at',
        'purchase_tax_type_secondary', 'tax_on_purchase_price_secondary', 'purchase_tax_percentage_secondary',
        'tax_on_purchase_total'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Tenant\app\Entities\DealBaseBreakDown';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'deal_id' => [
            "label" => 'Deal Id',
            "rules" => 'required|is_unique[deal_base_breakdown.deal_id,id,{id}]'
        ],
        'purchase_price' => [
            "label" => 'Purchase Price',
            "rules" => 'permit_empty|decimal'
        ],
        'trade_equity' => [
            "label" => 'Trade Equity',
            "rules" => 'permit_empty|decimal'
        ],
        'trade_value' => [
            "label" => 'Trade Value',
            "rules" => 'permit_empty|decimal'
        ],
        'net_difference' => [
            "label" => 'Net Difference',
            "rules" => 'permit_empty|decimal'
        ],
        'discount' => [
            "label" => 'Discount',
            "rules" => 'permit_empty|decimal'
        ],
        'subtotal' => [
            "label" => 'Sub Total',
            "rules" => 'permit_empty|decimal'
        ],
        'gasoline' => [
            "label" => 'Gasoline',
            "rules" => 'permit_empty|decimal'
        ],
        'secondary_subtotal' => [
            "label" => 'Secondary Subtotal',
            "rules" => 'permit_empty|decimal'
        ],
        'purchase_tax_percentage' => [
            "label" => 'Purchase Tax Percentage',
            "rules" => 'permit_empty|decimal'
        ],
        'total_tax_amount' => [
            "label" => 'Total Tax Amount',
            "rules" => 'permit_empty|decimal'
        ],
        'tax_on_purchase_price' => [
            "label" => 'Tax on Purchase',
            "rules" => 'permit_empty|decimal'
        ],

        'licence_fee' => [
            "label" => 'Licence Fee',
            "rules" => 'permit_empty|decimal'
        ],
        'lien_payout' => [
            "label" => 'Lien Payout',
            "rules" => 'permit_empty|decimal'
        ],
        'lien_registration_fee' => [
            "label" => 'Lien Registration',
            "rules" => 'permit_empty|decimal'
        ],
        'insurance_tax' => [
            "label" => 'Insurance Tax',
            "rules" => 'permit_empty|decimal'
        ],

        'total_balance_due' => [
            "label" => 'Insurance Fee',
            "rules" => 'permit_empty|decimal'
        ],

        
        'tax_on_purchase_price_secondary' => [
            "label" => 'Secondary Tax Amount',
            "rules" => 'permit_empty|decimal'
        ],
        'purchase_tax_percentage_secondary' => [
            "label" => 'Secondary Tax Percentage',
            "rules" => 'permit_empty|decimal'
        ],
        'tax_on_purchase_total' => [
            "label" => 'Total Tax Amount',
            "rules" => 'permit_empty|decimal'
        ]
    ];
}
