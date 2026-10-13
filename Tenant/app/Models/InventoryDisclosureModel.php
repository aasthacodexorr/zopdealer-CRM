<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class InventoryDisclosureModel extends Model
{
    protected $table      = 'inventory_disclosures';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'inventory_id',
        'question',
        'answer',
        'type',
        'options',
        'has_additional_information',
        'additional_information',
        'additional_information_placeholder',
        'created_at',
        'updated_at',
        'deleted_at',
        'prefilled_templates'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Tenant\app\Entities\DealTradeInDisclosure';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'inventory_id' => [
            "label" => 'Inventory Id',
            "rules" => 'required'
        ],
        'question' =>  [
            "label" => 'Question',
            "rules" => 'required|is_unique_in_subset[inventory_disclosures.question,inventory_id,id,{inventory_id},{id}]',
            'errors' => [
                'is_unique_in_subset' => '{value} is already present for this particular Trade In.',
            ],
        ]
    ];
}
