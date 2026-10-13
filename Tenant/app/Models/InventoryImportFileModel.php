<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class InventoryImportFileModel extends Model
{
    protected $table      = 'inventory_file';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'inventory_id', 'file_url', 'header', 'file',
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Tenant\app\Entities\InventoryImport';
    protected $useTimestamps = true;
}
