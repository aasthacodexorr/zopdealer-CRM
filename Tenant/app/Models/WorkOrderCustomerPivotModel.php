<?php

namespace Tenant\app\Models;

use App\Models\SearchableModel;
use CodeIgniter\Model;

class WorkOrderCustomerPivotModel extends Model
{
    protected $table      = 'work_order_customer_pivot_table';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'work_order_id', 'customer_id'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = false;

    protected $validationRules    = [
        'work_order_id'  => [
            "label" => 'Work Order Id',
            "rules" => 'is_natural'
        ],
        'customer_id'  => [
            "label" => 'Customer Id',
            "rules" => 'is_natural'
        ],
    ];
}
