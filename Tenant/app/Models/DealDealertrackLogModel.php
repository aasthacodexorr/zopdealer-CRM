<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class DealDealertrackLogModel extends Model
{
    protected $table      = 'deal_dealertrack_logs';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'deal_id',
        'application_profile_id',
        'status',
        'direction',
        'complete_response',
        'created_at',
        'updated_at',
        'deleted_at'

    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'deal_id' => [
            "label" => 'Deal Id',
            "rules" => 'required'
        ],

    ];
}
