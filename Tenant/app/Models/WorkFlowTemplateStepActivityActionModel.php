<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class WorkFlowTemplateStepActivityActionModel extends Model
{
    protected $table      = 'workflow_template_step_activity_action';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'title', 'description', 'notify', 'notification_method', 'automate_assignment',
        'default_assignee',
        'priority', 'activity_type', 'activity_method', 'wft_step_id',
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'title'  => [
            "label" => 'Title',
            "rules" => 'required'
        ],
        'description'  => [
            "label" => 'Description',
            "rules" => 'permit_empty'
        ],
        'notify'  => [
            "label" => 'Notify',
            "rules" => 'permit_empty|is_natural'
        ],
        'automate_assignment'  => [
            "label" => 'Default Assignment',
            "rules" => 'permit_empty|is_natural'
        ],
        'default_assignee'  => [
            "label" => 'Modify Assignee',
            "rules" => 'required|is_natural'
        ],
        'priority'  => [
            "label" => 'Priority',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'activity_type'  => [
            "label" => 'Activity Type',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'activity_method'  => [
            "label" => 'Activity Method',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ]
    ];
}
