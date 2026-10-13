<?php

namespace Tenant\app\Models;

use App\Models\ExactSearchableModel;
use Tenant\app\Repositories\CustomerCreditApplicationRepository;

class CustomerModel extends ExactSearchableModel
{

    protected $beforeInsert = ['cleanJSONAndStringifyObject', 'createKeyWordString'];
    protected $beforeUpdate = ['createKeyWordString'];


    protected $table      = 'customers';
    protected $primaryKey = 'id';

    protected $searchableColumns = [
        'company_name',
        'first_name',
        'last_name',
        'phone',
        'mobile',
        'email'
    ];

    protected $allowedFields = [
        'first_name',
        'last_name',
        'rin_or_dl_flag',
        'driver_licence',
        'rin',
        'customer_type',
        'driver_licence_expiry_date',
        'date_of_birth',
        'company_name',
        'mvda',
        'year_end',
        'tax_number',
        'sales_person_registration',
        'street_address',
        'apt',
        'city',
        'province',
        'postal_code',
        'country',
        'phone',
        'fax',
        'mobile',
        'email',
        'additional_information',
        'assignee',
        'created_at',
        'updated_at',
        'deleted_at',
        'search_keyword',
        'other_data',
        'is_encrypted'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'Tenant\app\Entities\Customer';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'first_name'  => [
            "label" => 'First Name',
            "rules" => 'required|alpha_numeric_punct'
        ],
        'last_name'  => [
            "label" => 'Last Name',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'rin_or_dl_flag'  => [
            "label" => 'RIN or Driver License Flag',
            "rules" => 'permit_empty|is_natural'
        ],
        'driver_licence'  => [
            "label" => 'Driver Licence',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'rin'  => [
            "label" => 'RIN',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'customer_type'  => [
            "label" => 'Customer Type',
            "rules" => 'permit_empty|is_natural'
        ],
        'company_name'  => [
            "label" => 'Company Name',
            "rules" => 'required_without[first_name]'
        ],
        'mvda'  => [
            "label" => 'MVDA',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'year_end'  => [
            "label" => 'Year End',
            "rules" => 'permit_empty|is_natural'
        ],
        'tax_number'  => [
            "label" => 'Tax Number',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'sales_person_registration'  => [
            "label" => 'Sales Person Registration',
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
            "rules" => 'permit_empty|alpha_numeric_space'
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

    ];

    public function cleanJSONAndStringifyObject(array $data)
    {
        if (!empty($data['data']['other_data'])) {
            try {
                $other_data = json_decode($data['data']['other_data'], true);
                $attributes_to_remove = $this->allowedFields;
                $attributes_to_remove = array_merge($attributes_to_remove, [
                    'residence_status',
                    'coapplicant_residence_status',
                    'coapplicant_date_of_birth',
                    'previous_bankruptcy',
                    'drivers_license_expiry_date',
                    'drivers_license_number',
                    'home_address',
                    'mobile_phone',
                    'id',
                    'driving_licence',
                    'address_1',
                    'home_phone',
                    'driving_licence_expiry_date',
                    'birthdate'
                ]);
                $attributes_to_remove = array_merge($attributes_to_remove, ['customer_id']);
                $credit_application_mapper = (new CustomerCreditApplicationRepository())->getCreditApplicationMapper();
                $co_credit_application_mapper = (new CustomerCreditApplicationRepository())->getCoApplicantMappingWithInCreditApplication();

                foreach ($credit_application_mapper as $mapper_key => $mapper_value) {
                    array_push($attributes_to_remove, $mapper_key);
                    array_push($attributes_to_remove, $mapper_value);
                }

                foreach ($co_credit_application_mapper as $mapper_key => $mapper_value) {
                    array_push($attributes_to_remove, $mapper_key);
                    array_push($attributes_to_remove, $mapper_value);
                }

                foreach ($attributes_to_remove as $attribute) {
                    if (isset($other_data[$attribute])) {
                        unset($other_data[$attribute]);
                    }
                }

                $data['data']['other_data'] = json_encode($other_data);
            } catch (\Exception $ex) {
            }
        }

        return $data;
    }
}
