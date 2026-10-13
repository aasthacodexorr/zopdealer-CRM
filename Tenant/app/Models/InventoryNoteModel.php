<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class InventoryNoteModel extends Model
{
    protected $table      = 'inventory_notes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id','title', 'description', 'attachment', 'inventory_id','user_id',
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'App\Entities\MasterEntity';
    protected $useTimestamps = true;

}