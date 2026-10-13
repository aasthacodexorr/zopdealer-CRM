<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class DealNoteModel extends Model
{
    protected $table      = 'deal_notes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id','title', 'description', 'attachment', 'deal_id','user_id',
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'App\Entities\MasterEntity';
    protected $useTimestamps = true;

}