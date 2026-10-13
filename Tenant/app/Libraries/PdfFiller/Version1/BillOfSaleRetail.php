<?php

namespace Tenant\app\Libraries\PdfFiller\Version1;

use App\Helpers\Utility;
use Carbon\Carbon;
use Tenant\app\Libraries\PdfFiller\PDFFillerAbstract;
use mikehaertl\pdftk\Pdf;
use Tenant\app\Libraries\PdfFiller\PDFFillerInterface;

class BillOfSaleRetail extends PDFFillerAbstract implements PDFFillerInterface
{
    private $campvan_manufactures = [
        "ford",
        "gmc",
        "chevrolet",
        "buick",
        "cadillac",
        "acura",
        "honda",
        "genesis",
        "kia",
        "jaguar",
        "land rover",
        "lucid",
        "mazda",
        "mercedes-benz",
        "nissan",
        "infiniti",
        "porsche",
        "subaru",
        "toyota",
        "lexus",
        "volkswagen",
        "audi",
        "volvo"
    ];

    public function __construct(String $template_name)
    {
        $this->template_name = $template_name;
        $this->pdf_driver = new Pdf($template_name);
        $this->company_as_name = true;
    }

    public function dataMap(array $data)
    {

        $this->data['camvap'] = "No";

        if (!empty($data['id'])) {
            $this->data['context_entity_id'] = $data['id'];
        }


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
                    for ($prefix_count = 0; $prefix_count < 5; $prefix_count++) {

                        $prefix = $prefix_count == 0 ? '' : "_" . $prefix_count;

                        $this->data['inventory_make' . $prefix] = $inventory['make'];
                        $this->data['inventory_year' . $prefix] = $inventory['year'];
                        $this->data['inventory_stock_no' . $prefix] = $inventory['stock_no'];
                        $this->data['inventory_model' . $prefix]  = $inventory['model'];
                        $this->data['inventory_color' . $prefix] = $inventory['exterior_color'];
                        $this->data['inventory_trim' . $prefix] = $inventory['trim'];
                        $this->data['inventory_fuel_type' . $prefix] = $inventory['fuel_type'];
                        $this->data['inventory_odometer' . $prefix] = $inventory['odometer'];
                        $this->data['inventory_engine' . $prefix] = $inventory['litres'];
                        $this->data['inventory_body_type' . $prefix] = $inventory['body_type'];
                        if (!empty($inventory['km_miles'])) {
                            $this->data['inventory_km_miles' . $prefix] = $inventory['km_miles'];
                        }
                        if (!empty($inventory['vin'])) {
                            $this->data['inventory_vin' . $prefix] = $inventory['vin'];
                        }

                        if (in_array(strtolower($this->data['inventory_make']), $this->campvan_manufactures)) {
                            $inventory_year = !empty($inventory['year']) ? intval($inventory['year']) : 0;
                            $current_year = intval(date("Y"));

                            if ($inventory_year > $current_year - 5) {
                                $inventory_odometer = !empty($inventory['odometer']) ? intval($inventory['odometer']) : 0;

                                if ($inventory_odometer < 160000) {
                                    $this->data['camvap'] = "Yes";
                                }
                            }
                        }
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
                        $this->data['purchaser_mvda'] = $this->getCustomerAttribute($customer, 'mvda');
                        $this->data['first_name'] = $this->getCustomerAttribute($customer, 'first_name');
                        $this->data['purchaser_first_name'] = $this->getCustomerAttribute($customer, 'first_name');
                        $this->data['purchaser_last_name'] = $this->getCustomerAttribute($customer, 'last_name');
                        $this->data['last_name'] = $this->getCustomerAttribute($customer, 'last_name');
                        $this->data['purchaser_full_name'] = $this->getCustomerAttribute($customer, 'first_name') . ' ' . $this->getCustomerAttribute($customer, 'last_name');
                        $this->data['purchaser_company_name'] = $this->getCustomerAttribute($customer, 'company_name');
                        $this->data['purchaser_customer_type'] = $this->getCustomerAttribute($customer, 'customer_type');
                        $this->data['purchaser_company_rin'] = $this->getCustomerAttribute($customer, 'rin');
                        $this->data['purchaser_address_1'] = $this->data['purchaser_address'] = $this->getCustomerAttribute($customer, 'apt') . ' ' . $this->getCustomerAttribute($customer, 'street_address');
                        $this->data['purchaser_city'] = $this->getCustomerAttribute($customer, 'city');
                        $this->data['purchaser_province'] = $this->getCustomerAttribute($customer, 'province');
                        $this->data['purchaser_postal_code'] = $this->getCustomerAttribute($customer, 'postal_code');
                        $this->data['purchaser_phone'] = Utility::format_phone_us($this->getCustomerAttribute($customer, 'phone'));
                        $this->data['purchaser_fax'] = Utility::format_phone_us($this->getCustomerAttribute($customer, 'fax'));
                        $this->data['purchaser_hst_registration'] = $this->getCustomerAttribute($customer, 'tax_number');
                        $this->data['purchaser_salesperson_name'] = $this->getCustomerAttribute($customer, 'last_name') . ' ' . $this->getCustomerAttribute($customer, 'first_name');
                        $this->data['purchaser_registration_no'] = $this->getCustomerAttribute($customer, 'sales_person_registration');
                        $this->data['purchaser_driver_licence'] = $this->getCustomerAttribute($customer, 'driver_licence');
                        $this->data['purchaser_email'] = $this->getCustomerAttribute($customer, 'email');
                        $this->data['purchaser_mobile'] = Utility::format_phone_us($this->getCustomerAttribute($customer, 'mobile'));

                        if (strtotime($this->getCustomerAttribute($customer, 'date_of_birth'))) {
                            $this->data['purchaser_date_of_birth'] = Carbon::parse($this->getCustomerAttribute($customer, 'date_of_birth'))->format('m/d/Y');
                            $this->data['purchaser_date_of_birth_ist_format'] = Carbon::parse($this->getCustomerAttribute($customer, 'date_of_birth'))->format(ZD_IST_DATE_FORMAT);
                        }

                        if (strtotime($this->getCustomerAttribute($customer, 'driver_licence_expiry_date'))) {
                            $this->data['purchaser_driver_licence_expiry_date'] = Carbon::parse($this->getCustomerAttribute($customer, 'driver_licence_expiry_date'))->format('m/d/Y');
                            $this->data['purchaser_driver_licence_expiry_date_ist_format'] = Carbon::parse($this->getCustomerAttribute($customer, 'driver_licence_expiry_date'))->format(ZD_IST_DATE_FORMAT);
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

        for ($prefix_count = 1; $prefix_count < 6; $prefix_count++) {

            $prefix = $prefix_count == 0 ? '' : "_" . $prefix_count;
            $this->data['purchaser_full_name' . $prefix] = $this->data['purchaser_full_name'];
        }

        if ($this->company_as_name && !empty($this->data['purchaser_company_name']) && $this->data['purchaser_customer_type'] == '1') {
            $this->data['purchaser_full_name'] = $this->data['purchaser_company_name'];
            $this->data['purchaser_driver_licence'] = $this->data['purchaser_company_rin'];
        }

        if (!empty($data['tradein'])) {
            foreach ($data['tradein'] as $tradein) {
                if (is_array($tradein)) {
                    $this->data['deal_tradein_make'] = $tradein['make'];
                    $this->data['deal_tradein_year'] = $tradein['year'];
                    $this->data['deal_tradein_stock_no'] = $tradein['stock_no'];
                    $this->data['deal_tradein_model'] = $tradein['model'];
                    $this->data['deal_tradein_color'] = $tradein['exterior_color'];
                    $this->data['deal_tradein_trim'] = $tradein['trim'];
                    $this->data['deal_tradein_fuel_type'] = $tradein['fuel_type'];
                    $this->data['deal_tradein_odometer'] = $tradein['odometer'];
                    $this->data['deal_tradein_odometer_1'] = $tradein['odometer'];
                    $this->data['deal_tradein_engine'] = $tradein["litres"];
                    $this->data['deal_tradein_lien_holder_name'] = $tradein["lien_holder"];

                    if (!empty($tradein['km_miles'])) {
                        $this->data['deal_tradein_km_miles'] = $tradein['km_miles'];
                    }
                    if (!empty($tradein['vin'])) {
                        $this->data['deal_tradein_vin'] = $tradein['vin'];
                    }

                    if ((int) $tradein['lien_value'] > 0) {
                        $this->data['deal_tradein_is_lien_present'] = 'Yes';
                        $this->data['deal_tradein_has_trade_value'] = 'Yes';
                        $this->data['deal_tradein_lien_amount'] = $tradein['lien_value'];
                        $this->data['deal_tradein_trade_value'] = $tradein['trade_value'];
                    }
                }
            }

            $this->mapTradeInDisclosures($data);
        }

        if (!empty($data['disclosure'])) {
            $this->data['deal_disclosure_comment'] = $data['disclosure'];
        }

        if (!empty($data['disclosures'])) {
            foreach ($data['disclosures'] as $disclosure) {
                switch ($disclosure['type']) {
                    case 'YesNoQuestion':
                    case 'Switch':

                        $this->data[Utility::slugify($disclosure['question'], '_')] = $disclosure['answer'];
                        if ($disclosure['has_additional_information'] == "1" && $disclosure['answer'] == "Yes") {
                            $this->data[Utility::slugify($disclosure['question'], '_') . "_additional_information"] = $disclosure['additional_information'];
                        }
                        break;

                    case 'DynamicInputField':
                    case 'InputField':

                        $this->data[Utility::slugify($disclosure['question'], '_')] = $disclosure['answer'];
                        break;

                    default:
                        # code...
                        break;
                }
            }
        }

        if (!empty($data['brand'])) {
            $this->data['deal_disclosure_brand_name'] = $data['brand'];
            $this->data["is_brand_" . Utility::slugify($data['brand'], '_')] = "Yes";
        }



        if (!empty($data['delivery_date_time'])) {
            if (strtotime($data['delivery_date_time'])) {
                $this->data['deal_delivery_date'] = Carbon::parse($data['delivery_date_time'])->format('m/d/Y');
                $this->data['deal_delivery_date_ist_format'] = Carbon::parse($data['delivery_date_time'])->format(ZD_IST_DATE_FORMAT);
            }
        }

        if (!empty($data['warranties'])) {
            foreach ($data['warranties'] as $warranty) {
                if (is_array($warranty)) {

                    $this->aggregateValues($warranty['tax_type'], (float) $warranty['tax_amount']);
                    $this->aggregateValues($warranty['tax_type_secondary'], (float) $warranty['tax_amount_secondary']);

                    $this->aggregateValues('generic_primary_tax', (float) $warranty['tax_amount']);
                    $this->aggregateValues('generic_secondary_tax', (float) $warranty['tax_amount_secondary']);

                    $this->data['deal_warranty_flag'] = 'Yes';
                    $this->data['deal_warranty_company_name'] = $warranty['company_name'];
                    $this->data['deal_warranty_duration'] = $warranty['duration'];
                    $this->data['deal_warranty_distance'] = $warranty['distance'];
                    $this->data['deal_warranty_description'] = '                   ' . $warranty['description'];
                    $this->data['deal_breakdown_warranty_amount'] = number_format((float)$warranty['amount'], 2, '.', ',');
                }
            }
        }

        if (!empty($data['guarantee'])) {
            foreach ($data['guarantee'] as $guarantee) {
                if (is_array($guarantee)) {

                    $this->aggregateValues($guarantee['tax_type'], (float) $guarantee['tax_amount']);
                    $this->aggregateValues($guarantee['tax_type_secondary'], (float) $guarantee['tax_amount_secondary']);

                    $this->aggregateValues('generic_primary_tax', (float) $guarantee['tax_amount']);
                    $this->aggregateValues('generic_secondary_tax', (float) $guarantee['tax_amount_secondary']);

                    $this->data['deal_guaranty_flag'] = 'Yes';
                    $this->data['deal_guaranty_duration'] = $guarantee['duration'];
                    $this->data['deal_guaranty_distance'] = $guarantee['distance'];
                    $this->data['deal_guaranty_description'] = '                   ' . $guarantee['description'];
                }
            }
        }

        $fee_count = 0;
        $total_fees = 0;
        if (!empty($data['fees'])) {

            foreach ($data['fees'] as $fee) {
                if (is_array($fee)) {
                    $this->aggregateValues($fee['tax_type'], (float) $fee['tax_amount']);
                    $this->aggregateValues($fee['tax_type_secondary'], (float) $fee['tax_amount_secondary']);

                    $this->aggregateValues('generic_primary_tax', (float) $fee['tax_amount']);
                    $this->aggregateValues('generic_secondary_tax', (float) $fee['tax_amount_secondary']);

                    $this->data['deal_fee_' . $fee_count . '_name'] = $fee['name'];
                    $this->data['deal_fee_' . $fee_count . '_amount'] = number_format((float)$fee['amount'], 2, '.', ',');
                    ++$fee_count;
                    $total_fees += (float) $fee['amount'];
                }
            }
        }

        $this->data['deal_fee_total_amount'] = $total_fees;


        $accessory_count = 0;
        if (!empty($data['accessories'])) {
            $aggregate_accessories_total = 0;
            foreach ($data['accessories'] as $accessory) {
                if (is_array($accessory)) {
                    $this->aggregateValues($accessory['tax_type'], (float) $accessory['tax_amount']);
                    $this->aggregateValues($accessory['tax_type_secondary'], (float) $accessory['tax_amount_secondary']);

                    $this->aggregateValues('generic_primary_tax', (float) $accessory['tax_amount']);
                    $this->aggregateValues('generic_secondary_tax', (float) $accessory['tax_amount_secondary']);

                    $this->data['deal_accessory_' . $accessory_count . '_name'] = $accessory['name'];
                    $this->data['deal_accessory_' . $accessory_count . '_amount'] = number_format((float)$accessory['amount'], 2, '.', ',');
                    $aggregate_accessories_total += (float) $accessory['amount'];
                    ++$accessory_count;
                }
            }

            if ((int) $aggregate_accessories_total > 0) {
                $this->data['deal_fee_' . $fee_count . '_name'] = "Accessories";
                $this->data['deal_fee_' . $fee_count . '_amount'] = number_format((float) $aggregate_accessories_total, 2, '.', ',');
                $this->data['deal_accessories_total_amount'] = number_format((float) $aggregate_accessories_total, 2, '.', ',');
                ++$fee_count;
            }
        }

        if (!empty($data['salesperson'])) {
            $salespersons  = $data['salesperson'];
            if (is_array($salespersons)) {
                foreach ($salespersons as $salesperson) {
                    $this->data['salesperson_name_2'] = $this->data['salesperson_name_1'] = $this->data['salesperson_name'] = $salesperson['name'];
                    $this->data['salesperson_registration_number'] = $salesperson['registration_number'];
                    $this->data['salesperson_title'] = $salesperson['title'];
                    // $this->data['seller_company_name'] = $salesperson['name'];
                    //@todo Need HST registration
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
                    // $this->data['seller_company_name'] = $salesperson['name'];
                    //@todo Need HST registration
                }
            }
        }


        if (!empty($data['dealbreakdown'])) {
            if (is_array($data['dealbreakdown'])) {
                foreach ($data['dealbreakdown'] as $dealbreakdown) {

                    //@todo Add trading value 

                    $selling_price = 0;
                    $selling_price = is_numeric($dealbreakdown['purchase_price']) &&
                        !empty($dealbreakdown['purchase_price']) ? (float) $dealbreakdown['purchase_price'] : 0;

                    $discount = is_numeric($dealbreakdown['discount']) &&
                        !empty($dealbreakdown['discount']) ? (float) $dealbreakdown['discount'] : 0;

                    $selling_price = $selling_price - $discount;

                    $this->aggregateValues($dealbreakdown['purchase_tax_type'], (float) $dealbreakdown['tax_on_purchase_price']);
                    $this->aggregateValues($dealbreakdown['purchase_tax_type_secondary'], (float) $dealbreakdown['tax_on_purchase_price_secondary']);

                    $this->aggregateValues('generic_primary_tax', (float) $dealbreakdown['tax_on_purchase_price']);
                    $this->aggregateValues('generic_secondary_tax', (float) $dealbreakdown['tax_on_purchase_price_secondary']);

                    $this->data['deal_breakdown_purchase_price'] =  number_format((float)$selling_price, 2, '.', ',');
                    $this->data['deal_breakdown_subtotal'] =  number_format((float) $dealbreakdown['subtotal'], 2, '.', ',');
                    $this->data['deal_breakdown_lien_fee'] = number_format((float) $dealbreakdown['lien_payout'], 2, '.', ',');
                    $this->data['deal_breakdown_lien_payout'] = number_format((float) $dealbreakdown['lien_payout'], 2, '.', ',');
                    $this->data['deal_breakdown_lien_registration_fee'] = number_format((float) $dealbreakdown['lien_registration_fee'], 2, '.', ',');
                    $this->data['deal_breakdown_licence_fee'] = number_format((float) $dealbreakdown['licence_fee'], 2, '.', ',');

                    $this->data['deal_breakdown_secondary_subtotal'] = number_format((float) $dealbreakdown['secondary_subtotal'], 2, '.', ',');
                    $this->data['deal_breakdown_secondary_subtotal_with_ppsa_fee'] = (float) $dealbreakdown['secondary_subtotal'] + (float) $dealbreakdown['lien_registration_fee'];
                    $this->data['deal_breakdown_secondary_subtotal_with_ppsa_fee'] = number_format($this->data['deal_breakdown_secondary_subtotal_with_ppsa_fee'], 2, '.', ',');
                    $this->data['deal_breakdown_trade_value'] = number_format((float) $dealbreakdown['trade_value'], 2, '.', ',');
                    $this->data['deal_breakdown_net_difference'] = number_format((float) $dealbreakdown['net_difference'], 2, '.', ',');
                    $this->data['deal_breakdown_tax_on_net_difference'] = number_format((float) $dealbreakdown['total_tax_amount'], 2, '.', ',');
                    $this->data['deal_breakdown_gasoline'] = number_format((float) $dealbreakdown['gasoline'], 2, '.', ',');


                    $this->data['deal_breakdown_total_balance_due'] = number_format((float) $dealbreakdown['total_balance_due'], 2, '.', ',');
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
                    $this->data['deal_finance_number_of_payments_in_months'] = $finance['term'];
                    $this->data['deal_finance_number_of_payments_in_months_1'] = $finance['term'];
                    $this->data['deal_finance_number_of_term'] = $finance['term'];
                    $this->data['deal_finance_number_of_term_1'] = $finance['term'];
                    $this->data['deal_finance_number_of_term_2'] = $finance['term'];
                    $this->data['deal_finance_number_of_term_3'] = $finance['term'];

                    $this->data['deal_finance_installment_amount'] = $finance['emi_amount'];
                    $this->data['deal_finance_installment_amount_1'] = $finance['emi_amount'];
                    $this->data['deal_finance_installment_amount_2'] = $finance['emi_amount'];
                    $this->data['deal_finance_installment_amount_3'] = $finance['emi_amount'];

                    $this->data['deal_finance_payment_frequency'] = $finance['payment_frequency'];
                    $this->data['deal_finance_payment_frequency_1'] = $finance['payment_frequency'];
                    $this->data['deal_finance_payment_frequency_2'] = $finance['payment_frequency'];
                    $this->data['deal_finance_payment_frequency_3'] = $finance['payment_frequency'];

                    $number_of_payments = intval($finance['term']);

                    if (!empty($finance['payment_frequency'])) {
                        switch ($finance['payment_frequency']) {
                            case 'Bi-Weekly':
                                $number_of_payments = ($number_of_payments / 12) * 26;
                                break;

                            case 'Weekly':
                                $number_of_payments = ($number_of_payments / 12) * 52;
                                break;
                            default:
                                # code...
                                break;
                        }
                    }

                    $this->data['deal_finance_number_of_payments_based_on_term'] =  $number_of_payments;
                    $this->data['deal_finance_number_of_payments_based_on_term_1'] =  $number_of_payments;
                    $this->data['deal_finance_number_of_payments_based_on_term_2'] =  $number_of_payments;
                    $this->data['deal_finance_number_of_payments_based_on_term_3'] =  $number_of_payments;

                    if (strtotime($finance['first_payment_date'])) {
                        $this->data['deal_finance_first_payment_date'] = Carbon::parse($finance['first_payment_date'])->format('m/d/Y');
                        $this->data['deal_finance_first_payment_date_ist_format'] = Carbon::parse($finance['first_payment_date'])->format(ZD_IST_DATE_FORMAT);
                    }

                    if (!empty($finance['interest_rate'])) {
                        $this->data['deal_finance_interest'] = $finance['interest_rate'] . ' % ' . $finance['interest_rate_type'];
                        $this->data['deal_finance_interest_rate'] = $finance['interest_rate'];
                        $this->data['deal_finance_interest_rate_1'] = $finance['interest_rate'];
                        $this->data['deal_finance_interest_rate_2'] = $finance['interest_rate'];
                        $this->data['deal_finance_interest_rate_3'] = $finance['interest_rate'];
                        $this->data['deal_finance_interest_rate_4'] = $finance['interest_rate'];
                        $this->data['deal_finance_interest_amount'] = number_format((float)$finance['interest_amount'], 2, '.', ',');
                        $this->data['deal_finance_interest_amount_1'] = number_format((float)$finance['interest_amount'], 2, '.', ',');
                    }

                    if ($data['type'] == "Financed") {
                        $this->data['deal_finance_total_amount'] = number_format((float) $finance['total_amount'], 2, '.', ',');
                        $this->data['deal_finance_total_amount_1'] = number_format((float) $finance['total_amount'], 2, '.', ',');
                    }
                }
            }
        }


        if (!empty($data['payments'])) {
            if (is_array($data['payments'])) {
                $total_payment = 0;
                $total_downpayment = 0;
                $total_deposit = 0;

                foreach ($data['payments'] as $payment) {
                    $total_payment += (float) $payment['amount'];
                    if (strtolower($payment['type']) == 'cash') {
                        $this->data['deal_payment_is_cash'] = 'Yes';
                    } elseif (strtolower($payment['type']) == 'credit') {
                        $this->data['deal_payment_is_credit_card'] = 'Yes';
                    } elseif (strtolower($payment['type']) == 'debit') {
                        $this->data['deal_payment_is_cash'] = 'Yes';
                    } elseif (strtolower($payment['type']) == 'cheque') {
                        $this->data['deal_payment_is_cheque'] = 'Yes';
                    }

                    if ((int)$payment['is_downpayment_or_deposit'] == 0) {
                        $this->data['deal_payment_as_downpayment'] = 'Yes';
                        $total_downpayment += (float) $payment['amount'];
                    } else if ((int)$payment['is_downpayment_or_deposit'] == 1) {
                        $this->data['deal_payment_as_deposit'] = 'Yes';
                        $total_deposit += (float) $payment['amount'];
                    }
                }

                $this->data['deal_payment_total_amount'] = number_format((float) $total_payment, 2, '.', ',');
                $this->data['deal_payment_total_amount_1'] = number_format((float) $total_payment, 2, '.', ',');
                $this->data['deal_payment_total_deposit'] = number_format((float) $total_deposit, 2, '.', ',');
                $this->data['deal_payment_total_downpayment'] = number_format((float) $total_downpayment, 2, '.', ',');
            }
        }


        $insurance_count  = 0;
        if (!empty($data['insurances'])) {
            if (is_array($data['insurances'])) {
                $total_payment = 0;
                $total_tax = 0;
                foreach ($data['insurances'] as $insurance) {
                    $total_payment += (float) $insurance['amount'];
                    $total_tax += (float) $insurance['tax_amount_total'];

                    $this->data['deal_insurance_' . $insurance_count . '_type'] = $insurance['type'];
                    $this->data['deal_insurance_' . $insurance_count . '_description'] = $insurance['description'];
                    $this->data['deal_insurance_' . $insurance_count . '_amount'] = number_format((float)$insurance['amount'], 2, '.', ',');

                    $this->aggregateValues($insurance['tax_type'] . '_insurance', (float) $insurance['tax_amount']);
                    $this->aggregateValues($insurance['tax_type_secondary'] . '_insurance', (float) $insurance['tax_amount_secondary']);

                    $this->aggregateValues('generic_primary_tax_insurance' . '_insurance', (float) $insurance['tax_amount']);
                    $this->aggregateValues('generic_secondary_tax_insurance', (float) $insurance['tax_amount_secondary']);

                    if (strtolower($insurance['type']) == 'life') {
                        $this->data['deal_insurance_is_life_insurance'] = 'Yes';
                    } elseif (strtolower($insurance['type']) == 'income') {
                        $this->data['deal_insurance_is_loss_of_income_insurance'] = 'Yes';
                    } elseif (strtolower($insurance['type']) == 'disability') {
                        $this->data['deal_insurance_is_disability_insurance'] = 'Yes';
                    } else {
                        $this->data['deal_insurance_is_other_insurance'] = 'Yes';
                    }

                    ++$insurance_count;
                }

                $this->data['deal_insurance_total_amount'] = number_format((float) $total_payment, 2, '.', ',');
                $this->data['deal_insurance_tax_on_insurance'] = number_format((float) $total_tax, 2, '.', ',');
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

        $this->data['dealer_street_address'] = $data['location']['street'] . ", " . $data['location']['city'] . ", " . $data['location']['state']  . ", " . $data['location']['zipcode'];
        $this->data['dealer_registration_number'] = $data['location']['dealer_registration_number'];
        $this->data['dealer_tax_number'] = $data['location']['tax_number'];
        $this->data['dealer_name'] = $data['location']['business_name'];
        $this->data['dealer_name_1'] = $data['location']['business_name'];
        $this->data['seller_full_name'] = $data['location']['business_name'];
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

    protected function mapTradeInDisclosures(array $data)
    {
        if (!empty($data['deal_tradein_disclosures'])) {
            foreach ($data['deal_tradein_disclosures'] as $disclosure) {

                if ($disclosure['question'] === "Brand") {
                    $this->data["deal_tradein_is_brand_" . Utility::slugify($disclosure['answer'], '_')] = "Yes";
                }

                switch ($disclosure['type']) {
                    case 'YesNoQuestion':
                    case 'Switch':

                        $this->data["deal_tradein_" . Utility::slugify($disclosure['question'], '_')] = $disclosure['answer'];
                        if ($disclosure['has_additional_information'] == "1" && $disclosure['answer'] == "Yes") {
                            $this->data["deal_tradein_" . Utility::slugify($disclosure['question'], '_') . "_additional_information"] = $disclosure['additional_information'];
                        }
                        break;

                    case 'DynamicInputField':
                    case 'InputField':

                        $this->data["deal_tradein_" . Utility::slugify($disclosure['question'], '_')] = $disclosure['answer'];
                        break;

                    case 'Dropdown':

                        $this->data["deal_tradein_" . Utility::slugify($disclosure['question'], '_')] = $disclosure['answer'];
                        break;

                    default:
                        $this->data["deal_tradein_" . Utility::slugify($disclosure['question'], '_')] = $disclosure['answer'];
                        break;
                        break;
                }
            }
        }
    }

    private function getCustomerAttribute($customer, string $attribute)
    {
        return (is_array($customer) && isset($customer[$attribute])) ? $customer[$attribute] : '';
    }
}
