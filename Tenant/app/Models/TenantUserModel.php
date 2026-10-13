<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class TenantUserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'email';
    protected $allowedFields = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'Tenant\app\Entities\TenantUser';
    protected $useTimestamps = true;

}