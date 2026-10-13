<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class CustomerNoteModel extends Model
{
    protected $table      = 'customers_notes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id','title', 'description', 'attachment', 'customer_id','user_id',
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'Tenant\app\Entities\LeadResponseNote';
    protected $useTimestamps = true;

}