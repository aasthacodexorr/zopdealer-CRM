<?php

namespace Tenant\app\Models;

use App\Models\SearchableModel;
use CodeIgniter\Model;

class DealDeliveryChecklistModel extends Model
{
    protected $table      = 'deal_delivery_checklist';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'deal_id', 'detail', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'detail'  => [
            "label" => 'Detail',
            "rules" => 'required'
        ],
        'deal_id' => [
            "label" => 'Deal Id',
            "rules" => 'required|alpha_numeric_punct'
        ]
    ];
}
