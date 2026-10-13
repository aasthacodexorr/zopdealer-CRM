<?php

namespace Tenant\app\Models;

use App\Models\SearchableModel;
use CodeIgniter\Model;

class CustomerInsuranceModel extends Model
{
    protected $table      = 'customer_insurance';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'customer_id', 'company_name', 'agent_name', 'policy_number',
        'policy_expiry_date', 'phone', 'phone_ext',
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Tenant\app\Entities\CustomerInsurance';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'customer_id'                        => [
            "label" => 'Customer Id',
            "rules" => 'required|is_unique[customer_company_credit_application.customer_id,id,{id}]'
        ],
        'company_name'  => [
            "label" => 'Company Name',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'agent_name'  => [
            "label" => 'Agent',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'policy_number'  => [
            "label" => 'Policy Number',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'phone'  => [
            "label" => 'Phone',
            "rules" => 'permit_empty|is_natural|exact_length[10]'
        ],
        'phone_ext'  => [
            "label" => 'Phone Ext',
            "rules" => 'permit_empty|is_natural'
        ],

    ];

}