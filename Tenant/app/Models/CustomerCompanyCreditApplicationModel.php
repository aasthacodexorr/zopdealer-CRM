<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class CustomerCompanyCreditApplicationModel extends Model{
    protected $table      = 'customer_company_credit_application';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'customer_id', 'years_at_present_address', 'business_type', 'number_of_employees', 'years_in_business',
        'accountant_firm', 'accountant_personal', 'accountant_phone', 'accountant_phone_ext', 'principal_owner',
        'officer_name', 'officer_position', 'ownership_percentage', 'officer_address', 'officer_phone',
        'officer_phone_ext', 'bank_name', 'bank_number', 'bank_account_number', 'bank_account_type',
        'bank_branch_number', 'bank_contact_personal', 'bank_phone', 'bank_phone_ext', 'bank_street_address',
        'bank_apt', 'bank_city', 'bank_province','bank_postal_code', 'bank_country',
        'created_at', 'updated_at', 'deleted_at', 'is_encrypted'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Tenant\app\Entities\CustomerCompanyCreditApplication';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'customer_id'                        => [
            "label" => 'Customer Id',
            "rules" => 'required|is_unique[customer_company_credit_application.customer_id,id,{id}]'
        ],
        'years_at_present_address'                        => [
            "label" => 'Years at present address',
            "rules" => 'permit_empty|is_natural'
        ],
        'business_type'  => [
            "label" => 'Business Type',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'number_of_employees'                        => [
            "label" => 'Number of Employees',
            "rules" => 'permit_empty|is_natural'
        ],
        'years_in_business'                        => [
            "label" => 'Years in business',
            "rules" => 'permit_empty|is_natural'
        ],
        'accountant_firm'  => [
            "label" => 'Accountant Firm',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'accountant_personal'  => [
            "label" => 'Accountant Personal',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'accountant_phone'  => [
            "label" => 'Accountant Phone',
            "rules" => 'permit_empty|is_natural|exact_length[10]'
        ],
        'accountant_phone_ext'  => [
            "label" => 'Accountant Phone Ext',
            "rules" => 'permit_empty|is_natural'
        ],
        'principal_owner'  => [
            "label" => 'Principal Owner',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],

        'officer_name'  => [
            "label" => 'Officer Name',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'officer_position'  => [
            "label" => 'Officer Position',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'ownership_percentage'                        => [
            "label" => 'Ownership Percentage',
            "rules" => 'permit_empty|decimal'
        ],
        'officer_address'  => [
            "label" => 'Officer Address',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'officer_phone'  => [
            "label" => 'Officer Phone',
            "rules" => 'permit_empty|is_natural|exact_length[10]'
        ],

        'officer_phone_ext'  => [
            "label" => 'Officer Phone Ext',
            "rules" => 'permit_empty|is_natural'
        ],
        'bank_name'  => [
            "label" => 'Bank Name',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'bank_number'  => [
            "label" => 'Bank Number',
            "rules" => 'permit_empty|is_natural'
        ],
        'bank_account_number'  => [
            "label" => 'Bank Account Number',
            "rules" => 'permit_empty|is_natural'
        ],
        'bank_account_type'  => [
            "label" => 'Bank Account Type',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],

        'bank_branch_number'  => [
            "label" => 'Bank Branch Number',
            "rules" => 'permit_empty|is_natural'
        ],
        'bank_contact_personal'  => [
            "label" => 'Bank Contact Personal',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'bank_phone'  => [
            "label" => 'Bank Phone',
            "rules" => 'permit_empty|is_natural|exact_length[10]'
        ],
        'bank_phone_ext'  => [
            "label" => 'Bank Phone Ext',
            "rules" => 'permit_empty|is_natural'
        ],
        'bank_street_address'  => [
            "label" => 'Bank Street Address',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],

        'bank_apt'  => [
            "label" => 'Bank Apt',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'bank_city'  => [
            "label" => 'Bank City',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'bank_province'  => [
            "label" => 'Bank Province',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'bank_postal_code'  => [
            "label" => 'Bank Postal Code',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'bank_country'  => [
            "label" => 'Bank Country',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],

    ];
}