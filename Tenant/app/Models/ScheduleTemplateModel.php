<?php

namespace Tenant\app\Models;

use App\Models\ExactSearchableModel;

class ScheduleTemplateModel extends ExactSearchableModel
{
    protected $table      = 'schedule_templates';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'datetime', 'frequency', 'format', 'enable', 'primary_recipients', 'timezone',
        'additional_recipients', 'module', 'module_id', 'created_at',
        'updated_at', 'deleted_at', 'search_keyword', 'title'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Tenant\app\Entities\ScheduleTemplate';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'datetime'  => [
            "label" => 'Date Time',
            "rules" => 'required|valid_date'
        ],
        'frequency' => [
            "label" => 'Frequency',
            "rules" => 'required'
        ],
        'format' => [
            "label" => 'Format',
            "rules" => 'required'
        ],
        'module' => [
            "label" => 'Module',
            "rules" => 'required'
        ],
        'module_id' => [
            "label" => 'Module ID',
            "rules" => 'required'
        ],
        'timezone' => [
            "label" => 'Timezone',
            "rules" => 'required'
        ],
        'enable' => [
            "label" => 'Active Button',
            "rules" => 'permit_empty|is_natural'
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
