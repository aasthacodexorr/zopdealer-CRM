<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class InventoryDigitalRetailingModel extends Model
{
    protected $table      = 'inventory_digital_retailing';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'inventory_id', 'override_default_finance_terms', 'finance_terms',
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'inventory_id' => [
            "label" => 'Inventory Id',
            "rules" => 'required'
        ],
        'override_default_finance_terms'  => [
            "label" => 'Override Default Finance Terms',
            "rules" => 'permit_empty|is_natural'
        ]
    ];
}
