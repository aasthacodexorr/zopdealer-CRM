<?php

namespace Tenant\app\Repositories;

use App\Helpers\Utility;
use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\CustomerCompanyCreditApplicationModel;
use Tenant\app\Models\CustomerIndividualCreditApplicationModel;

/**
 * CustomerCreditApplicationRepository CRUD and pagination for the Customer Credit Application
 */
class CustomerCreditApplicationRepository extends CRUDWithAdvanceRepository
{
    const NOT_ENCRYPTED = 0;
    const ENCRYPTED = 1;
    const ENCRYPTION_NOT_REQUIRED = 2;

    public function __construct()
    {
        parent::__construct(new CustomerIndividualCreditApplicationModel());
        $this->entity = 'Tenant\app\Entities\CustomerIndividualCreditApplication';
        $this->entityLabel = 'Customer details';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'customer_application_id';
    }

    public function show($id): ?array
    {

        $response = $this->model->asArray()->find($id);

        if (!empty($response)) {
            if ($response['is_encrypted'] == static::ENCRYPTED) {
                try {
                    $response = $this->getDecryptedCreditApplication($response);
                } catch (\Exception $ex) {
                    log_message('warning', $ex->getMessage());
                }
            }
        }

        return $response;
    }

    public function switchCustomerState(int $customer_type)
    {
        //$customer_type : type 0 is individual, type 1 is company
        switch ($customer_type) {
            case 0:
                $this->model = new CustomerIndividualCreditApplicationModel();
                $this->entity = 'Tenant\app\Entities\CustomerIndividualCreditApplication';
                break;

            case 1:
                $this->model = new CustomerCompanyCreditApplicationModel();
                $this->entity = 'Tenant\app\Entities\CustomerCompanyCreditApplication';
                break;
        }

        return $this;
    }

    /**
     * findByCustomerId search CustomerCreditApplication By Customer Id
     *
     * @param  mixed $customer_id
     * @return void
     */
    public function findByCustomerId($customer_id)
    {
        $response = $this->model->where('customer_id', $customer_id)->asArray()->first();

        if (!empty($response)) {
            if ($response['is_encrypted'] == static::ENCRYPTED) {
                try {
                    $response = $this->getDecryptedCreditApplication($response);
                } catch (\Exception $ex) {
                    log_message('warning', $ex->getMessage());
                }
            }
        }

        return $response;
    }

    public function filterByCustomerId($customer_id)
    {
        $this->model->where('customer_id', $customer_id);
        return $this;
    }

    public function deleteWithoutKey()
    {
        $this->model->delete();
    }

    /**
     * saveWithOutValidation
     *
     * @param  mixed $data
     * @return 
     */
    public function saveWithOutValidation(array $data)
    {
        return $this->model->protect(false)->insert($data, true);
    }

    public function getCreditApplicationMapper(): array
    {
        return [
            'marital_status' => 'marital_status',
            'sin' => 'sin',
            'market_value' => 'market_value',
            'mortgage_amount' => 'mortgage_balance_amount',
            'monthly_payment'   => 'mortgage_rent_amount_monthly',
            'years_at_present_address'  => 'residence_period_years',
            'employer_name' => 'employer_name',
            'job_title' => 'job_title',
            'employer_work_phone' => 'employer_work_phone',
            'employment_period_years' => 'employment_period_years',
            'employment_period_months' => 'employment_period_months',
            'employer_address' => 'employer_address',
            'employer_city' => 'employer_city',
            'employer_province' => 'employer_province',
            'employer_postal_code' => 'employer_postal_code',
            'monthly_gross' => 'gross_income',
            'previous_job_title' => 'previous_job_title',
            'previous_employer_name' => 'previous_employer_name',
            'previous_employer_work_phone' => 'previous_work_phone',
            'previous_employment_period_years' => 'previous_employment_period_years',
            'previous_employment_period_months' => 'previous_employment_period_months',
            'other_income_source' => 'other_income_source',
            'other_monthly_gross' => 'other_gross_income',

            'previous_address' => 'previous_address',
            'previous_address_city' => 'previous_address_city',
            'previous_address_province' => 'previous_address_province',
            'previous_address_postal_code' => 'previous_address_postal_code',
            'previous_residence_address_period_years' => 'previous_residence_address_period_years',
            'previous_residence_address_period_years' => 'previous_residence_period_years',
            'previous_residence_period_months' => 'previous_residence_period_months',
            'previous_residence_monthly_payment' => 'previous_residence_monthly_payment',

        ];
    }

    public function getCoApplicantMappingWithInCreditApplication(): array
    {
        return [
            'first_name' => 'coapplicant_first_name',
            'last_name'  => 'coapplicant_last_name',
            'email'      => 'coapplicant_email',
            'phone'      => 'coapplicant_phone',
            'mobile'     => 'coapplicant_mobile_phone',
            'work_phone' => 'coapplicant_work_phone',
            'sin'        => 'coapplicant_sin',
            //'date_of_birth'  => 'coapplicant_date_of_birth',
            'marital_status' => 'coapplicant_marital_status',
            'market_value' => 'coapplicant_market_value',
            'mortgage_amount' => 'coapplicant_mortgage_balance_amount',
            'driver_licence' => 'coapplicant_drivers_license_number',

            'street_address' => 'coapplicant_home_address',
            'city'       => 'coapplicant_city',
            'province'   => 'coapplicant_province',
            'postal_code'    => 'coapplicant_postal_code',
            //'residence_ownership' => 'coapplicant_residence_status',
            'monthly_payment' => 'coapplicant_mortgage_rent_amount_monthly',
            'residence_period_years' => 'coapplicant_residence_period_years',
            'years_at_present_address' => 'coapplicant_residence_period_years',

            'employer_name' => 'coapplicant_employer_name',
            'job_title' => 'coapplicant_job_title',
            'employer_work_phone' => 'coapplicant_employer_work_phone',
            'employer_work_phone'    => 'coapplicant_employer_work_phone',
            'monthly_gross'  => 'coapplicant_gross_income',
            'employment_period_years' => 'coapplicant_employment_period_years',
            'employment_period_months'   => 'coapplicant_employment_period_months',

            // 'bankruptcy_declared' => 'coapplicant_previous_bankruptcy'
        ];
    }

    public function encryptIndividualCreditApplication(): void
    {
        $db = \Config\Database::connect();
        $builder = $db->table('customer_individual_credit_application');
        $builder->select('*');
        $builder->where('is_encrypted', static::NOT_ENCRYPTED);
        $builder->orderBy('id', 'DESC');
        $query = $builder->get();

        foreach ($query->getResultArray() as $row) {
            try {
                $columns_to_encrypt = ['sin'];
                $modified_data = [];
                foreach ($columns_to_encrypt as $column) {
                    if (!empty($row[$column])) {
                        try {
                            $modified_data[$column] = Utility::encryptString($row[$column], getenv("password.encryption"));
                        } catch (\Exception $ex) {
                        }
                    }
                }

                if (!empty($modified_data)) {
                    $modified_data['is_encrypted'] = static::ENCRYPTED;
                    (new CustomerIndividualCreditApplicationModel())->protect(false)->update($row['id'], $modified_data);
                }
            } catch (\Exception $ex) {
                log_message('warning', $ex->getMessage());
            }
        }
    }

    private function getDecryptedCreditApplication(array $data): array
    {
        $columns_to_decrypt = ['sin'];
        foreach ($columns_to_decrypt as $column) {
            if (!empty($data[$column])) {
                try {
                    $data[$column] = Utility::decryptString($data[$column], getenv("password.encryption"));
                } catch (\Exception $ex) {
                }
            }
        }

        $data['is_encrypted'] = static::NOT_ENCRYPTED;

        return $data;
    }
}
