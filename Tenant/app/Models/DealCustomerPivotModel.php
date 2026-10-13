<?php

namespace Tenant\app\Models;

use App\Models\SearchableModel;
use CodeIgniter\Model;

class DealCustomerPivotModel extends Model
{
    protected $table      = 'deal_customer_pivot_table';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'deal_id', 'customer_id', 'customer_type',
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $validationRules    = [
        'deal_id'  => [
            "label" => 'Deal Id',
            "rules" => 'is_natural'
        ],
        'customer_id'  => [
            "label" => 'Customer Id',
            "rules" => 'is_natural'
        ],
        'customer_type'  => [
            "label" => 'Customer Type',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
    ];

}