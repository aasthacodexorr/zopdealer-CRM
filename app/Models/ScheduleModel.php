<?php

namespace App\Models;

use CodeIgniter\Model;

class ScheduleModel extends Model
{
    protected $table      = 'schedule';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'module', 'module_id', 'context', 'schedule_template_id',
        'database', 'schedule', 'status', 'frequency', 'timezone',
        'created_at', 'updated_at', 'deleted_at', 'search_keyword', 'unique_index'
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
        'schedule_template_id'                        => [
            "label" => 'schedule Template ID',
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
            "rules" => 'is_unique[schedule.unique_index,id,{id}]'
        ]
    ];
}
