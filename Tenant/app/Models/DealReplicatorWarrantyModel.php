<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class DealReplicatorWarrantyModel extends Model
{
    protected $table      = 'deal_replicator_warranties';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'deal_replicator_id', 'company_name', 'duration', 'distance', 'description',
        'tax_type', 'tax_percentage', 'tax_amount',
        'amount', 'tax_type_secondary', 'tax_amount_secondary', 'tax_percentage_secondary',
        'tax_amount_total', 'created_at', 'updated_at', 'deleted_at',
        'cost', 'cost_tax_type', 'cost_tax_percentage', 'cost_tax_amount',
        'cost_tax_type_secondary', 'cost_tax_amount_secondary', 'cost_tax_percentage_secondary',
        'cost_tax_amount_total'

    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Tenant\app\Entities\DealWarranty';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'deal_replicator_id' => [
            "label" => 'Deal Model Id',
            "rules" => 'required|is_unique[deal_replicator_warranties.deal_replicator_id,id,{id}]'
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
            "rules" => 'required|decimal'
        ],
        'amount' => [
            "label" => 'Amount',
            "rules" => 'required|decimal'
        ],

        'tax_amount_secondary' => [
            "label" => 'Secondary Tax Amount',
            "rules" => 'required|decimal'
        ],
        'tax_percentage_secondary' => [
            "label" => 'Secondary Tax Percentage',
            "rules" => 'permit_empty|decimal'
        ],
        'tax_amount_total' => [
            "label" => 'Total Tax Amount',
            "rules" => 'required|decimal'
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
