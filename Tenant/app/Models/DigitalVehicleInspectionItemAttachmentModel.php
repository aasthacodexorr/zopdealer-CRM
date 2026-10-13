<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class DigitalVehicleInspectionItemAttachmentModel extends Model
{
    protected $table      = 'digital_vehicle_inspection_item_attachments';
    protected $allowedFields = [
        'url',
        'dvi_item_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'url'                        => [
            "label" => 'URL',
            "rules" => 'required'
        ],
        'dvi_item_id'                        => [
            "label" => 'DVI Item ID',
            "rules" => 'required'
        ],

    ];
}
