<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class LeadResponseFileModel extends Model
{
    protected $table      = 'lead_responses_files';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'lead_response_id', 'file_url', 'header',
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'Tenant\app\Entities\LeadResponseFile';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'lead_response_id' => [
            "label" => 'Lead Id',
            "rules" => 'required'
        ],
        'file_url'  => [
            "label" => 'File Url',
            "rules" => 'required'
        ],


    ];
}
