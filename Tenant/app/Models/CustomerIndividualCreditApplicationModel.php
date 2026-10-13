<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class CustomerIndividualCreditApplicationModel extends Model
{
    protected $table      = 'customer_individual_credit_application';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'customer_id',
        'salutation',
        'gender',
        'marital_status',
        'residence_ownership',
        'market_value',
        'mortgage_amount',
        'monthly_payment',
        'mortgage_bank',
        'years_at_present_address',
        'property_owner',
        'bankruptcy_declared',
        'bankruptcy_information',
        'previous_write_off',
        'previous_write_off_information',
        'financial_institution',
        'desired_monthly_payment',
        'employment_type',
        'job_title',
        'occupation',
        'employer_name',
        'employer_work_phone',
        'employer_work_phone_ext',
        'employment_period_years',
        'employment_period_months',
        'employer_address',
        'employer_city',
        'employer_province',
        'employer_postal_code',
        'employer_country',
        'rate_per_hour',
        'hours_per_week',
        'monthly_gross',
        'annual_gross',
        'previous_employment_type',
        'previous_job_title',
        'previous_occupation',
        'previous_employer_name',
        'previous_employer_work_phone',
        'previous_employer_work_phone_ext',
        'previous_employment_period_years',
        'previous_employment_period_months',
        'previous_employer_address',
        'previous_employer_city',
        'previous_employer_province',
        'previous_employer_postal_code',
        'previous_employer_country',
        'previous_rate_per_hour',
        'previous_hours_per_week',
        'previous_monthly_gross',
        'previous_annual_gross',
        'other_income_source',
        'other_rate_per_hour',
        'other_hours_per_week',
        'other_monthly_gross',
        'other_annual_gross',
        'created_at',
        'updated_at',
        'deleted_at',
        'previous_address',
        'previous_address_city',
        'previous_address_province',
        'previous_address_postal_code',
        'previous_residence_address_period_years',
        'previous_residence_period_months',
        'previous_residence_monthly_payment',
        'sin',
        'is_encrypted'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Tenant\app\Entities\CustomerIndividualCreditApplication';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'customer_id'                        => [
            "label" => 'Customer Id',
            "rules" => 'required|is_unique[customer_individual_credit_application.customer_id,id,{id}]'
        ],
        'sin'  => [
            "label" => 'SIN',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'salutation'  => [
            "label" => 'Salutation',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'gender'  => [
            "label" => 'Gender',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'marital_status'  => [
            "label" => 'Marital Status',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'residence_ownership'  => [
            "label" => 'Residence Ownership',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],

        'market_value'   => [
            "label" => 'Market Value',
            "rules" => 'permit_empty|decimal'
        ],
        'mortgage_amount'   => [
            "label" => 'Mortgage Amount',
            "rules" => 'permit_empty|decimal'
        ],
        'monthly_payment'   => [
            "label" => 'Monthly Payment',
            "rules" => 'permit_empty|decimal'
        ],
        'mortgage_bank'  => [
            "label" => 'Mortgage Bank',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'years_at_present_address'  => [
            "label" => 'Years at present address',
            "rules" => 'permit_empty|is_natural'
        ],

        'property_owner'  => [
            "label" => 'Property Owner',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'bankruptcy_declared'  => [
            "label" => 'Bankruptcy declared',
            "rules" => 'permit_empty|is_natural'
        ],
        'bankruptcy_information'  => [
            "label" => 'Bankrupcy information',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'previous_write_off'  => [
            "label" => 'Previous Write Off',
            "rules" => 'permit_empty|is_natural'
        ],
        'previous_write_off_information'  => [
            "label" => 'Previous Write Off information',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],

        'financial_institution'  => [
            "label" => 'Financial Institution',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'desired_monthly_payment'   => [
            "label" => 'Desired Monthly Payment',
            "rules" => 'permit_empty|decimal'
        ],
        'financial_institution'  => [
            "label" => 'Financial Institution',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'employment_type'  => [
            "label" => 'Employment Type',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'job_title'  => [
            "label" => 'Job Title',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'occupation'  => [
            "label" => 'Occupation',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],

        'employer_name'  => [
            "label" => 'Employer Name',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'employer_work_phone'  => [
            "label" => 'Employer Work Phone',
            "rules" => 'permit_empty|is_natural|exact_length[10]'
        ],
        'employer_work_phone_ext'  => [
            "label" => 'Employer Work Phone Ext',
            "rules" => 'permit_empty|is_natural'
        ],
        'employment_period_years'  => [
            "label" => 'Employment Period Years',
            "rules" => 'permit_empty|is_natural'
        ],
        'employment_period_months'  => [
            "label" => 'Employment Period Months',
            "rules" => 'permit_empty|is_natural'
        ],

        'employer_address'  => [
            "label" => 'Employer Address',
            "rules" => 'permit_empty'
        ],
        'employer_city'  => [
            "label" => 'Employer City',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'employer_province'  => [
            "label" => 'Employer Province',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'employer_postal_code'  => [
            "label" => 'Employer Postal Code',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'employer_country'  => [
            "label" => 'Employer Country',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],

        'rate_per_hour'   => [
            "label" => 'Rate Per Hour',
            "rules" => 'permit_empty|decimal'
        ],
        'hours_per_week'  => [
            "label" => 'Hours Per Week',
            "rules" => 'permit_empty|is_natural'
        ],
        'monthly_gross'   => [
            "label" => 'Monthly Gross',
            "rules" => 'permit_empty|decimal'
        ],
        'annual_gross'   => [
            "label" => 'Annual Gross',
            "rules" => 'permit_empty|decimal'
        ],
        'previous_employment_type'  => [
            "label" => 'Previous Employment Type',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],

        'previous_job_title'  => [
            "label" => 'Previous Job Title',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'previous_occupation'  => [
            "label" => 'Previous Occupation',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'previous_employer_name'  => [
            "label" => 'Previous Employer Name',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'previous_employer_work_phone'  => [
            "label" => 'Previous Employer Work Phone',
            "rules" => 'permit_empty|is_natural|exact_length[10]'
        ],
        'previous_employer_work_phone_ext'  => [
            "label" => 'Previous Employer Work Phone Ext',
            "rules" => 'permit_empty|is_natural'
        ],

        'previous_employment_period_years'  => [
            "label" => 'Previous Employment Period Years',
            "rules" => 'permit_empty|is_natural'
        ],
        'previous_employment_period_months'  => [
            "label" => 'Previous Employment Period Months',
            "rules" => 'permit_empty|is_natural'
        ],
        'previous_employer_address'  => [
            "label" => 'Previous Employer Address',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'previous_employer_city'  => [
            "label" => 'Previous Employer City',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'previous_employer_province'  => [
            "label" => 'Previous Employer Province',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],


        'previous_employer_postal_code'  => [
            "label" => 'Previous Employer Postal Code',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'previous_employer_country'  => [
            "label" => 'Previous Employer Country',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'previous_rate_per_hour'   => [
            "label" => 'Previous Rate Per Hour',
            "rules" => 'permit_empty|decimal'
        ],
        'previous_hours_per_week'  => [
            "label" => 'Previous Hours Per Week',
            "rules" => 'permit_empty|is_natural'
        ],
        'previous_monthly_gross'   => [
            "label" => 'Previous Monthly Gross',
            "rules" => 'permit_empty|decimal'
        ],

        'previous_annual_gross'   => [
            "label" => 'Previous Annual Gross',
            "rules" => 'permit_empty|decimal'
        ],
        'other_income_source'  => [
            "label" => 'Other Income Source',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'other_rate_per_hour'   => [
            "label" => 'Other Rate Per Hour',
            "rules" => 'permit_empty|decimal'
        ],
        'other_hours_per_week'  => [
            "label" => 'Other Hours Per Week',
            "rules" => 'permit_empty|is_natural'
        ],
        'other_monthly_gross'   => [
            "label" => 'Other Monthly Gross',
            "rules" => 'permit_empty|decimal'
        ],

        'other_annual_gross'   => [
            "label" => 'Other Annual Gross',
            "rules" => 'permit_empty|decimal'
        ],


        'previous_address'  => [
            "label" => 'Previous Address',
            "rules" => 'permit_empty'
        ],
        'previous_address_city'  => [
            "label" => 'Previous Address City',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'previous_address_province'  => [
            "label" => 'Previous Address Province',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'previous_address_postal_code'  => [
            "label" => 'Previous Address Postal Code',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'previous_residence_address_period_years'  => [
            "label" => 'Previous Residence Period Years',
            "rules" => 'permit_empty|is_natural'
        ],
        'previous_residence_period_months'  => [
            "label" => 'Previous Residence Period Months',
            "rules" => 'permit_empty|is_natural'
        ],
        'previous_residence_monthly_payment'   => [
            "label" => 'Previous Residence Monthly Payment',
            "rules" => 'permit_empty|decimal'
        ],

    ];
}
