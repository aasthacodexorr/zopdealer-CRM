<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class InsuranceSettingModel extends Model
{
    protected $table      = 'insurance_settings';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'label', 'type', 'amount', 'deductible', 'duration',
        'description', 'tax_type', 'tax_percentage', 'tax_amount',
        'created_at', 'updated_at', 'deleted_at',
        'tax_type_secondary', 'tax_amount_secondary', 'tax_percentage_secondary',
        'tax_amount_total',
        'cost', 'cost_tax_type', 'cost_tax_percentage', 'cost_tax_amount',
        'cost_tax_type_secondary', 'cost_tax_amount_secondary', 'cost_tax_percentage_secondary',
        'cost_tax_amount_total', 'is_mandatory', 'is_variable','only_finance'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Tenant\app\Entities\DealInsurance';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'label'  => [
            "label" => 'Label',
            "rules" => 'required|alpha_numeric_punct|is_unique[insurance_settings.label,id,{id}]'
        ],
        'amount' => [
            "label" => 'Amount',
            "rules" => 'permit_empty|decimal'
        ],
        'deductible' => [
            "label" => 'Deductible',
            "rules" => 'permit_empty|decimal'
        ],
        'duration'                        => [
            "label" => 'Duration',
            "rules" => 'permit_empty|is_natural'
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
        'is_mandatory'  => [
            "label" => 'Mandatory',
            "rules" => 'permit_empty|is_natural'
        ],
        'is_variable'  => [
            "label" => 'Variable',
            "rules" => 'permit_empty|is_natural'
        ],
        'only_finance'  => [
            "label" => 'Only Finance',
            "rules" => 'permit_empty|is_natural'
        ]
    ];
}
