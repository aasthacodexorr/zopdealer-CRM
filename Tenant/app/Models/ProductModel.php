<?php

namespace Tenant\app\Models;

use App\Models\ExactSearchableModel;

class ProductModel extends ExactSearchableModel
{
    protected $table      = 'products';
    protected $primaryKey = 'id';

    protected $searchableColumns = [
        'name', 'item_code', 'category', 'vendor_code', 'vendor_name'
    ];

    protected $allowedFields = [
        'name', 'item_code', 'description', 'category', 'type',
        'vendor_code', 'vendor_name', 'available_quantity', 'alert_on_threshold_quantity', 'auto_update_quantity',

        'price', 'tax_type', 'tax_percentage', 'tax_amount', 'tax_type_secondary',
        'tax_percentage_secondary', 'tax_amount_secondary', 'tax_amount_total', 'cost', 'cost_tax_type',

        'cost_tax_percentage', 'cost_tax_amount', 'cost_tax_type_secondary', 'cost_tax_amount_secondary', 'cost_tax_percentage_secondary',
        'cost_tax_amount_total', 'additional_information', 'job_card_information', 'warranty', 'phonetic_string',

        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'name'  => [
            "label" => 'Name',
            "rules" => 'required'
        ],
        'item_code'  => [
            "label" => 'Item Code',
            "rules" => 'required|is_unique[products.item_code,id,{id}]'
        ],
        'description'  => [
            "label" => 'Name',
            "rules" => 'permit_empty'
        ],
        'type'  => [
            "label" => 'Type',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],

        'available_quantity'                        => [
            "label" => 'Available Quantity',
            "rules" => 'permit_empty|is_natural'
        ],
        'alert_on_threshold_quantity'                        => [
            "label" => 'Alert Threshold Quantity',
            "rules" => 'permit_empty|is_natural'
        ],
        'auto_update_quantity'                        => [
            "label" => 'Auto Update Quantity',
            "rules" => 'permit_empty|is_natural'
        ],



        'price' => [
            "label" => 'Amount',
            "rules" => 'permit_empty|decimal'
        ],
        'tax_percentage' => [
            "label" => 'Tax Percentage',
            "rules" => 'permit_empty|decimal'
        ],
        'tax_amount' => [
            "label" => 'Tax Amount',
            "rules" => 'permit_empty|decimal'
        ],

        'tax_amount_secondary' => [
            "label" => 'Secondary Tax Amount',
            "rules" => 'permit_empty|decimal'
        ],
        'tax_percentage_secondary' => [
            "label" => 'Secondary Tax Percentage',
            "rules" => 'permit_empty|decimal'
        ],
        'tax_amount_total' => [
            "label" => 'Total Tax Amount',
            "rules" => 'permit_empty|decimal'
        ],

        'cost' => [
            "label" => 'Cost',
            "rules" => 'permit_empty|decimal'
        ],

        'cost_tax_percentage' => [
            "label" => 'Tax Percentage',
            "rules" => 'permit_empty|decimal'
        ],
        'cost_tax_amount' => [
            "label" => 'Tax Amount',
            "rules" => 'permit_empty|decimal'
        ],

        'cost_tax_amount_secondary' => [
            "label" => 'Secondary Tax Amount',
            "rules" => 'permit_empty|decimal'
        ],
        'cost_tax_percentage_secondary' => [
            "label" => 'Secondary Tax Percentage',
            "rules" => 'permit_empty|decimal'
        ],
        'cost_tax_amount_total' => [
            "label" => 'Total Tax Amount',
            "rules" => 'permit_empty|decimal'
        ]
    ];
}
