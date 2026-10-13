<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class ActivityModel extends Model
{
    protected $table      = 'activities';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'title', 'description', 'start_date', 'end_date', 'notify',
        'notification_date', 'notification_method', 'notification_status',
        'notification_at',
        'assignee', 'status', 'priority', 'module', 'module_id',
        'activity_type', 'activity_method', 'all_day', 'created_at', 'updated_at', 'deleted_at',
        'is_private'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Tenant\app\Entities\Activity';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'title'  => [
            "label" => 'Title',
            "rules" => 'required|alpha_numeric_punct'
        ],
        'description'  => [
            "label" => 'Description',
            "rules" => 'permit_empty'
        ],
        'start_date'  => [
            "label" => 'Start Date',
            "rules" => 'permit_empty|valid_date'
        ],
        'end_date'  => [
            "label" => 'End Date',
            "rules" => 'permit_empty|valid_date'
        ],
        'notification_date'  => [
            "label" => 'Notification Date',
            "rules" => 'permit_empty|valid_date'
        ],

        'notify'  => [
            "label" => 'Notify',
            "rules" => 'permit_empty|is_natural'
        ],

        'notification_status'  => [
            "label" => 'Notification Status',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'status'  => [
            "label" => 'Status',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'notification_at'  => [
            "label" => 'Notification At',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'assignee'  => [
            "label" => 'Assignee',
            "rules" => 'permit_empty|is_natural'
        ],

        'priority'  => [
            "label" => 'Priority',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'module'  => [
            "label" => 'Module',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],

        'module_id'  => [
            "label" => 'Module Id',
            "rules" => 'permit_empty|is_natural'
        ],

        'activity_type'  => [
            "label" => 'Activity Type',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'activity_method'  => [
            "label" => 'Activity Method',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],

        'is_private'  => [
            "label" => 'Is Private',
            "rules" => 'permit_empty|is_natural'
        ],
        



    ];

}
