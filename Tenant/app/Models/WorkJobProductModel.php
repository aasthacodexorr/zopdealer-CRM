<?php

namespace Tenant\app\Models;

use App\Models\ExactSearchableModel;
use CodeIgniter\Model;

class WorkJobProductModel extends Model
{
    protected $table      = 'work_job_products';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name', 'item_code', 'description', 'category', 'type',
        'vendor_code', 'vendor_name', 'unit',

        'discount', 'quantity', 'price', 'tax_type', 'tax_percentage', 'tax_amount', 'tax_type_secondary',
        'tax_percentage_secondary', 'tax_amount_secondary', 'tax_amount_total', 
        
        'cost', 'cost_tax_type', 'cost_tax_percentage', 'cost_tax_amount', 'cost_tax_type_secondary', 'cost_tax_amount_secondary', 'cost_tax_percentage_secondary',
        'cost_tax_amount_total', 'additional_information', 'job_card_information', 'warranty', 'phonetic_string',

        'created_at', 'updated_at', 'deleted_at', 'work_job_id',
        'sub_total','cost_sub_total', 'sort_rank'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'name'  => [
            "label" => 'Name',
            "rules" => 'required'
        ],
        'work_job_id'  => [
            "label" => 'Work Job Id',
            "rules" => 'required'
        ],
        'description'  => [
            "label" => 'Name',
            "rules" => 'permit_empty'
        ],
        'type'  => [
            "label" => 'Type',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],



        'price' => [
            "label" => 'Amount',
            "rules" => 'permit_empty|decimal'
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
        ],
        'cost' => [
            "label" => 'Cost',
            "rules" => 'permit_empty|decimal'
        ],
        'sub_total' => [
            "label" => 'Sub Total',
            "rules" => 'permit_empty|decimal'
        ],
        'cost_sub_total' => [
            "label" => 'Cost Sub Total',
            "rules" => 'permit_empty|decimal'
        ]
    ];
}
