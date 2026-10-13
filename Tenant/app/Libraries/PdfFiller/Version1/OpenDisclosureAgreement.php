<?php

namespace Tenant\app\Libraries\PdfFiller\Version1;

use App\Helpers\Utility;
use Carbon\Carbon;
use Tenant\app\Libraries\PdfFiller\PDFFillerAbstract;
use mikehaertl\pdftk\Pdf;
use Tenant\app\Libraries\PdfFiller\PDFFillerInterface;

class OpenDisclosureAgreement extends PDFFillerAbstract implements PDFFillerInterface
{
    public function __construct(String $template_name)
    {
        $this->template_name = $template_name;
        $this->pdf_driver = new Pdf($template_name);
        $this->company_as_name = true;
    }

    public function dataMap(array $data)
    {
        if (!empty($data['date'])) {
            if (strtotime($data['date'])) {
                $this->data['deal_day'] = Carbon::parse($data['date'])->format('d');
                $this->data['deal_month'] = Carbon::parse($data['date'])->format('m');
                $this->data['deal_year'] = Carbon::parse($data['date'])->format('Y');
                $this->data['deal_date'] = Carbon::parse($data['date'])->format('m/d/Y');
                $this->data['deal_date_1'] = Carbon::parse($data['date'])->format('m/d/Y');
                $this->data['deal_date_2'] = Carbon::parse($data['date'])->format('m/d/Y');

                $this->data['deal_date_ist_format'] = Carbon::parse($data['date'])->format(ZD_IST_DATE_FORMAT);
                $this->data['deal_date_1_ist_format'] = Carbon::parse($data['date'])->format(ZD_IST_DATE_FORMAT);
                $this->data['deal_date_2_ist_format'] = Carbon::parse($data['date'])->format(ZD_IST_DATE_FORMAT);
            }
        }

        if (!empty($data['inventory'])) {
            foreach ($data['inventory'] as $inventory) {
                if (is_array($inventory)) {
                    $this->data['inventory_make'] = $inventory['make'];
                    $this->data['inventory_year'] = $inventory['year'];
                    $this->data['inventory_stock_no'] = $inventory['stock_no'];
                    $this->data['inventory_model'] = $inventory['model'];
                    $this->data['inventory_color'] = $inventory['exterior_color'];
                    $this->data['inventory_trim'] = $inventory['trim'];
                    $this->data['inventory_fuel_type'] = $inventory['fuel_type'];
                    $this->data['inventory_odometer'] = $inventory['odometer'];
                    $this->data['inventory_engine'] = $inventory['litres'];
                    if (!empty($inventory['km_miles'])) {
                        $this->data['inventory_km_miles'] = $inventory['km_miles'];
                    }
                    if (!empty($inventory['vin'])) {
                        $this->data['inventory_vin'] = $inventory['vin'];
                    }
                }
            }
        }


        $this->data['purchaser_full_name'] = "";

        if (!empty($data['customers'])) {
            foreach ($data['customers'] as $customer) {
                if (is_array($customer)) {
                    if ($customer['deal_customer_type'] == 'primary_customer') {

                        if (!empty($customer['customer_insurance'])) {
                            if (is_array($customer['customer_insurance'])) {
                                $this->data['customer_insurance_company_name'] = $customer['customer_insurance']['company_name'];
                                $this->data['customer_insurance_policy_number'] = $customer['customer_insurance']['policy_number'];
                                $this->data['customer_insurance_agent_name'] = $customer['customer_insurance']['agent_name'];
                                $this->data['customer_insurance_agent_phone'] = $customer['customer_insurance']['phone'] . ' ext ' . $customer['customer_insurance']['phone_ext'];

                                $this->data['customer_insurance_agent_details'] = $customer['customer_insurance']['agent_name'] . ' ' .  $customer['customer_insurance']['phone'] . ' ext ' . $customer['customer_insurance']['phone_ext'];
                                if (strtotime($customer['customer_insurance']['policy_expiry_date'])) {
                                    $this->data['customer_insurance_policy_expiry_date'] = Carbon::parse($customer['customer_insurance']['policy_expiry_date'])->format('m/d/Y');
                                    $this->data['customer_insurance_policy_expiry_date_ist_format'] = Carbon::parse($customer['customer_insurance']['policy_expiry_date'])->format(ZD_IST_DATE_FORMAT);
                                }
                            }
                        }
                        $this->data['purchaser_mvda'] = $customer['mvda'];
                        $this->data['first_name'] = $customer['first_name'];
                        $this->data['purchaser_first_name'] = $customer['first_name'];
                        $this->data['purchaser_last_name'] = $customer['last_name'];
                        $this->data['last_name'] = $customer['last_name'];
                        $this->data['purchaser_full_name'] = $customer['first_name'] . ' ' . $customer['last_name'];
                        $this->data['purchaser_company_name'] = $customer['company_name'];
                        $this->data['purchaser_customer_type'] = $customer['customer_type'];
                        $this->data['purchaser_company_rin'] = $customer['rin'];
                        $this->data['purchaser_address'] = $customer['apt'] . ' ' . $customer['street_address'];
                        $this->data['purchaser_city'] = $customer['city'];
                        $this->data['purchaser_province'] = $customer['province'];
                        $this->data['purchaser_postal_code'] = $customer['postal_code'];
                        $this->data['purchaser_phone'] = Utility::format_phone_us($customer['phone']);
                        $this->data['purchaser_fax'] = Utility::format_phone_us($customer['fax']);
                        $this->data['purchaser_hst_registration'] = $customer['tax_number'];
                        $this->data['purchaser_salesperson_name'] = $customer['last_name'] . ' ' . $customer['first_name'];
                        $this->data['purchaser_registration_no'] = $customer['sales_person_registration'];
                        $this->data['purchaser_driver_licence'] = $customer['driver_licence'];
                        $this->data['purchaser_email'] = $customer['email'];
                        $this->data['purchaser_mobile'] = Utility::format_phone_us($customer['mobile']);

                        if (strtotime($customer['date_of_birth'])) {
                            $this->data['purchaser_date_of_birth'] = Carbon::parse($customer['date_of_birth'])->format('m/d/Y');
                            $this->data['purchaser_date_of_birth_ist_format'] = Carbon::parse($customer['date_of_birth'])->format(ZD_IST_DATE_FORMAT);
                        }

                        if (strtotime($customer['driver_licence_expiry_date'])) {
                            $this->data['purchaser_driver_licence_expiry_date'] = Carbon::parse($customer['driver_licence_expiry_date'])->format('m/d/Y');
                            $this->data['purchaser_driver_licence_expiry_date_ist_format'] = Carbon::parse($customer['driver_licence_expiry_date'])->format(ZD_IST_DATE_FORMAT);
                        }
                    } else {
                        if ($customer['deal_customer_type'] == 'co-signer') {
                            $this->data['deal_co_signor'] = $customer['first_name'] . ' ' . $customer['last_name'];
                        }
                        $this->data['purchaser_full_name'] .= ' / ' . $customer['first_name'] . ' ' . $customer['last_name'];
                    }
                }
            }
        }

        $this->data['purchaser_full_name_1'] = $this->data['purchaser_full_name'];

        if ($this->company_as_name && !empty($this->data['purchaser_company_name']) && $this->data['purchaser_customer_type'] == '1') {
            $this->data['purchaser_full_name'] = $this->data['purchaser_company_name'];
            $this->data['purchaser_driver_licence'] = $this->data['purchaser_company_rin'];
        }



        if (!empty($data['disclosure'])) {
            $this->data['deal_disclosure_comment'] = $data['disclosure'];
        }

        if (!empty($data['brand'])) {
            $this->data['deal_disclosure_brand_name'] = $data['brand'];
        }



        if (!empty($data['delivery_date_time'])) {
            if (strtotime($data['delivery_date_time'])) {
                $this->data['deal_delivery_date'] = Carbon::parse($data['delivery_date_time'])->format('m/d/Y');
                $this->data['deal_delivery_date_ist_format'] = Carbon::parse($data['delivery_date_time'])->format(ZD_IST_DATE_FORMAT);
            }
        }

        $this->data['deal_warranty_comment'] = 'Declined';
        if (!empty($data['warranties'])) {
            foreach ($data['warranties'] as $warranty) {
                if (is_array($warranty)) {

                    $this->aggregateValues($warranty['tax_type'], (float) $warranty['tax_amount']);
                    $this->aggregateValues($warranty['tax_type_secondary'], (float) $warranty['tax_amount_secondary']);
                    $this->data['deal_warranty_flag'] = 'Yes';
                    $this->data['deal_warranty_company_name'] = $warranty['company_name'];
                    $this->data['deal_warranty_duration'] = $warranty['duration'];
                    $this->data['deal_warranty_distance'] = $warranty['distance'];
                    $this->data['deal_warranty_description'] = '                   ' . $warranty['description'];
                    $this->data['deal_warranty_comment'] = $warranty['description'];
                }
            }
        }

        if (!empty($data['guarantee'])) {
            foreach ($data['guarantee'] as $guarantee) {
                if (is_array($guarantee)) {

                    $this->aggregateValues($guarantee['tax_type'], (float) $guarantee['tax_amount']);
                    $this->aggregateValues($guarantee['tax_type_secondary'], (float) $guarantee['tax_amount_secondary']);
                }
            }
        }


        if (!empty($data['salesperson'])) {
            $salespersons  = $data['salesperson'];
            if (is_array($salespersons)) {
                foreach ($salespersons as $salesperson) {
                    $this->data['salesperson_name'] = $salesperson['name'];
                    $this->data['salesperson_registration_number'] = $salesperson['registration_number'];
                    $this->data['salesperson_title'] = $salesperson['title'];
                }
            }
        }

        if (!empty($data['approved_by'])) {
            $approvers  = $data['approved_by'];
            if (is_array($approvers)) {
                foreach ($approvers as $approver) {
                    $this->data['vendor_name'] = $approver['name'];
                    $this->data['vendor_registration_number'] = $approver['registration_number'];
                    $this->data['vendor_title'] = $approver['title'];
                }
            }
        }




        if (!empty($data['finance'])) {
            foreach ($data['finance'] as $finance) {
                if (is_array($finance) && ($data['type'] == "Financed")) {
                    $this->data['deal_is_financed'] = "Yes";
                    $this->data['deal_finance_payment_amount'] = $finance['emi_amount'] . ' ' . $finance['payment_frequency'];
                    $this->data['deal_finance_lien_holder'] = $finance['lien_holder_name'];
                    $this->data['deal_finance_number_of_payments'] = $finance['term'] . ' Months';
                    //@todo Need to calculate the complete number of payments.
                    if (strtotime($finance['first_payment_date'])) {
                        $this->data['deal_finance_first_payment_date'] = Carbon::parse($finance['first_payment_date'])->format('m/d/Y');
                        $this->data['deal_finance_first_payment_date_ist_format'] = Carbon::parse($finance['first_payment_date'])->format(ZD_IST_DATE_FORMAT);
                    }

                    if (!empty($finance['interest_rate'])) {
                        $this->data['deal_finance_interest'] = $finance['interest_rate'] . ' % ' . $finance['interest_rate_type'];
                        $this->data['deal_finance_interest_rate'] = $finance['interest_rate'];
                        $this->data['deal_finance_interest_rate_1'] = $finance['interest_rate'];
                        $this->data['deal_finance_interest_amount'] = number_format((float)$finance['interest_amount'], 2, '.', ',');
                    }

                    if ($data['type'] == "Financed") {
                        $this->data['deal_finance_total_amount'] = number_format((float) $finance['total_amount'], 2, '.', ',');
                        $this->data['deal_finance_total_amount_1'] = number_format((float) $finance['total_amount'], 2, '.', ',');
                    }
                }
            }
        }

        $this->data['deal_life_insurance_comment'] = 'Declined';
        $this->data['deal_gap_insurance_comment'] = 'Declined';
        $this->data['deal_disability_insurance_comment'] = 'Declined';
        $this->data['deal_income_insurance_comment'] = 'Declined';
        $this->data['deal_sickness_insurance_comment'] = 'Declined';
        $this->data['deal_walkaway_insurance_comment'] = 'Declined';
        $this->data['deal_injury_insurance_comment'] = 'Declined';
        $this->data['deal_other_insurance_comment'] = 'Declined';


        if (!empty($data['insurances'])) {
            if (is_array($data['insurances'])) {
                foreach ($data['insurances'] as $insurance) {
                    $insurance_type = strtolower($insurance['type']);

                    $insurance_type = $insurance_type == "sickness" ? 'injury' : $insurance_type;
                    $this->data['deal_' . $insurance_type . '_insurance_comment'] = $insurance['description'];
                }
            }
        }


        $this->data['complete_business_details'] =
            $data['location']['business_name'] . ",\r\n" .
            $data['location']['street'] . ",\r\n" .
            $data['location']['city'] . ", " . $data['location']['state']  . ", " . $data['location']['zipcode'] . ",\r\n" .
            "Tel : " . Utility::format_phone_us($data['location']['phone']) . ",\r\n" .
            "Email : " . $data['location']['email'] . ",\r\n" .
            "Tax # " . $data['location']['tax_number'];


        if (!empty($data['certified'])) {
            if ($data['certified'] == "Yes" || $data['certified'] == "No") {
                $this->data['safety_certificate_available'] = $data['certified'];
                $this->data['is_vehicle_sold_as_is'] = 'No';
            }
        }


        $this->data['dealer_registration_number'] = $data['location']['dealer_registration_number'];
        $this->data['dealer_tax_number'] = $data['location']['tax_number'];
        $this->data['dealer_name'] = $data['location']['business_name'];
        $this->data['dealer_name_1'] = $data['location']['business_name'];
        $this->data['dealer_email'] = $data['location']['email'];
        $this->data['dealer_phone'] = Utility::format_phone_us($data['location']['phone']);

        if (!empty($data['dealer_website'])) {
            $this->data['dealer_website'] = $data['dealer_website'];
        }

        return $this;
    }

    protected function aggregateValues($attribute, $value)
    {
        $attribute = $attribute . '_tax_total';
        $attribute = strtolower($attribute);

        if (isset($this->data[$attribute])) {
            $this->data[$attribute] += (float) $value;
        } else {
            $this->data[$attribute] = (float) $value;
        }
    }
}
