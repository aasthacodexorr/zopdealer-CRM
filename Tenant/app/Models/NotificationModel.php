<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id', 'sender', 'recipient', 'template_file', 'content',
        'premium', 'subject', 'read', 'max_tries', 'status',
        'comment', 'additional_recipients', 'spam', 'schedule', 'identifier',
        'context', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Tenant\app\Entities\Notification';
    protected $useTimestamps = true;
}
