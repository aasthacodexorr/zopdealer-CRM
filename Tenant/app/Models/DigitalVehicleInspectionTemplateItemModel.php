<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class DigitalVehicleInspectionTemplateItemModel extends Model
{
    protected $table      = 'digital_vehicle_inspection_template_items';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'title',
        'description',
        'type',
        'enable_comment',
        'enable_attachments',
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
            "rules" => 'required|min_length[2]|is_unique[digital_vehicle_inspection_template_items.title,id,{id}]'
        ],
        'description'                          => [
            "label" => 'Description',
            "rules" => 'permit_empty'
        ],
        'type'                          => [
            "label" => 'Type',
            "rules" => 'required'
        ],
        'enable_comment'                        => [
            "label" => 'Enable Comment',
            "rules" => 'permit_empty|is_natural'
        ],
        'enable_attachments'                        => [
            "label" => 'Enable Comment',
            "rules" => 'permit_empty|is_natural'
        ],
    ];
}
