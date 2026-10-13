<?php

namespace Tenant\app\Models;

use App\Models\SearchableModel;
use CodeIgniter\Model;

class InventoryInternalInspectionModel extends Model
{
    protected $table      = 'inventory_internal_inspection';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'inventory_id', 'question',  'answer', 'type',
        'options', 'has_additional_information', 'additional_information',
        'additional_information_placeholder', 'css_class',
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'inventory_id' => [
            "label" => 'Inventory Id',
            "rules" => 'required'
        ],
        'question' =>  [
            "label" => 'Checkpoint',
            "rules" => 'required|is_unique_in_subset[inventory_internal_inspection.question,inventory_id,id,{inventory_id},{id}]',
            'errors' => [
                'is_unique_in_subset' => '{value} is already present for this checklist.',
            ],
        ]
    ];
}
