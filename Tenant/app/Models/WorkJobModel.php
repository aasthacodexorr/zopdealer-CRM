<?php

namespace Tenant\app\Models;

use App\Models\ExactSearchableModel;

class WorkJobModel extends ExactSearchableModel
{
    protected $table      = 'work_jobs';
    protected $primaryKey = 'id';

    protected $searchableColumns = [
        'title',
    ];

    protected $allowedFields = [
        'title', 'description', 'issue', 'reason', 'solution',
        'job_card_information', 

        'created_at', 'updated_at', 'deleted_at', 'work_order_id'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'title'  => [
            "label" => 'Title',
            "rules" => 'required'
        ],
        'work_order_id'  => [
            "label" => 'Work Order Id',
            "rules" => 'required'
        ],
    ];
}
