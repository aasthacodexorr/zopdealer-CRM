<?php

namespace Tenant\app\Models;

use App\Models\SearchableModel;
use CodeIgniter\Model;

class InventoryWebInspectionModel extends Model
{
    protected $table      = 'inventory_vehicle_inspection';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'inventory_id','detail','created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'App\Entities\MasterEntity';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'detail'  => [
            "label" => 'Detail',
            "rules" => 'required'
        ],
        'inventory_id' => [
            "label" => 'Inventory Id',
            "rules" => 'required|alpha_numeric_punct'
        ]
    ];
}
