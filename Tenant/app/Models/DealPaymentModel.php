<?php

namespace Tenant\app\Models;

use App\Models\SearchableModel;
use CodeIgniter\Model;

class DealPaymentModel extends Model
{
    protected $table      = 'deal_payments';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'deal_id', 'amount', 'type', 'description', 'is_downpayment_or_deposit',
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Tenant\app\Entities\DealPayment';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'deal_id' => [
            "label" => 'Deal Id',
            "rules" => 'required'
        ],
        'amount' => [
            "label" => 'Amount',
            "rules" => 'permit_empty|decimal'
        ],
        'type'  => [
            "label" => 'Type',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'description'  => [
            "label" => 'Description',
            "rules" => 'permit_empty'
        ],
        'is_downpayment_or_deposit'                        => [
            "label" => 'Downpayment or Deposit Flag',
            "rules" => 'permit_empty|is_natural'
        ],
        
    ];
}
