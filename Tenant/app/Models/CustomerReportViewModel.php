<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class CustomerReportViewModel extends Model
{
    protected $table      = 'customer_reports_view';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'header','view', 'query', 'sort', 'default', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'Tenant\app\Entities\CustomerView';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'header'                          => [
            "label" => 'Title',
            "rules" => 'required|is_unique[customer_reports_view.header,id,{id}]'
        ],
    ];

}