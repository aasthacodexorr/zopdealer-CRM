<?php

namespace Tenant\app\Libraries\PdfFiller\Version1;

use App\Helpers\Utility;
use Carbon\Carbon;
use Tenant\app\Libraries\PdfFiller\PDFFillerAbstract;
use mikehaertl\pdftk\Pdf;
use Tenant\app\Libraries\PdfFiller\PDFFillerInterface;

class CreditApplication extends BillOfSaleRetail implements PDFFillerInterface
{
    public function __construct(String $template_name)
    {
        $this->template_name = $template_name;
        $this->pdf_driver = new Pdf($template_name);
        $this->company_as_name = false;
    }


    public function dataMap(array $data)
    {
        // parent::dataMap($data);

        if (!empty($data['customers'])) {
            foreach ($data['customers'] as $customer) {
                if (is_array($customer)) {
                    if ($customer['deal_customer_type'] == 'primary_customer') {

                        $primary_customer_mapping = [
                            'primary_customer_first_name' => 'first_name',
                            'primary_customer_last_name'  =>  'last_name',
                            //  'primary_customer_date_of_birth' => 'date_of_birth',
                            'primary_customer_sin'  => 'sin',
                            'primary_customer_driver_licence' => 'driver_licence',
                            'primary_customer_phone' => 'phone',
                            'primary_customer_mobile'  => 'mobile',
                            'primary_customer_work_phone'  => 'work_phone',
                            'primary_customer_email'  => 'email',

                            'primary_customer_marital_status'   =>  'marital_status',
                            'primary_customer_street_address'   =>  'street_address',
                            'primary_customer_city'     =>  'city',
                            'primary_customer_province' =>  'province',
                            'primary_customer_postal_code' =>  'postal_code',

                            'primary_customer_residence_period_years'   =>  'years_at_present_address',
                            'primary_customer_status'  => 'residence_ownership',
                            'primary_customer_monthly_payment'  => 'monthly_payment',
                            'primary_customer_mortgage_amount'  => 'mortgage_amount',
                            'primary_customer_market_value'  => 'market_value',
                            'primary_customer_mortgage_bank'  => 'mortgage_bank',
                            
                            'primary_customer_previous_address' => 'previous_address',
                            'primary_customer_previous_address_city'    => 'previous_address_city',
                            'primary_customer_previous_address_province'   => 'previous_address_province',
                            'primary_customer_previous_address_postal_code' =>  'previous_address_postal_code',
                            'primary_customer_previous_residence_address_period_years'  =>  'previous_residence_address_period_years',
                            'primary_customer_previous_residence_period_months'  => 'primary_customer_previous_residence_period_months',

                            //   'primary_customer_previous_residence_status'  => 'previous_residence_status',
                            'primary_customer_previous_residence_period'   => 'previous_residence_address_period_years',
                            'primary_customer_previous_residence_monthly_payment'    => 'previous_residence_monthly_payment',

                            'primary_customer_employer_name' => 'employer_name',
                            // 'primary_customer_self_employed' => 'self_employed',
                            'primary_customer_employer_work_phone'  => 'employer_work_phone',
                            'primary_customer_job_title'    => 'job_title',
                            'primary_customer_employer_address' => 'employer_address',
                            'primary_customer_employer_city'    => 'employer_city',
                            'primary_customer_employer_province' => 'employer_province',
                            'primary_customer_employer_postal_code' => 'employer_postal_code',
                            'primary_customer_employment_period_years' => 'employment_period_years',
                            'primary_customer_employment_period_months' => 'employment_period_months',
                            'primary_customer_occupation' => 'occupation',

                            'primary_customer_employment_period'    => 'employment_period_years',

                            'primary_customer_monthly_gross' => 'monthly_gross',
                            // 'primary_customer_income_frequency' => 'income_frequency',

                            'primary_customer_other_income_source'    => 'other_income_source',
                            'primary_customer_other_monthly_gross'    => 'other_monthly_gross',

                            // 'primary_customer_other_income_frequency'  => 'other_income_frequency',

                            'primary_customer_previous_employer_name' => 'previous_employer_name',
                            // 'primary_customer_previous_self_employed' => 'previous_self_employed',
                            'primary_customer_previous_employer_work_phone' => 'previous_employer_work_phone',
                            'primary_customer_previous_job_title' => 'previous_job_title',
                            'primary_customer_previous_employer_address' => 'previous_employer_address',
                            'primary_customer_previous_employer_city' => 'previous_employer_city',
                            'primary_customer_previous_employer_province'  => 'previous_employer_province',
                            'primary_customer_previous_employer_postal_code' => 'previous_employer_postal_code',
                            'primary_customer_previous_employment_period_years' => 'previous_employment_period_years',
                            'primary_customer_previous_employment_period_months' => 'previous_employment_period_months',
                            'primary_customer_previous_employment_period' => 'previous_employment_period_years',

                            'primary_customer_previous_monthly_gross' => 'previous_monthly_gross',
                            //'primary_customer_previous_income_frequency' => 'previous_income_frequency',
                        ];


                        foreach ($primary_customer_mapping as $key => $value) {
                            if (isset($customer[$value])) {
                                if ($key == "mobile" || $key == "work_phone" || $key == "phone") {
                                    $this->data[$key] = Utility::format_phone_us($value);
                                    $this->data[$key . '_1'] = Utility::format_phone_us($value);
                                    $this->data[$key . '_2'] = Utility::format_phone_us($value);
                                    $this->data[$key . '_3'] = Utility::format_phone_us($value);
                                    $this->data[$key . '_4'] = Utility::format_phone_us($value);
                                    $this->data[$key . '_5'] = Utility::format_phone_us($value);
                                } else {
                                    $this->data[$key] = $customer[$value];
                                    $this->data[$key . '_1'] = $customer[$value];
                                    $this->data[$key . '_2'] = $customer[$value];
                                    $this->data[$key . '_3'] = $customer[$value];
                                    $this->data[$key . '_4'] = $customer[$value];
                                    $this->data[$key . '_5'] = $customer[$value];
                                }
                            }
                        }

                        $this->data['primary_customer_full_name'] =
                        $this->data['primary_customer_full_name_1'] =
                        $this->data['primary_customer_full_name_2'] =
                        $this->data['primary_customer_full_name_3'] = $customer['first_name'] . ' ' . $customer['last_name'];

                        if (is_array($customer['credit_application']) && !empty($customer['credit_application'])) {
                            foreach ($primary_customer_mapping as $key => $value) {
                                if (isset($customer['credit_application'][$value])) {

                                    if ($key == "primary_customer_previous_residence_period" && !empty($customer['credit_application'][$value])) {
                                        $this->data[$key] = $this->data[$key .'_1'] = $this->data[$key .'_2'] = $this->data[$key .'_3'] = $customer['credit_application'][$value] . " Years";
                                        $months = $customer['credit_application']['previous_residence_period_months'];
                                        if (!empty($months)) {
                                            $this->data[$key] .= " " . $months . " Months";
                                            $this->data[$key .'_1'] .= " " . $months . " Months";
                                            $this->data[$key .'_2'] .= " " . $months . " Months";
                                            $this->data[$key .'_3'] .= " " . $months . " Months";
                                        }
                                    } else if ($key == "primary_customer_employment_period" && !empty($customer['credit_application'][$value])) {
                                        $this->data[$key] = $this->data[$key .'_1'] = $this->data[$key .'_2'] = $this->data[$key .'_3'] = $customer['credit_application'][$value] . " Years";
                                        $months = $customer['credit_application']['employment_period_months'];
                                        if (!empty($months)) {
                                            $this->data[$key] .= " " . $months . " Months";
                                            $this->data[$key .'_1'] .= " " . $months . " Months";
                                            $this->data[$key .'_2'] .= " " . $months . " Months";
                                            $this->data[$key .'_3'] .= " " . $months . " Months";
                                        }
                                    } else if ($key == "primary_customer_previous_employment_period" && !empty($customer['credit_application'][$value])) {
                                        $this->data[$key] = $this->data[$key .'_1'] = $this->data[$key .'_2'] = $this->data[$key .'_3'] = $customer['credit_application'][$value] . " Years";
                                        $months = $customer['credit_application']['previous_employment_period_months'];
                                        if (!empty($months)) {
                                            $this->data[$key] .= " " . $months . " Months";
                                            $this->data[$key .'_1'] .= " " . $months . " Months";
                                            $this->data[$key .'_2'] .= " " . $months . " Months";
                                            $this->data[$key .'_3'] .= " " . $months . " Months";
                                        }
                                    } else if ($key == "primary_customer_previous_employer_work_phone") {
                                        $this->data[$key] = $this->data[$key .'_1'] = $this->data[$key .'_2'] = $this->data[$key .'_3'] = Utility::format_phone_us($customer['credit_application'][$value]);
                                        if (!empty($customer['credit_application']['previous_employer_work_phone_ext'])) {
                                            $this->data[$key] .= " ext" . $customer['credit_application']['previous_employer_work_phone_ext'];
                                            $this->data[$key .'_1'] .= " ext" . $customer['credit_application']['previous_employer_work_phone_ext'];
                                           $this->data[$key .'_2'] .= " ext" . $customer['credit_application']['previous_employer_work_phone_ext'];
                                           $this->data[$key .'_3'] .= " ext" . $customer['credit_application']['previous_employer_work_phone_ext'];
                                        }
                                    } else if ($key == "primary_customer_employer_work_phone") {
                                        $this->data[$key] = $this->data[$key .'_1'] = $this->data[$key .'_2'] = $this->data[$key .'_3'] = Utility::format_phone_us($customer['credit_application'][$value]);
                                        if (!empty($customer['credit_application']['employer_work_phone_ext'])) {
                                            $this->data[$key] .= " ext" . $customer['credit_application']['employer_work_phone_ext'];
                                            $this->data[$key .'_1'] .= " ext" . $customer['credit_application']['employer_work_phone_ext'];
                                            $this->data[$key .'_2'] .= " ext" . $customer['credit_application']['employer_work_phone_ext'];
                                            $this->data[$key .'_3'] .= " ext" . $customer['credit_application']['employer_work_phone_ext'];
                                        }
                                    } else {
                                        $this->data[$key] = $this->data[$key .'_1'] = $this->data[$key .'_2'] = $this->data[$key .'_3'] = $customer['credit_application'][$value];
                                    }
                                }
                            }
                        }

                        if (isset($customer['date_of_birth']) && strtotime($customer['date_of_birth'])) {
                            $this->data['primary_customer_date_of_birth'] = $this->data['primary_customer_date_of_birth_1'] = Carbon::parse($customer['date_of_birth'])->format('m/d/Y');
                            $this->data['primary_customer_date_of_birth_ist_format'] = Carbon::parse($customer['date_of_birth'])->format(ZD_IST_DATE_FORMAT);
                        }

                        if (isset($customer['driver_licence_expiry_date']) && strtotime($customer['driver_licence_expiry_date'])) {
                            $this->data['primary_customer_driver_licence_expiry_date'] = $this->data['primary_customer_driver_licence_expiry_date_1'] = Carbon::parse($customer['driver_licence_expiry_date'])->format('m/d/Y');
                            $this->data['primary_customer_driver_licence_expiry_date_ist_format'] = Carbon::parse($customer['driver_licence_expiry_date'])->format(ZD_IST_DATE_FORMAT);
                        }
                    } else {
                        $secondary_customer_mapping = [
                            'secondary_customer_first_name' => 'first_name',
                            'secondary_customer_last_name'  =>  'last_name',
                            //  'secondary_customer_date_of_birth' => 'date_of_birth',
                            'secondary_customer_sin'  => 'sin',
                            'secondary_customer_driver_licence' => 'driver_licence',
                            'secondary_customer_phone' => 'phone',
                            'secondary_customer_mobile'  => 'mobile',
                            'secondary_customer_work_phone'  => 'work_phone',
                            'secondary_customer_email'  => 'email',

                            'secondary_customer_marital_status'   =>  'marital_status',
                            'secondary_customer_street_address'   =>  'street_address',
                            'secondary_customer_city'     =>  'city',
                            'secondary_customer_province' =>  'province',
                            'secondary_customer_postal_code' =>  'postal_code',

                            'secondary_customer_residence_period_years'   =>  'years_at_present_address',
                            'secondary_customer_status'  => 'residence_ownership',
                            'secondary_customer_monthly_payment'  => 'monthly_payment',
                            'secondary_customer_mortgage_amount'  => 'mortgage_amount',
                            'secondary_customer_market_value'  => 'market_value',
                            'secondary_customer_mortgage_bank'  => 'mortgage_bank',
                            'secondary_customer_previous_address' => 'previous_address',
                            'secondary_customer_previous_address_city'    => 'previous_address_city',
                            'secondary_customer_previous_address_province'   => 'previous_address_province',
                            'secondary_customer_previous_address_postal_code' =>  'previous_address_postal_code',
                            'secondary_customer_previous_residence_address_period_years'  =>  'previous_residence_address_period_years',
                            'secondary_customer_previous_residence_period_months'  => 'secondary_customer_previous_residence_period_months',

                            //   'secondary_customer_previous_residence_status'  => 'previous_residence_status',
                            'secondary_customer_previous_residence_period'   => 'previous_residence_address_period_years',
                            'secondary_customer_previous_residence_monthly_payment'    => 'previous_residence_monthly_payment',

                            'secondary_customer_employer_name' => 'employer_name',
                            // 'secondary_customer_self_employed' => 'self_employed',
                            'secondary_customer_employer_work_phone'  => 'employer_work_phone',
                            'secondary_customer_job_title'    => 'job_title',
                            'secondary_customer_employer_address' => 'employer_address',
                            'secondary_customer_employer_city'    => 'employer_city',
                            'secondary_customer_employer_province' => 'employer_province',
                            'secondary_customer_employer_postal_code' => 'employer_postal_code',
                            'secondary_customer_employment_period_years' => 'employment_period_years',
                            'secondary_customer_employment_period_months' => 'employment_period_months',
                            'secondary_customer_occupation' => 'occupation',

                            'secondary_customer_employment_period'    => 'employment_period_years',

                            'secondary_customer_monthly_gross' => 'monthly_gross',
                            // 'secondary_customer_income_frequency' => 'income_frequency',

                            'secondary_customer_other_income_source'    => 'other_income_source',
                            'secondary_customer_other_monthly_gross'    => 'other_monthly_gross',

                            // 'secondary_customer_other_income_frequency'  => 'other_income_frequency',

                            'secondary_customer_previous_employer_name' => 'previous_employer_name',
                            // 'secondary_customer_previous_self_employed' => 'previous_self_employed',
                            'secondary_customer_previous_employer_work_phone' => 'previous_employer_work_phone',
                            'secondary_customer_previous_job_title' => 'previous_job_title',
                            'secondary_customer_previous_employer_address' => 'previous_employer_address',
                            'secondary_customer_previous_employer_city' => 'previous_employer_city',
                            'secondary_customer_previous_employer_province'  => 'previous_employer_province',
                            'secondary_customer_previous_employer_postal_code' => 'previous_employer_postal_code',
                            'secondary_customer_previous_employment_period_years' => 'previous_employment_period_years',
                            'secondary_customer_previous_employment_period_months' => 'previous_employment_period_months',
                            'secondary_customer_previous_employment_period' => 'previous_employment_period_years',

                            'secondary_customer_previous_monthly_gross' => 'previous_monthly_gross',
                            //'secondary_customer_previous_income_frequency' => 'previous_income_frequency',
                        ];


                        foreach ($secondary_customer_mapping as $key => $value) {
                            if (isset($customer[$value])) {
                                if ($key == "mobile" || $key == "work_phone" || $key == "phone") {
                                    $this->data[$key] = Utility::format_phone_us($value);
                                    $this->data[$key . '_1'] = Utility::format_phone_us($value);
                                    $this->data[$key . '_2'] = Utility::format_phone_us($value);
                                    $this->data[$key . '_3'] = Utility::format_phone_us($value);
                                    $this->data[$key . '_4'] = Utility::format_phone_us($value);
                                    $this->data[$key . '_5'] = Utility::format_phone_us($value);
                                } else {
                                    $this->data[$key] = $customer[$value];
                                    $this->data[$key . '_1'] = $customer[$value];
                                    $this->data[$key . '_2'] = $customer[$value];
                                    $this->data[$key . '_3'] = $customer[$value];
                                    $this->data[$key . '_4'] = $customer[$value];
                                    $this->data[$key . '_5'] = $customer[$value];
                                }
                            }
                        }

                        if (is_array($customer['credit_application']) && !empty($customer['credit_application'])) {
                            foreach ($secondary_customer_mapping as $key => $value) {
                                if (isset($customer['credit_application'][$value])) {

                                    if ($key == "secondary_customer_previous_residence_period" && !empty($customer['credit_application'][$value])) {
                                        $this->data[$key] = $this->data[$key .'_1'] = $this->data[$key .'_2'] = $this->data[$key .'_3'] = $customer['credit_application'][$value] . " Years";
                                        $months = $customer['credit_application']['previous_residence_period_months'];
                                        if (!empty($months)) {
                                            $this->data[$key] .= " " . $months . " Months";
                                            $this->data[$key .'_1'] .= " " . $months . " Months";
                                            $this->data[$key .'_2'] .= " " . $months . " Months";
                                            $this->data[$key .'_3'] .= " " . $months . " Months";
                                        }
                                    } else if ($key == "secondary_customer_employment_period" && !empty($customer['credit_application'][$value])) {
                                        $this->data[$key] = $this->data[$key .'_1'] = $this->data[$key .'_2'] = $this->data[$key .'_3'] = $customer['credit_application'][$value] . " Years";
                                        $months = $customer['credit_application']['employment_period_months'];
                                        if (!empty($months)) {
                                            $this->data[$key] .= " " . $months . " Months";
                                            $this->data[$key .'_1'] .= " " . $months . " Months";
                                            $this->data[$key .'_2'] .= " " . $months . " Months";
                                            $this->data[$key .'_3'] .= " " . $months . " Months";
                                        }
                                    } else if ($key == "secondary_customer_previous_employment_period" && !empty($customer['credit_application'][$value])) {
                                        $this->data[$key] = $this->data[$key .'_1'] = $this->data[$key .'_2'] = $this->data[$key .'_3'] = $customer['credit_application'][$value] . " Years";
                                        $months = $customer['credit_application']['previous_employment_period_months'];
                                        if (!empty($months)) {
                                            $this->data[$key] .= " " . $months . " Months";
                                            $this->data[$key .'_1'] .= " " . $months . " Months";
                                            $this->data[$key .'_2'] .= " " . $months . " Months";
                                            $this->data[$key .'_3'] .= " " . $months . " Months";
                                        }
                                    } else if ($key == "secondary_customer_previous_employer_work_phone") {
                                        $this->data[$key] = $this->data[$key .'_1'] = $this->data[$key .'_2'] = $this->data[$key .'_3'] = Utility::format_phone_us($customer['credit_application'][$value]);
                                        if (!empty($customer['credit_application']['previous_employer_work_phone_ext'])) {
                                            $this->data[$key] .= " ext" . $customer['credit_application']['previous_employer_work_phone_ext'];
                                            $this->data[$key .'_1'] .= " ext" . $customer['credit_application']['previous_employer_work_phone_ext'];
                                            $this->data[$key .'_2'] .= " ext" . $customer['credit_application']['previous_employer_work_phone_ext'];
                                            $this->data[$key .'_3'] .= " ext" . $customer['credit_application']['previous_employer_work_phone_ext'];
                                        }
                                    } else if ($key == "secondary_customer_employer_work_phone") {
                                        $this->data[$key] = $this->data[$key .'_1'] = $this->data[$key .'_2'] = $this->data[$key .'_3'] = Utility::format_phone_us($customer['credit_application'][$value]);
                                        if (!empty($customer['credit_application']['employer_work_phone_ext'])) {
                                            $this->data[$key] .= " ext" . $customer['credit_application']['employer_work_phone_ext'];
                                            $this->data[$key .'_1'] .= " ext" . $customer['credit_application']['employer_work_phone_ext'];
                                            $this->data[$key .'_2'] .= " ext" . $customer['credit_application']['employer_work_phone_ext'];
                                            $this->data[$key .'_3'] .= " ext" . $customer['credit_application']['employer_work_phone_ext'];
                                        }
                                    } else {
                                        $this->data[$key] = $this->data[$key .'_1'] = $this->data[$key .'_2'] = $this->data[$key .'_3'] = $customer['credit_application'][$value];
                                    }
                                }
                            }
                        }

                        if (isset($customer['date_of_birth']) && strtotime($customer['date_of_birth'])) {
                            $this->data['secondary_customer_date_of_birth'] = $this->data['secondary_customer_date_of_birth_1'] = Carbon::parse($customer['date_of_birth'])->format('m/d/Y');
                            $this->data['secondary_customer_date_of_birth_ist_format'] = Carbon::parse($customer['date_of_birth'])->format(ZD_IST_DATE_FORMAT);
                        }

                        if (isset($customer['driver_licence_expiry_date']) && strtotime($customer['driver_licence_expiry_date'])) {
                            $this->data['secondary_customer_driver_licence_expiry_date'] = $this->data['secondary_customer_driver_licence_expiry_date_1'] = Carbon::parse($customer['driver_licence_expiry_date'])->format('m/d/Y');
                            $this->data['secondary_customer_driver_licence_expiry_date_ist_format'] = Carbon::parse($customer['driver_licence_expiry_date'])->format(ZD_IST_DATE_FORMAT);
                        }


                    }
                }
            }
        }

        parent::dataMap($data);
        return $this;
    }
}
