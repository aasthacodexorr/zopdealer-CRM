<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class WorkFlowTemplateStepModel extends Model
{
    protected $table      = 'workflow_template_steps';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'index', 'wait_time', 'wait_time_unit', 'action',
        'workflow_template_id',
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'workflow_template_id'                        => [
            "label" => 'WorkFlow Template ID',
            "rules" => 'permit_empty|is_natural'
        ],
        'wait_time'                        => [
            "label" => 'Wait Time',
            "rules" => 'is_natural'
        ],
        'wait_time_unit'                          => [
            "label" => 'Wait Time Unit',
            "rules" => 'required|alpha_numeric_space'
        ],
        'action'                          => [
            "label" => 'Action',
            "rules" => 'required|alpha_numeric_space'
        ],
        'index'                        => [
            "label" => 'Step Index',
            "rules" => 'permit_empty|is_natural'
        ],
    ];
}
