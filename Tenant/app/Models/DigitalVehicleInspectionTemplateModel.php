<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class DigitalVehicleInspectionTemplateModel extends Model
{
    protected $table      = 'digital_vehicle_inspection_templates';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'title',
        'description',
        'require_odometer',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'title'                          => [
            "label" => 'Title',
            "rules" => 'required|min_length[2]|is_unique[digital_vehicle_inspection_templates.title,id,{id}]'
        ],
        'description'                          => [
            "label" => 'Description',
            "rules" => 'permit_empty'
        ],
        'require_odometer'                        => [
            "label" => 'Odometer Required',
            "rules" => 'permit_empty|is_natural'
        ],
    ];
}
