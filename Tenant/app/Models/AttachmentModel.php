<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class AttachmentModel extends Model
{

    protected $table      = 'attachments';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'module', 'sub_module', 'entity_id', 'url', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $returnType    = 'CodeIgniter\Entity';

    protected $useSoftDeletes = false;
    protected $useTimestamps = true;
}
