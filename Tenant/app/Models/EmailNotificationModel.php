<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class EmailNotificationModel extends NotificationModel
{
    protected $table      = 'email_notifications';

    protected $allowedFields = [
        'id', 'sender', 'recipient', 'template_file', 'content',
        'premium', 'subject', 'read', 'max_tries', 'status',
        'comment', 'additional_recipients', 'spam', 'schedule', 'identifier',
        'context', 'created_at', 'updated_at', 'deleted_at', 'attachment'
    ];
}
