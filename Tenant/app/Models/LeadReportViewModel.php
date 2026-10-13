<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class LeadReportViewModel extends Model
{
    protected $table      = 'lead_reports_view';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'header','view', 'query', 'sort', 'default', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'Tenant\app\Entities\DealView';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'header'                          => [
            "label" => 'Title',
            "rules" => 'required|is_unique[lead_reports_view.header,id,{id}]'
        ],
    ];

}