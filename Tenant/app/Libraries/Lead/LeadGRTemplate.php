<?php

namespace Tenant\app\Libraries\Lead;

class LeadGRTemplate
{
    private const MAPPINGS = [
        'applicant_how_long' => ["residence_period", "residence_period_years"],
        'credit_score' => ["credit_rating", "credit_score"],
        'applicant_street' => ["street_address", "home_address"],
        'applicant_city' => ["city"],
        'applicant_state' => ["province"],
        //'employment_status'  => ["employment_status"],    
        'employer_name' => ["company_name", "employer_name"],
        'gross_monthly_income' => ["monthly_gross_income", "gross_income"],
        'duration_atwork' => ["employment_period_with_this_salary", "employment_period_years"],
        'applicant_monthly_payment' => ["mortgage_rent_amount_monthly"],
        'applicant_home_status' => ["residence_status"],
        'occupation' => ["job_title"],
        'applicant_landlord_name' => ["landlord_name"],
        'marital_status' => ["marital_status"],
        'applicant_zip_code' => ["postal_code"],
        'dob' => ["date_of_birth"],
        'driver_license' => ["drivers_license_number"],
        'driver_expiry' => ["drivers_license_expiry_date"],
        'current_status' => ["current_status"],
        'other_income' => ["other_gross_income"],
        'income_description' => ["other_income_source"],
        'previous_applicant_street' => ["previous_address"],
        'previous_applicant_city' => ["previous_address_city"],
        'previous_applicant_state' => ["previous_address_province"],
        'previous_applicant_postal_code' => ["previous_address_postal_code"],
        'previous_applicant_how_long' => ["previous_residence_address_period_years"],
        'max_downpayment' => ["max_downpayment"],
        'max_biweekly_budget' => ["max_bi_weekly_budget"],
        'previous_employer_name' => ["previous_employer_name"],
        'previous_occupation' => ["previous_occupation", "previous_job_title"],
        'previous_duration_atwork' => ["previous_duration_atwork", "previous_employment_period_years"],
        'previous_work_address' => ["previous_work_address"],
        'previous_employer_street' => ["previous_employer_street", "previous_employer_address"],
        'previous_employer_city' => ["previous_employer_city"],
        'previous_employer_state' => ["previous_employer_state", "previous_employer_province"],
        'previous_employer_zip_code' => ["previous_employer_zip_code", "previous_employer_postal_code"],
        'previous_employer_phone' => ["previous_employer_phone", "previous_work_phone"],
        'employer_street' => ["employer_address"],
        'employer_city' => ["employer_city"],
        'employer_state' => ["employer_province"],
        'employer_zip_code' => ["employer_postal_code"],
        'employer_phone' => ["employer_work_phone", "employer_phone"]
    ];
    private const MARITAL_STATUS_MAP = [
        'Single' => 'Single',
        'Married' => 'Married',
        'Divorced' => 'Divorced',
        'Common Law' => 'Common Law',
    ];

    private const CURRENT_STATUS_MAP = [
        'Canadian Citizen' => 'Citizen',
        'PR' => 'PR',
        'Student' => 'Student',
        'Work Permit' => 'Work Permit',
        'Other' => 'Other',
    ];

    /**
     * Finds the matching value for a given key in the data array.
     * @param string $key
     * @param array $data
     * @return string
     */
    private function findMatchingValue(string $key, array $data): string
    {
        $mappings = self::MAPPINGS[$key] ?? [];
        foreach ($mappings as $mapping) {
            if (isset($data[$mapping])) {
                return $data[$mapping];
            }
        }
        return '';
    }

    /**
     * Formats the lead data according to the template.
     * @param array $data
     * @return array [Formatted data array]
     */
    public function formatWithTemplate(array $data): array
    {
        $template = [];
        $flatData = $this->flattenArray($data);

        $template = array_merge(
            $template,
            $this->getLeadInformation($data),
            $this->getApplicationPersonalInfo($flatData),
            $this->getWorksheetInfo($flatData),
            $this->getAddressDetails($flatData),
            $this->getEmploymentDetails($flatData),
            $this->getIncomeDetails($flatData),
        );
        return $template;
    }

    /**
     * Gets the lead information from the data array.
     * @param array $data
     * @return array [Matching value if found, otherwise an empty string]
     */
    private function getLeadInformation(array $data): array
    {
        return [
            'first_name' => $data['first_name'],
            'last_name' => $data["last_name"],
            'mobile' => $data["phone"],
            'email' => $data["email"],
            'lead_source' => $data["source"],
            'lead_owner' => '',  // keep it blank
            'other_data' => $data["other_data"],
        ];
    }

    /**
     * Gets the lead information from the data array.
     * @param array $flatData 
     * @return array 
     */
    private function getApplicationPersonalInfo(array $flatData): array
    {
        $dob = $flatData['birth_year'] ?? '';
        $dob .= $dob ? "-{$flatData['birth_month']}-{$flatData['birth_date']}" : '';
        $dob = $dob ?: $this->findMatchingValue('dob', $flatData);

        return [
            'dob' => $dob,
            'driver_license' => $this->findMatchingValue('driver_license', $flatData),
            'driver_expiry' => $this->findMatchingValue('driver_expiry', $flatData),
            'marital_status' => $this->exactMatch('MARITAL_STATUS_MAP', $this->findMatchingValue('marital_status', $flatData), '-None-'),
            'current_status' => $this->exactMatch('CURRENT_STATUS_MAP', $this->findMatchingValue('current_status', $flatData), '-None-')
        ];
    }

    /**
     * Gets the worksheet information from the flat data array.
     * @param array $flatData 
     * @return array 
     */
    private function getWorksheetInfo(array $flatData): array
    {
        $inventoryTitle = $this->buildInventoryTitle($flatData);

        return [
            'max_downpayment' => $this->findMatchingValue('max_downpayment', $flatData),
            'max_biweekly_budget' => $this->findMatchingValue('max_biweekly_budget', $flatData),
            'credit_score' => $this->findMatchingValue('credit_score', $flatData),
            'inventory_title' => $inventoryTitle,
        ];
    }

    /**
     * Building InventoryTitle
     * @param array $flatData
     * @return string
     */
    private function buildInventoryTitle(array $flatData): string
    {
        if (!isset($flatData['inventory']) || $flatData['inventory']==null) {
            return '';
        }
    
        return sprintf('%s - %s %s %s', 
            $flatData['stock_no'], 
            $flatData['year'], 
            $flatData['make'], 
            $flatData['model']
        );
    }

    /**
     * Gets the address details from the flat data array.
     * @param array $flatData
     * @return array
     */
    private function getAddressDetails(array $flatData): array
    {
        return [
            'applicant_street' => $this->findMatchingValue('applicant_street', $flatData),
            'applicant_city' => $this->findMatchingValue('applicant_city', $flatData),
            'applicant_state' => $this->findMatchingValue('applicant_state', $flatData),
            'applicant_zip_code' => $this->findMatchingValue('applicant_zip_code', $flatData),
            'applicant_how_long' => $this->findMatchingValue('applicant_how_long', $flatData),
            'applicant_home_status' => $this->findMatchingValue('applicant_home_status', $flatData),
            'applicant_landlord_name' => $this->findMatchingValue('applicant_landlord_name', $flatData),
            'applicant_monthly_payment' => $this->findMatchingValue('applicant_monthly_payment', $flatData),
            'previous_applicant_street' => $this->findMatchingValue('previous_applicant_street', $flatData),
            'previous_applicant_city' => $this->findMatchingValue('previous_applicant_city', $flatData),
            'previous_applicant_state' => $this->findMatchingValue('previous_applicant_state', $flatData),
            'previous_applicant_postal_code' => $this->findMatchingValue('previous_applicant_postal_code', $flatData),
            'previous_applicant_how_long' => $this->findMatchingValue('previous_applicant_how_long', $flatData)
        ];
    }

    /**
     * Gets the employment details from the flat data array.
     * @param array $flatData
     * @return array
     */
    private function getEmploymentDetails(array $flatData): array
    {
        return [
            'employer_name' => $this->findMatchingValue('employer_name', $flatData),
            'occupation' => $this->findMatchingValue('occupation', $flatData),
            'duration_atwork' => $this->findMatchingValue('duration_atwork', $flatData),
            'employer_street' => $this->findMatchingValue('employer_street', $flatData),
            'employer_city' => $this->findMatchingValue('employer_city', $flatData),
            'employer_state' => $this->findMatchingValue('employer_state', $flatData),
            'employer_zip_code' => $this->findMatchingValue('employer_zip_code', $flatData),
            'employer_phone' => $this->findMatchingValue('employer_phone', $flatData),
            'previous_employer_name' => $this->findMatchingValue('previous_employer_name', $flatData),
            'previous_occupation' => $this->findMatchingValue('previous_occupation', $flatData),
            'previous_duration_atwork' => $this->findMatchingValue('previous_duration_atwork', $flatData),
            'previous_employer_street' => $this->findMatchingValue('previous_employer_street', $flatData),
            'previous_employer_city' => $this->findMatchingValue('previous_employer_city', $flatData),
            'previous_employer_state' => $this->findMatchingValue('previous_employer_state', $flatData),
            'previous_employer_zip_code' => $this->findMatchingValue('previous_employer_zip_code', $flatData),
            'previous_employer_phone' => $this->findMatchingValue('previous_employer_phone', $flatData)
            // 'employment_status' => $this->findMatchingValue($this->employment_status, $flatData),
        ];
    }

    /**
     * Gets the income details from the flat data array.
     * @param array $flatData
     * @return array
     */
    private function getIncomeDetails(array $flatData): array
    {
        return [
            'gross_monthly_income' => $this->findMatchingValue('gross_monthly_income', $flatData),
            'other_income' => $this->findMatchingValue('other_income', $flatData),
            'income_description' => $this->findMatchingValue('income_description', $flatData)
        ];
    }

    /**
     * Flattens a nested array into a single-level array.
     * @param array $data [Nested array to flatten].
     * @return array [Flattened array]
     */
    private function flattenArray(array $data): array
    {
        $flatData = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $flatData = array_merge($flatData, $this->flattenArray($value));
            } elseif ($key === 'other_data' && is_string($value)) {
                $decodedValue = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $flatData = array_merge($flatData, $this->flattenArray($decodedValue));
                } else {
                    $flatData[$key] = $value; // Keep the original value if JSON decoding fails
                }
            } else {
                $flatData[$key] = $value;
            }
        }
        return $flatData;
    }

    /**
     * Finds an exact match for a given input in a specified array variable.
     * @param string $arrayVariable  [Name of the array variable to search in]
     * @param string $input  [Input value to search for]
     * @param string $default  [Default value to return if no match is found]
     * @return string
     */
    private function exactMatch(string $arrayVariable, string $input, string $default = ''): string
    {
        $mapping = constant(self::class . '::' . $arrayVariable);
        return $mapping[$input] ?? $default;
    }

}