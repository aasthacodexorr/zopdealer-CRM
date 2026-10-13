<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class WorkFlowTemplateStepEmailActionModel extends Model
{
    protected $table      = 'workflow_template_step_email_action';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'subject', 'content', 'wft_step_id',
        'override_custom_template', 'custom_template',
        'primary_recipients', 'additional_recipients',
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'subject'  => [
            "label" => 'Subject',
            "rules" => 'required'
        ],
        'content'  => [
            "label" => 'Description',
            "rules" => 'permit_empty'
        ],
        'override_custom_template' => [
            "label" => 'Override Custom Template Button',
            "rules" => 'permit_empty|is_natural'
        ],
        'custom_template'                        => [
            "label" => 'Custom Template',
            "rules" => 'required_with_a_toggle[override_custom_template]'
        ],
        'additional_recipients' => [
            "label" => 'Additional Recipients',
            "rules" => 'validate_email_json_array'
        ],
        'primary_recipients' => [
            "label" => 'Primary Recipients',
            "rules" => 'json_required_without[additional_recipients]'
        ],
    ];
}
