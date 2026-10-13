<?php

namespace Tenant\app\Models;

use App\Models\SearchableModel;
use CodeIgniter\Model;

class DealFinanceModel extends Model
{
    protected $table      = 'deal_finance';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'deal_id', 'total_amount', 'finance_commission', 'override_finance_calculation', 'interest_rate',
        'interest_rate_type', 'term', 'payment_frequency', 'interest_amount', 'emi_amount',
        'first_payment_date', 'lien_holder_name', 
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Tenant\app\Entities\DealFinance';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'deal_id' => [
            "label" => 'Deal Id',
            "rules" => 'required|is_unique[deal_finance.deal_id,id,{id}]'
        ],
        'total_amount' => [
            "label" => 'Total Amount',
            "rules" => 'permit_empty|decimal'
        ],
        'finance_commission' => [
            "label" => 'Finance Comission',
            "rules" => 'permit_empty|decimal'
        ],
        'override_finance_calculation'  => [
            "label" => 'Override Finance Calculation',
            "rules" => 'permit_empty|is_natural'
        ],
        'interest_rate' => [
            "label" => 'Interest Rate',
            "rules" => 'permit_empty|decimal'
        ],
        'interest_rate_type'  => [
            "label" => 'Interest Rate Type',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'term'  => [
            "label" => 'Term',
            "rules" => 'permit_empty|is_natural'
        ],
        'payment_frequency'  => [
            "label" => 'Payment Frequency',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'interest_amount' => [
            "label" => 'Interest Amount',
            "rules" => 'permit_empty|decimal'
        ],
        'emi_amount' => [
            "label" => 'Payment',
            "rules" => 'permit_empty|decimal'
        ],
        'lien_holder_name'  => [
            "label" => 'Lien Holder Name',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
       
    ];

}