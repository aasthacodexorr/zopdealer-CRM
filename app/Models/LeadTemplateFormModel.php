<?php

namespace App\Models;

use CodeIgniter\Model;

class LeadTemplateFormModel extends Model
{
    protected $table      = 'lead_template_forms';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'description', 'type', 'slug', 'recipient_email', 'custom_css', 'custom_less',
        'auto_reply', 'auto_reply_template', 'admin_reply_template', 'override_auto_reply_content', 'auto_reply_content',
        'source', 'disclosure', 'default_terms_and_condition', 'VOI',
        'override_custom_template', 'custom_template', 'recipient_phone', 'recipient_adf_email',
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'App\Entities\LeadTemplateForm';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'name'                          => [
            "label" => 'Name',
            "rules" => 'required|alpha_numeric_space|min_length[2]|is_unique[lead_template_forms.name,id,{id}]'
        ],
        'slug'                          => [
            "label" => 'Slug',
            "rules" => 'required|alpha_dash|min_length[2]|is_unique[lead_template_forms.slug,id,{id}]'
        ],
        'source'                          => [
            "label" => 'Source',
            "rules" => 'required'
        ],
        'admin_reply_template'                          => [
            "label" => 'Admin Email Template',
            "rules" => 'required'
        ],
    ];
}