<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class InventoryImportImageModel extends Model
{
    protected $table      = 'inventory_image';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'inventory_id',
        'image_url',
        'sort_rank',
        'image_with_overlay_url',
        'active',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Tenant\app\Entities\InventoryImport';
    protected $useTimestamps = true;
}
