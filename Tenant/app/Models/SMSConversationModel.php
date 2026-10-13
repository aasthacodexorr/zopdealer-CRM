<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class SMSConversationModel extends Model
{
    protected $table      = 'sms_conversation';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'sid', 'from', 'to', 'body',
        'status', 'direction', 'read',
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;


    protected $validationRules    = [
        'sid'  => [
            "label" => 'SID',
            "rules" => 'required|alpha_numeric_punct'
        ],
        'from'  => [
            "label" => 'Sender Phone',
            "rules" => 'is_natural|exact_length[10]'
        ],
        'to'  => [
            "label" => 'Recipient Phone',
            "rules" => 'is_natural|exact_length[10]'
        ],
        'read'  => [
            "label" => 'Read',
            "rules" => 'permit_empty|is_natural'
        ],
    ];
}
