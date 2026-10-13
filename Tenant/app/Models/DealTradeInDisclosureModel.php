<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class DealTradeInDisclosureModel extends Model
{
    protected $table      = 'deal_trade_in_disclosures';
    protected $primaryKey = 'id';


    protected $allowedFields = [
        'trade_in_id', 'question',  'answer', 'type',
        'options', 'has_additional_information', 'additional_information',
        'additional_information_placeholder',
        'created_at', 'updated_at', 'deleted_at',
        'prefilled_templates'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Tenant\app\Entities\DealTradeInDisclosure';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'trade_in_id' => [
            "label" => 'Trade In Id',
            "rules" => 'required'
        ],
        'question' =>  [
            "label" => 'Question',
            "rules" => 'required|is_unique_in_subset[deal_trade_in_disclosures.question,trade_in_id,id,{trade_in_id},{id}]',
            'errors' => [
                'is_unique_in_subset' => '{value} is already present for this particular Trade In.',
            ],
        ]
    ];
}
