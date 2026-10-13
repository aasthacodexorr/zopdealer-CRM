<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class CostVariationMatrixModel extends Model
{
    protected $table      = 'cost_variation_matrix';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'amount', 'tax_type', 'tax_percentage', 'tax_amount', 'entity', 'entity_id',
        'conditions', 'created_at', 'updated_at', 'deleted_at',
        'tax_type_secondary', 'tax_amount_secondary', 'tax_percentage_secondary',
        'tax_amount_total', 'cost', 'cost_tax_type', 'cost_tax_percentage', 'cost_tax_amount',
        'cost_tax_type_secondary', 'cost_tax_amount_secondary', 'cost_tax_percentage_secondary',
        'cost_tax_amount_total'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'conditions' => [
            "label" => 'Conditions',
            "rules" => 'required'
        ],
        'amount' => [
            "label" => 'Amount',
            "rules" => 'permit_empty|decimal'
        ],
        'description'  => [
            "label" => 'Name',
            "rules" => 'permit_empty|alpha_numeric_punct'
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
