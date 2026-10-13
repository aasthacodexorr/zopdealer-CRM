<?php

namespace Tenant\app\Models;

use App\Models\SearchableModel;
use CodeIgniter\Model;

class CostSettingModel extends SearchableModel
{
    protected $table      = 'cost_settings';
    protected $primaryKey = 'id';

    protected $searchableColumns = [
        'name',
        'vendor_name',
        'price'
    ];

    protected $allowedFields = [
        'name',
        'vendor_name',
        'price',
        'quantity',
        'discount',
        'tax_type',
        'tax_percentage',
        'tax_amount',
        'sub_total',
        'created_at',
        'updated_at',
        'deleted_at',
        'phonetic_string',
        'tax_type_secondary',
        'tax_amount_secondary',
        'tax_percentage_secondary',
        'tax_amount_total',
        'include_cost_by_default'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Tenant\app\Entities\CostSetting';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'name'  => [
            "label" => 'Name',
            "rules" => 'required|is_unique[cost_settings.name,id,{id}]'
        ],
        'price' => [
            "label" => 'Price',
            "rules" => 'permit_empty|decimal'
        ],
        'quantity'  => [
            "label" => 'Quantity',
            "rules" => 'permit_empty|is_natural'
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
        'include_cost_by_default'  => [
            "label" => 'Include Cost By Default',
            "rules" => 'permit_empty|is_natural'
        ],

    ];
}
