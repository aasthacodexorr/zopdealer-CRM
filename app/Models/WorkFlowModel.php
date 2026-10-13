<?php

namespace App\Models;

use CodeIgniter\Model;

class WorkFlowModel extends Model
{
    protected $table      = 'workflow';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'module', 'module_id', 'context', 'workflow_template_id',
        'database', 'current_step', 'schedule', 'status',
        'created_at', 'updated_at', 'deleted_at', 'search_keyword' , 'unique_index'
    ];

    protected $useSoftDeletes = false;
    protected $useTimestamps = true;
    protected $returnType    = 'CodeIgniter\Entity';

    protected $validationRules    = [
        'module'  => [
            "label" => 'Module',
            "rules" => 'required|alpha_numeric_punct'
        ],
        'module_id'  => [
            "label" => 'Module Id',
            "rules" => 'permit_empty|is_natural'
        ],
        'workflow_template_id'                        => [
            "label" => 'WorkFlow Template ID',
            "rules" => 'permit_empty|is_natural'
        ],
        'current_step'  => [
            "label" => 'Current Step',
            "rules" => 'permit_empty|is_natural'
        ],
        'database'  => [
            "label" => 'Database',
            "rules" => 'required'
        ],
        'schedule'  => [
            "label" => 'Schedule',
            "rules" => 'permit_empty|valid_date'
        ],
        'unique_index'                        => [
            "label" => 'Work Flow Hash',
            "rules" => 'is_unique[workflow.unique_index,id,{id}]'
        ]
    ];
}
