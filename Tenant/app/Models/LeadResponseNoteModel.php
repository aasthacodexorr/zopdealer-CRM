<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class LeadResponseNoteModel extends Model
{
    protected $table      = 'lead_responses_notes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id','title', 'description', 'attachment', 'lead_response_id','user_id',
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'Tenant\app\Entities\LeadResponseNote';
    protected $useTimestamps = true;

}