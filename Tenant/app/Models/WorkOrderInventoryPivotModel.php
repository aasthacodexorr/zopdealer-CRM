<?php

namespace Tenant\app\Models;

use App\Models\SearchableModel;
use CodeIgniter\Model;

class WorkOrderInventoryPivotModel extends Model
{
    protected $table      = 'work_order_inventory_pivot_table';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'work_order_id', 'stock_no'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = false;

    protected $validationRules    = [
        'work_order_id'  => [
            "label" => 'Work Order Id',
            "rules" => 'is_natural'
        ],
        'stock_no'  => [
            "label" => 'Stock Number',
            "rules" => 'required'
        ],
    ];
}
