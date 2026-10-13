<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditOutboxModel extends Model
{
    protected $table      = 'audit_outbox';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'database',
        'database_uid',
        'event_id',
        'payload',
        'response',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $useSoftDeletes = false;
    protected $useTimestamps = true;
    protected $returnType = 'CodeIgniter\Entity';

    protected $validationRules = [
        'database' => [
            "label" => 'Database',
            "rules" => 'required',
        ],
        'database_uid' => [
            "label" => 'Database UID',
            "rules" => 'required',
        ],
        'event_id' => [
            "label" => 'Event ID',
            "rules" => 'permit_empty|is_unique[audit_outbox.event_id,id,{id}]',
        ],
        'payload' => [
            "label" => 'Payload',
            "rules" => 'permit_empty',
        ],
        'response' => [
            "label" => 'Response',
            "rules" => 'permit_empty',
        ],
        'status' => [
            "label" => 'Status',
            "rules" => 'permit_empty',
        ],
    ];
}
