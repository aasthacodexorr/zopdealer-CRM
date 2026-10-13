<?php

namespace Tenant\app\Models;

use App\Models\ExactSearchableModel;
use CodeIgniter\Model;

class WorkFlowTemplateModel extends ExactSearchableModel
{
    protected $table      = 'workflow_templates';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'title', 'description', 'module', 'trigger', 'condition', 'enable',
        'created_at', 'updated_at', 'deleted_at', 'search_keyword'
    ];

    protected $searchableColumns = [
        'title'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'title'                          => [
            "label" => 'Title',
            "rules" => 'required|alpha_numeric_space|min_length[2]|is_unique[workflow_templates.title,id,{id}]'
        ],
        'module'                          => [
            "label" => 'Module',
            "rules" => 'required|alpha_numeric_space'
        ],
        'trigger'                          => [
            "label" => 'Trigger',
            "rules" => 'permit_empty'
        ],
        'enable'                        => [
            "label" => 'Active Button',
            "rules" => 'permit_empty|is_natural'
        ],
    ];
}
