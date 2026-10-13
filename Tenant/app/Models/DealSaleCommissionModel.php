<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class DealSaleCommissionModel extends Model
{
    protected $table      = 'deal_sale_commissions';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'deal_id', 'role', 'user', 'type',
        'percentage', 'calculated_amount', 'paid_amount', 'paid_date',
        'created_at', 'updated_at', 'deleted_at',
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Tenant\app\Entities\DealSaleCommission';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'deal_id' => [
            "label" => 'Deal Id',
            "rules" => 'required'
        ],
        'role'  => [
            "label" => 'Role',
            "rules" => 'permit_empty'
        ],
        'user'  => [
            "label" => 'User',
            "rules" => 'required|alpha_numeric_punct'
        ],
        'type'  => [
            "label" => 'Type',
            "rules" => 'required'
        ],
        'percentage' => [
            "label" => 'Percentage',
            "rules" => 'permit_empty|decimal'
        ],
        'calculated_amount' => [
            "label" => 'Calculated Amount',
            "rules" => 'permit_empty|decimal'
        ],
        'paid_amount' => [
            "label" => 'Paid Amount',
            "rules" => 'required|decimal'
        ],
    ];
}
