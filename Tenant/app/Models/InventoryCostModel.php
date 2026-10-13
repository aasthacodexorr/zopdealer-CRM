<?php

namespace Tenant\app\Models;

use App\Models\SearchableModel;

class InventoryCostModel extends SearchableModel
{
    protected $table      = 'inventory_costs';
    protected $primaryKey = 'id';

    protected $searchableColumns = [
        'name',
        'vendor_name',
        'price'
    ];

    protected $allowedFields = [
        'name',
        'description',
        'invoice',
        'vendor_name',
        'price',
        'quantity',
        'discount',
        'tax_type',
        'tax_percentage',
        'tax_amount',
        'sub_total',
        'purchase_date',
        'inventory_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'phonetic_string',
        'tax_type_secondary',
        'tax_amount_secondary',
        'tax_percentage_secondary',
        'tax_amount_total',
        'payment_reference'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Tenant\app\Entities\InventoryCost';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'name'  => [
            "label" => 'Name',
            "rules" => 'required'
        ],
        'description' => [
            "label" => 'Description',
            "rules" => 'permit_empty'
        ],
        'invoice' => [
            "label" => 'Invoice',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'payment_reference' => [
            "label" => 'Payment Reference',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'price' => [
            "label" => 'Price',
            "rules" => 'permit_empty|decimal'
        ],
        'quantity'  => [
            "label" => 'Quantity',
            "rules" => 'permit_empty|integer'
        ],
        'discount' => [
            "label" => 'Discount',
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
        'sub_total' => [
            "label" => 'Sub Total',
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


    ];
}
