<?php

namespace App\Libraries\DealerTrack;

/**
 * DealerTrackExport  This class contains methods associated with DealerTrackExport
 */
class DealerTrackExport
{
    private array $transformed_data = [];

    private string $dealer_id = '';

    private string $dealer_confirm_id = '';

    public  function __construct()
    {
        $this->transformed_data['Header']['AssetType'] = 'AU';
        $this->transformed_data['Header']['DMSKey'] = getenv('dealertrack.dms.id');
        $this->transformed_data['Header']['DMSPassKey'] = getenv('dealertrack.dms.key');
    }

    public function transform(array $data): self
    {
        $this->transformed_data['Header']['DMSReference'] = $data['DMSReference'];

        if (!empty($data['customers'])) {
            foreach ($data['customers'] as $customer) {
                if (is_array($customer)) {

                    $is_personal_applicant = $customer['customer_type'] == '0' ?  true : false;
                    $is_primary_customer = $customer['deal_customer_type'] == 'primary_customer' ?  true : false;

                    if ($is_personal_applicant) {
                        $this->transformed_data['Header']['PrimaryApplicantType'] = 'CONSUMER';
                        if ($is_primary_customer) {
                            $this->personalCustomerMapping($customer, 'PrimaryApplicant');
                        } else {
                            $this->personalCustomerMapping($customer, 'CoApplicants');
                            $this->transformed_data['CoApplicants'] = [$this->transformed_data['CoApplicants']];
                        }
                    }

                    if (!$is_personal_applicant) {
                        $this->transformed_data['Header']['PrimaryApplicantType'] = 'COMMERCIAL';
                        if ($is_primary_customer) {
                            $this->commercialCustomerMapping($customer, 'PrimaryCommercialApplicant');
                        } else {
                            $this->commercialCustomerMapping($customer, 'CommercialCoApplicants');
                            $this->transformed_data['CommercialCoApplicants'] = [$this->transformed_data['CommercialCoApplicants']];
                        }
                    }
                }
            }
        }

        if (!empty($data['inventory'])) {
            foreach ($data['inventory'] as $inventory) {
                if (is_array($inventory)) {
                    $this->inventoryMapping($inventory);
                }
            }
        }

        return $this;
    }

    private function inventoryMapping(array $inventory): void
    {
        $this->transformed_data['Automobile']['StockNumber'] = $inventory['stock_no'];
        $this->transformed_data['Automobile']['VIN'] = $inventory['vin'];
        $this->transformed_data['Automobile']['Odometer'] = $inventory['odometer'];
    }

    private function commercialCustomerMapping(array $customer, String $attribute = 'PrimaryCommercialApplicant'): void
    {
        $this->transformed_data[$attribute] = [];
        $this->transformed_data[$attribute]['CompanyName'] = $customer['company_name'];
        $this->transformed_data[$attribute]['Phone'] = $customer['phone'];
        $this->transformed_data[$attribute]['AdditionalInfo']['Fax'] = $customer['fax'];

        if (!empty($customer['credit_application'])) {
            $this->transformed_data[$attribute]['AdditionalInfo']['PrincipalOwners'] = $customer['credit_application']['principal_owner'];
            $this->transformed_data[$attribute]['AdditionalInfo']['NumberOfEmployees'] = $customer['credit_application']['number_of_employees'];
            $this->transformed_data[$attribute]['AdditionalInfo']['YearsInBusiness'] = $customer['credit_application']['years_in_business'];
            $this->transformed_data[$attribute]['CurrentAddress']['YearsAtAddress'] = $customer['credit_application']['years_at_present_address'];
        }

        $this->transformed_data[$attribute]['CurrentAddress']['SuiteNo'] = $customer['apt'];
        $this->transformed_data[$attribute]['CurrentAddress']['StreetName'] = $customer['street_address'];
        $this->transformed_data[$attribute]['CurrentAddress']['City'] = $customer['city'];
        $this->transformed_data[$attribute]['CurrentAddress']['PostalCode'] = $customer['postal_code'];
        $this->transformed_data[$attribute]['CurrentAddress']['Province'] = $customer['province'];
    }

    private function personalCustomerMapping(array $customer, String $attribute = 'PrimaryApplicant'): void
    {
        $this->transformed_data[$attribute] = [];
        $this->transformed_data[$attribute]['FirstName'] = $customer['first_name'];
        $this->transformed_data[$attribute]['LastName'] = $customer['last_name'];
        $this->transformed_data[$attribute]['Phone'] = $customer['phone'];
        $this->transformed_data[$attribute]['MobilePhone'] = $customer['mobile'];
        $this->transformed_data[$attribute]['Email'] = $customer['email'];
        $this->transformed_data[$attribute]['AdditionalInfo']['DateOfBirth'] = $customer['date_of_birth'];


        if (!empty($customer['credit_application'])) {

            $this->transformed_data[$attribute]['SIN'] = $customer['credit_application']['sin'];
            $this->transformed_data[$attribute]['AdditionalInfo']['Salutation'] = $customer['credit_application']['salutation'];
            $this->transformed_data[$attribute]['AdditionalInfo']['Gender'] = $customer['credit_application']['gender'];
            $this->transformed_data[$attribute]['AdditionalInfo']['MaritalStatus'] = $customer['credit_application']['marital_status'];

            $this->transformed_data[$attribute]['IncomeDetails']['GrossIncomeAmount'] = $customer['credit_application']['annual_gross'];
            $this->transformed_data[$attribute]['IncomeDetails']['OtherIncomeAmount'] = $customer['credit_application']['other_annual_gross'];
            $this->transformed_data[$attribute]['IncomeDetails']['OtherIncomeDescription'] = $customer['credit_application']['other_income_source'];

            $this->transformed_data[$attribute]['HomeMortgageDetails']['PropertyValue'] = $customer['credit_application']['market_value'];
            $this->transformed_data[$attribute]['HomeMortgageDetails']['HomeMonthlyPayment'] = $customer['credit_application']['monthly_payment'];
            $this->transformed_data[$attribute]['HomeMortgageDetails']['HomeMortgageAmount'] = $customer['credit_application']['mortgage_amount'];
            $this->transformed_data[$attribute]['HomeMortgageDetails']['HomeMortgageOwnership'] = $customer['credit_application']['property_owner'];
            $this->transformed_data[$attribute]['HomeMortgageDetails']['HomeOwnershipType'] = $customer['credit_application']['residence_ownership'];

            $this->transformed_data[$attribute]['CurrentAddress']['YearsAtAddress'] = $customer['credit_application']['years_at_present_address'];

            $this->transformed_data[$attribute]['PreviousAddress']['YearsAtAddress'] = $customer['credit_application']['previous_residence_address_period_years'];
            $this->transformed_data[$attribute]['PreviousAddress']['MonthsAtAddress'] = $customer['credit_application']['previous_residence_period_months'];
            $this->transformed_data[$attribute]['PreviousAddress']['StreetName'] = $customer['credit_application']['previous_address'];
            $this->transformed_data[$attribute]['PreviousAddress']['City'] = $customer['credit_application']['previous_address_city'];
            $this->transformed_data[$attribute]['PreviousAddress']['PostalCode'] = $customer['credit_application']['previous_address_city'];
            $this->transformed_data[$attribute]['PreviousAddress']['Province'] = $customer['credit_application']['previous_address_province'];

            $this->transformed_data[$attribute]['CurrentEmployment']['EmployerName'] = $customer['credit_application']['employer_name'];
            $this->transformed_data[$attribute]['CurrentEmployment']['EmploymentType'] = $customer['credit_application']['employment_type'];
            $this->transformed_data[$attribute]['CurrentEmployment']['Occupation'] = $customer['credit_application']['occupation'];
            $this->transformed_data[$attribute]['CurrentEmployment']['EmployerPhone'] = $customer['credit_application']['employer_work_phone'];
            $this->transformed_data[$attribute]['CurrentEmployment']['EmployerPhoneExt'] = $customer['credit_application']['employer_work_phone_ext'];
            $this->transformed_data[$attribute]['CurrentEmployment']['EmployerDurationYears'] = $customer['credit_application']['employment_period_years'];
            $this->transformed_data[$attribute]['CurrentEmployment']['EmployerDurationMonths'] = $customer['credit_application']['employment_period_months'];

            $this->transformed_data[$attribute]['CurrentEmployment']['Address']['YearsAtAddress'] = $customer['credit_application']['employment_period_years'];
            $this->transformed_data[$attribute]['CurrentEmployment']['Address']['MonthsAtAddress'] = $customer['credit_application']['employment_period_months'];
            $this->transformed_data[$attribute]['CurrentEmployment']['Address']['StreetName'] = $customer['credit_application']['employer_address'];
            $this->transformed_data[$attribute]['CurrentEmployment']['Address']['City'] = $customer['credit_application']['employer_city'];
            $this->transformed_data[$attribute]['CurrentEmployment']['Address']['PostalCode'] = $customer['credit_application']['employer_postal_code'];
            $this->transformed_data[$attribute]['CurrentEmployment']['Address']['Province'] = $customer['credit_application']['employer_province'];

            $this->transformed_data[$attribute]['PreviousEmployment']['EmployerName'] = $customer['credit_application']['previous_employer_name'];
            $this->transformed_data[$attribute]['PreviousEmployment']['EmploymentType'] = $customer['credit_application']['previous_employment_type'];
            $this->transformed_data[$attribute]['PreviousEmployment']['Occupation'] = $customer['credit_application']['previous_occupation'];
            $this->transformed_data[$attribute]['PreviousEmployment']['EmployerPhone'] = $customer['credit_application']['previous_employer_work_phone'];
            $this->transformed_data[$attribute]['PreviousEmployment']['EmployerPhoneExt'] = $customer['credit_application']['previous_employer_work_phone_ext'];
            $this->transformed_data[$attribute]['PreviousEmployment']['EmployerDurationYears'] = $customer['credit_application']['previous_employment_period_years'];
            $this->transformed_data[$attribute]['PreviousEmployment']['EmployerDurationMonths'] = $customer['credit_application']['previous_employment_period_months'];

            $this->transformed_data[$attribute]['PreviousEmployment']['Address']['YearsAtAddress'] = $customer['credit_application']['previous_employment_period_years'];
            $this->transformed_data[$attribute]['PreviousEmployment']['Address']['MonthsAtAddress'] = $customer['credit_application']['previous_employment_period_months'];
            $this->transformed_data[$attribute]['PreviousEmployment']['Address']['StreetName'] = $customer['credit_application']['previous_employer_address'];
            $this->transformed_data[$attribute]['PreviousEmployment']['Address']['City'] = $customer['credit_application']['previous_employer_city'];
            $this->transformed_data[$attribute]['PreviousEmployment']['Address']['PostalCode'] = $customer['credit_application']['previous_employer_postal_code'];
            $this->transformed_data[$attribute]['PreviousEmployment']['Address']['Province'] = $customer['credit_application']['previous_employer_province'];
        }

        $this->transformed_data[$attribute]['CurrentAddress']['SuiteNo'] = $customer['apt'];
        $this->transformed_data[$attribute]['CurrentAddress']['StreetName'] = $customer['street_address'];
        $this->transformed_data[$attribute]['CurrentAddress']['City'] = $customer['city'];
        $this->transformed_data[$attribute]['CurrentAddress']['PostalCode'] = $customer['postal_code'];
        $this->transformed_data[$attribute]['CurrentAddress']['Province'] = $customer['province'];
    }

    public function send(): array
    {
        try {

            if (empty($this->dealer_id) || empty($this->dealer_confirm_id)) {
                return [
                    'errors' => 'Dealer ID or Dealer Confirmation Id is missing'
                ];
            } else {
                $this->transformed_data['Header']['DealerId'] = $this->dealer_id;
                $this->transformed_data['Header']['DealerConfirmId'] = $this->dealer_confirm_id;
                $auth_token_response = $this->getAuthToken();

                if (empty($auth_token_response['access_token'])) {
                    throw new \Exception("No Access Token", 1);
                }

                $access_token = $auth_token_response['access_token'];
                $response = $this->request($access_token);
                return $response;
            }
        } catch (\Exception $ex) {
            return ['errors' => $ex->getMessage()];
        }
    }


    public function setDealer_id(string $dealer_id): self
    {
        $this->dealer_id = $dealer_id;

        return $this;
    }

    public function setDealer_confirm_id(string $dealer_confirm_id): self
    {
        $this->dealer_confirm_id = $dealer_confirm_id;

        return $this;
    }

    private function getAuthToken(): array
    {
        try {

            $curl = curl_init();

            $authorization = getenv('dealertrack.dms.auth.user') . ':' .  getenv('dealertrack.dms.auth.password');
            $authorization = base64_encode($authorization);

            curl_setopt_array($curl, array(
                CURLOPT_URL => getenv('dealertrack.dms.auth.url'),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded',
                    'Authorization: Basic ' . $authorization,
                ),
            ));

            $response = curl_exec($curl);
            if ($response === false) {
                return ['errors' => curl_error($curl)];
            }

            curl_close($curl);

            $response = json_decode($response, true);

            if (isset($response['error'])) {
                throw new \Exception($response['error'], 1);
            }

            if (empty($response['access_token'])) {
                throw new \Exception("Could not find access token", 1);
            }

            return $response;
        } catch (\Exception $ex) {
            return ['errors' => $ex->getMessage()];
        }
    }

    private function request(string $token): array
    {
        try {
            $curl = curl_init();

            $client_cert = dirname(__FILE__) . "/Zop_DealerTrack.cer";
            $client_key = dirname(__FILE__) . "/server.key";

            curl_setopt_array($curl, array(
                CURLOPT_URL => getenv('dealertrack.dms.transfer.url'),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($this->transformed_data),
                CURLOPT_HTTPHEADER => array(
                    'x-api-key: ' . getenv('dealertrack.dms.transfer.key'),
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $token,
                ),
            ));

            curl_setopt($curl, CURLOPT_SSLKEY, $client_key);
            curl_setopt($curl, CURLOPT_SSLCERT, $client_cert);

            $response = curl_exec($curl);

            if ($response === false) {
                return ['errors' => curl_error($curl)];
            }

            curl_close($curl);

            return json_decode($response, true);
        } catch (\Exception $ex) {
            return ['errors' => $ex->getMessage()];
        }
    }
}
