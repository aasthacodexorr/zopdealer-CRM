<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class DigitalVehicleInspectionItemModel extends Model
{
    protected $table      = 'digital_vehicle_inspection_items';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'title',
        'description',
        'type',
        'enable_comment',
        'enable_attachments',
        'comment',
        'status',
        'reason',
        'has_response',
        'render_only',
        'dvi_id',
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
            "rules" => 'required'
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
        'has_response'                        => [
            "label" => 'Has Response',
            "rules" => 'permit_empty|is_natural'
        ],
        'render_only'                        => [
            "label" => 'Response Only',
            "rules" => 'permit_empty|is_natural'
        ],
        'dvi_id'                          => [
            "label" => 'DVI Template Item ID',
            "rules" => 'required'
        ],
    ];
}
