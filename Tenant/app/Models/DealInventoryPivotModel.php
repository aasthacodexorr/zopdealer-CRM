<?php

namespace Tenant\app\Models;

use App\Models\SearchableModel;
use CodeIgniter\Model;

class DealInventoryPivotModel extends Model
{
    protected $table      = 'deal_inventory_pivot_table';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'deal_id', 'stock_no', 'odometer',
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $validationRules    = [
        'deal_id'  => [
            "label" => 'Deal Id',
            "rules" => 'is_natural'
        ],
        'stock_no'  => [
            "label" => 'Stock Number',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'odometer'  => [
            "label" => 'Odometer',
            "rules" => 'permit_empty|is_natural'
        ],
    ];

}