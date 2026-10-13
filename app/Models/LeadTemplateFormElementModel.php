<?php

namespace App\Models;
/**
 * LeadTemplateFormElementModel This model represents the schema of each of the element in Lead Form.
 */
class LeadTemplateFormElementModel extends BaseWithoutDBValidationModel
{
    protected $table      = 'lead_template_form_elements';
    protected $primaryKey = 'dom_element_id';
    protected $allowedFields = [
        'dom_label',
        'dom_placeholder',
        'dom_name',
        'dom_value',
        'dom_value_type',
        'dom_id',
        'dom_class',
        'dom_type',
        'dom_validation',
        'options',
        'dom_order',
        'lead_template_form_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $returnType    = 'App\Entities\LeadTemplateFormElement';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'dom_name'                          => [
            "label" => 'Name of Form Element',
            "rules" => 'required|alpha_dash|min_length[2]'
        ],
    ];
}
