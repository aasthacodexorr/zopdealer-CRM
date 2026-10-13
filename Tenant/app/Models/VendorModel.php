<?php

namespace Tenant\app\Models;

use App\Models\ExactSearchableModel;

class VendorModel extends ExactSearchableModel
{
    protected $table      = 'vendors';
    protected $primaryKey = 'id';

    protected $searchableColumns = [
        'company_name', 'first_name', 'last_name',
        'phone', 'mobile', 'email'
    ];

    protected $allowedFields = [
        'company_name', 'first_name', 'last_name', 'rin', 'tax_number',
        'street_address', 'apt', 'city', 'province', 'postal_code',
        'country', 'phone', 'fax', 'mobile', 'email',
        'created_at', 'updated_at', 'deleted_at', 'phonetic_string',
        'search_keyword', 'mvda', 'sales_person_registration', 'additional_information'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'Tenant\app\Entities\Vendor';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'company_name'  => [
            "label" => 'Company Name',
            "rules" => 'required|is_unique[vendors.company_name,id,{id}]'
        ],
        'first_name'  => [
            "label" => 'First Name',
            "rules" => 'required|alpha_numeric_punct'
        ],
        'last_name'  => [
            "label" => 'Last Name',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'rin'  => [
            "label" => 'RIN',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'tax_number'  => [
            "label" => 'Tax Number',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'street_address'  => [
            "label" => 'Street Address',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'apt'  => [
            "label" => 'Suite / Apt',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'city'  => [
            "label" => 'City',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'province'  => [
            "label" => 'Province',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'postal_code'  => [
            "label" => 'Postal Code',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'country'  => [
            "label" => 'Country',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],

        'phone'  => [
            "label" => 'Phone',
            "rules" => 'permit_empty|is_natural|exact_length[10]'
        ],
        'fax'  => [
            "label" => 'Fax',
            "rules" => 'permit_empty|is_natural'
        ],
        'mobile'  => [
            "label" => 'Mobile',
            "rules" => 'permit_empty|is_natural|exact_length[10]'
        ],
        'email'  => [
            "label" => 'Email',
            "rules" => 'permit_empty|valid_email'
        ],
        'mvda'  => [
            "label" => 'MVDA',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'sales_person_registration'  => [
            "label" => 'Sales Person Registration',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],

    ];
}
