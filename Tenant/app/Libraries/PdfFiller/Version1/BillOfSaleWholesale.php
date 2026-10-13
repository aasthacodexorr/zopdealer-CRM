<?php

namespace Tenant\app\Libraries\PdfFiller\Version1;

use App\Helpers\Utility;
use Carbon\Carbon;
use Tenant\app\Libraries\PdfFiller\PDFFillerAbstract;
use mikehaertl\pdftk\Pdf;
use Tenant\app\Libraries\PdfFiller\PDFFillerInterface;

class BillOfSaleWholesale extends PDFFillerAbstract implements PDFFillerInterface
{

    public function __construct(String $template_name)
    {
        $this->template_name = $template_name;

        $this->pdf_driver = new Pdf($template_name);
    }

    public function dataMap(array $data)
    {
        if (!empty($data['id'])) {
            $this->data['context_entity_id'] = $data['id'];
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
                    $this->data['inventory_body_type'] = $inventory['body_type'];

                    if (!empty($inventory['km_miles'])) {
                        $this->data['inventory_km_miles'] = $inventory['km_miles'];
                    }
                    $this->data['inventory_vin'] = $inventory['vin'];
                }
            }
        }


        if (!empty($data['dealbreakdown'])) {
            foreach ($data['dealbreakdown'] as $dealbreakdown) {
                $this->data['inventory_price'] = number_format((float) $dealbreakdown['subtotal'], 2, '.', ',');
                $this->data['inventory_tax'] = number_format((float) $dealbreakdown['total_tax_amount'], 2, '.', ',');
                $this->data['inventory_licencing'] =  number_format((float) $dealbreakdown['licence_fee'], 2, '.', ',');
                $this->data['inventory_total_amount'] = number_format((float) $dealbreakdown['total_balance_due'], 2, '.', ',');
            }
        }

        if (!empty($data['payments'])) {
            if (is_array($data['payments'])) {
                $total_payment = 0;
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
                    } else if ((int)$payment['is_downpayment_or_deposit'] == 1) {
                        $this->data['deal_payment_as_deposit'] = 'Yes';
                    }
                }

                $this->data['deal_payment_total_amount'] = number_format((float) $total_payment, 2, '.', ',');
                $this->data['deal_payment_total_amount_1'] = number_format((float) $total_payment, 2, '.', ',');
            }
        }

        if (!empty($data['customers'])) {
            foreach ($data['customers'] as $customer) {
                if (is_array($customer)) {
                    if ($customer['deal_customer_type'] == 'primary_customer') {
                        $this->data['purchaser_mvda'] = $customer['mvda'];
                        $this->data['purchaser_company_name'] = $customer['company_name'];
                        $this->data['purchaser_address'] = $customer['apt'] . ' ' . $customer['street_address'];
                        $this->data['purchaser_city_prov_pc'] = $customer['city'] . ' / ' . $customer['province'] . ' / ' . $customer['postal_code'];
                        $this->data['purchaser_phone'] = Utility::format_phone_us($customer['phone']);
                        $this->data['purchaser_fax'] = Utility::format_phone_us($customer['fax']);
                        $this->data['purchaser_hst_registration'] = $customer['tax_number'];
                        $this->data['purchaser_salesperson_name'] = $customer['first_name'] . ' ' . $customer['last_name'];
                        $this->data['purchaser_registration_no'] = $customer['sales_person_registration'];
                    }
                }
            }
        }

        if (!empty($data['location'])) {
            $location = $data['location'];
            if (is_array($location)) {
                $this->data['seller_company_name'] = $location['business_name'];
                $this->data['seller_address'] = $location['street'];
                $this->data['seller_city_prov_pc'] = $location['city'] . ' / ' . $location['state'] . ' / ' . $location['zipcode'];
                $this->data['seller_phone'] = Utility::format_phone_us($location['phone']);;
                $this->data['seller_hst_registration'] = $location['tax_number'];
                $this->data['seller_mvda'] = $location['dealer_registration_number'];
                $this->data['seller_fax'] = Utility::format_phone_us($location['fax']);
            }
        }

        if (!empty($data['approved_by'])) {
            $approvers  = $data['approved_by'];
            if (is_array($approvers)) {
                foreach ($approvers as $approver) {
                    $this->data['seller_salesperson_name'] = $approver['name'];
                    $this->data['seller_registration_no'] = $approver['registration_number'];
                }
            }
        }


        if (strtotime($data['date'])) {
            $this->data['deal_date'] = Carbon::parse($data['date'])->format('m/d/Y');
            $this->data['deal_date_1'] = Carbon::parse($data['date'])->format('m/d/Y');
            $this->data['deal_date_2'] = Carbon::parse($data['date'])->format('m/d/Y');

            $this->data['deal_date_ist_format'] = Carbon::parse($data['date'])->format(ZD_IST_DATE_FORMAT);
            $this->data['deal_date_1_ist_format'] = Carbon::parse($data['date'])->format(ZD_IST_DATE_FORMAT);
            $this->data['deal_date_2_ist_format'] = Carbon::parse($data['date'])->format(ZD_IST_DATE_FORMAT);
        }

        $this->data['deal_disclosure_comment'] = '                                                                           '
            . $data['disclosure'];

        switch ($data['brand']) {
            case 'Irreparable':
                $this->data['irreparable'] = "Yes";
                break;
            case 'Rebuilt':
                $this->data['rebuilt'] = "Yes";
                break;

            case 'Salvage':
                $this->data['salvage'] = "Yes";
                break;

            case 'None':
                $this->data['none'] = "Yes";
                break;
        }

        if (!empty($data['disclosures'])) {
            foreach ($data['disclosures'] as $disclosure) {
                switch ($disclosure['type']) {
                    case 'YesNoQuestion':

                        $this->data[Utility::slugify($disclosure['question'], '_')] = $disclosure['answer'];
                        if ($disclosure['has_additional_information'] == "1" && $disclosure['answer'] == "Yes") {
                            $this->data[Utility::slugify($disclosure['question'], '_') . "_additional_information"] = $disclosure['additional_information'];
                        }
                        break;

                    case 'InputField':

                        $this->data[Utility::slugify($disclosure['question'], '_')] = $disclosure['answer'];
                        break;

                    default:
                        # code...
                        break;
                }
            }
        }


        return $this;
    }
}
