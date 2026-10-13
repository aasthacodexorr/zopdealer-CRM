<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class ConfigurationModel extends Model
{
    protected $table      = 'configurations';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'key', 'value',
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'Tenant\app\Entities\Configuration';
    protected $useTimestamps = true;


}