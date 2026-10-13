<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class InventoryPurchaseReportViewModel extends Model
{
    protected $table      = 'inventory_purchase_reports_view';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'header','view', 'query', 'sort', 'default', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'header'                          => [
            "label" => 'Title',
            "rules" => 'required|is_unique[inventory_purchase_reports_view.header,id,{id}]'
        ],
    ];

}