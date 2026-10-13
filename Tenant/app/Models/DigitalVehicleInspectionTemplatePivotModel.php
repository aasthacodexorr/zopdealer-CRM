<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class DigitalVehicleInspectionTemplatePivotModel extends Model
{
    protected $table      = 'dvi_template_item_pivot_table';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'dvi_template_id',
        'dvi_template_item_id',
        'sort_rank',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'dvi_template_id'                          => [
            "label" => 'DVI Template ID',
            "rules" => 'required'
        ],
        'dvi_template_item_id'                          => [
            "label" => 'DVI Template Item ID',
            "rules" => 'required'
        ],
    ];
}
