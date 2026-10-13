<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class LeadResponseViewModel extends Model
{
    protected $table      = 'lead_responses_view';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'header','view', 'query', 'sort', 'default', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'Tenant\app\Entities\LeadResponseView';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'header'                          => [
            "label" => 'Title',
            "rules" => 'required|is_unique[lead_responses_view.header,id,{id}]'
        ],
    ];

}