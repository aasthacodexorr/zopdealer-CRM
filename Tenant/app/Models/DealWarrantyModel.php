<?php

namespace Tenant\app\Models;

use App\Models\SearchableModel;
use CodeIgniter\Model;

class DealWarrantyModel extends Model
{
    protected $table      = 'deal_warranties';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'deal_id', 'company_name', 'duration', 'distance', 'description',
        'tax_type', 'tax_percentage', 'tax_amount',
         'amount',
        'created_at', 'updated_at', 'deleted_at',
        'tax_type_secondary', 'tax_amount_secondary', 'tax_percentage_secondary',
        'tax_amount_total','cost', 'cost_tax_type', 'cost_tax_percentage', 'cost_tax_amount',
         'cost_tax_type_secondary', 'cost_tax_amount_secondary', 'cost_tax_percentage_secondary',
         'cost_tax_amount_total'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Tenant\app\Entities\DealWarranty';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'deal_id' => [
            "label" => 'Deal Id',
            "rules" => 'required|is_unique[deal_warranties.deal_id,id,{id}]'
        ],
        'company_name'  => [
            "label" => 'Company Name',
            "rules" => 'permit_empty'
        ],
        'duration'                        => [
            "label" => 'Duration',
            "rules" => 'permit_empty|is_natural'
        ],
        'distance'                        => [
            "label" => 'Distance',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'description'  => [
            "label" => 'Description',
            "rules" => 'permit_empty'
        ],
        'tax_percentage' => [
            "label" => 'Tax Percentage',
            "rules" => 'permit_empty|decimal'
        ],
        'tax_amount' => [
            "label" => 'Tax Amount',
            "rules" => 'permit_empty|decimal'
        ],
        'amount' => [
            "label" => 'Amount',
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
