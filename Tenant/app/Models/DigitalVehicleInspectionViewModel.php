<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class DigitalVehicleInspectionViewModel extends Model
{
    protected $table      = 'digital_vehicle_inspections_view';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'header',
        'view',
        'query',
        'sort',
        'default',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'header'                          => [
            "label" => 'Title',
            "rules" => 'required|is_unique[digital_vehicle_inspections_view.header,id,{id}]'
        ],
    ];
}
