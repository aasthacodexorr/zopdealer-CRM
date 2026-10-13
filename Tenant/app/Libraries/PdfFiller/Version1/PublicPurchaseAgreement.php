<?php

namespace Tenant\app\Libraries\PdfFiller\Version1;

use App\Helpers\Utility;
use Carbon\Carbon;
use Tenant\app\Libraries\PdfFiller\PDFFillerAbstract;
use mikehaertl\pdftk\Pdf;
use Tenant\app\Libraries\PdfFiller\PDFFillerInterface;

class PublicPurchaseAgreement extends PDFFillerAbstract implements PDFFillerInterface
{
    public function __construct(String $template_name)
    {
        $this->template_name = $template_name;
        $this->pdf_driver = new Pdf($template_name);
        $this->company_as_name = true;
    }

    public function dataMap(array $data)
    {

        if (!empty($data['inventory'])) {
            foreach ($data['inventory'] as $inventory) {
                if (is_array($inventory)) {
                    $this->data['make'] = $inventory['make'];
                    $this->data['year'] = $inventory['year'];
                    $this->data['stock_no'] = $inventory['stock_no'];
                    $this->data['model'] = $inventory['model'];
                    $this->data['color'] = $inventory['exterior_color'];
                    $this->data['trim'] = $inventory['trim'];
                    $this->data['fuel_type'] = $inventory['fuel_type'];
                    $this->data['odometer'] = $inventory['odometer'];
                    $this->data['engine'] = $inventory['litres'];
                    if (!empty($inventory['km_miles'])) {
                        $this->data['km_miles'] = $inventory['km_miles'];
                    }
                    if (!empty($inventory['vin'])) {
                        $this->data['vin'] = $inventory['vin'];
                    }
                }
            }
        }

        if (!empty($data['location'])) {
            $this->data['purchaser_name'] = $data['location']['business_name'];
            $this->data['purchaser_mvda'] = $data['location']['dealer_registration_number'];
            $this->data['purchaser_address'] = $data['location']['street']
                . " " . $data['location']['city']
                . " " . $data['location']['state']
                . " " . $data['location']['zipcode'];

            $this->data['purchaser_street_address'] = $data['location']['street'];

            $this->data['purchaser_phone'] = Utility::format_phone_us($data['location']['phone']);
            $this->data['purchaser_fax'] = Utility::format_phone_us($data['location']['fax']);
            $this->data['purchaser_tax_number'] = $data['location']['tax_number'];
            $this->data['purchaser_rin_number'] = $data['location']['rin_number'];

            $this->data['purchaser_city_prov_pc'] = $data['location']['city'] . ' / ' . $data['location']['state'] . ' / ' . $data['location']['zipcode'];
        }

        if (!empty($data['current_user'])) {
            if (!empty($data['current_user'][0])) {
                $this->data['purchaser_sales_person'] = $data['current_user'][0]['name'];
                $this->data['purchaser_registration_number'] = $data['current_user'][0]['registration_number'];
            }
        }


        if (!empty($data['purchase_details'])) {
            $purchase_details = $data['purchase_details'];

            if (strtotime($purchase_details['purchase_date'])) {
                $this->data['date'] = Carbon::parse($purchase_details['purchase_date'])->format('m/d/Y');
                $this->data['Date_1'] = Carbon::parse($purchase_details['purchase_date'])->format('m/d/Y');
                $this->data['Date_2'] = Carbon::parse($purchase_details['purchase_date'])->format('m/d/Y');

                $this->data['date_ist_format'] = Carbon::parse($purchase_details['purchase_date'])->format(ZD_IST_DATE_FORMAT);
                $this->data['Date_1_ist_format'] = Carbon::parse($purchase_details['purchase_date'])->format(ZD_IST_DATE_FORMAT);
                $this->data['Date_2_ist_format'] = Carbon::parse($purchase_details['purchase_date'])->format(ZD_IST_DATE_FORMAT);
            }

            $this->data['seller_salesperson_name'] = $purchase_details['first_name'] . " " . $purchase_details['last_name'];
            $this->data['seller_sales_person'] = $purchase_details['first_name'] . " " . $purchase_details['last_name'];
            $this->data['seller_company'] = $purchase_details['company_name'];

            //purchased from company
            if ($purchase_details['purchase_from_flag'] == '1') {
                $this->data['seller_name'] = $purchase_details['company_name'];
                $this->data['seller_mvda'] = $purchase_details['mvda'];
                $this->data['seller_registration_number'] = $purchase_details['sales_person_registration'];
            } else {
                $this->data['seller_name'] = $purchase_details['first_name'] . " " . $purchase_details['last_name'];
                $this->data['seller_name_1'] = $purchase_details['first_name'] . " " . $purchase_details['last_name'];
            }

            $this->data['seller_driver_licence'] = $purchase_details['driver_licence'];
            $this->data['seller_address'] = $purchase_details['apt'];
            $this->data['seller_address'] .= " " . $purchase_details['street_address'];
            $this->data['seller_address'] .= " " . $purchase_details['city'];
            $this->data['seller_address'] .= " " . $purchase_details['province'];
            $this->data['seller_address'] .= " " . $purchase_details['postal_code'];

            $this->data['seller_street_address'] = $purchase_details['apt'];
            $this->data['seller_street_address'] .= " " . $purchase_details['street_address'];

            $this->data['seller_city_prov_pc'] = $purchase_details['city'] . ' / ' . $purchase_details['province'] . ' / ' . $purchase_details['postal_code'];

            $this->data['seller_phone'] = Utility::format_phone_us($purchase_details['phone']);
            $this->data['seller_fax'] = Utility::format_phone_us($purchase_details['fax']);

            $total = 0;
            $total += (float) $purchase_details['price'];
            $total -= (float) $purchase_details['discount'];
            $total +=  (float) $purchase_details['tax_amount_total'];
            $this->data['selling_price'] = "$ " . number_format((float) $purchase_details['price'], 2, '.', ',');
            $this->data['selling_discount'] = "$ " . number_format((float) $purchase_details['discount'], 2, '.', ',');

            $selling_price_after_discount = (float) $purchase_details['price'] - (float) $purchase_details['discount'];
            $this->data['selling_price_after_discount'] = "$ " . number_format((float) $selling_price_after_discount, 2, '.', ',');

            $this->data['selling_tax_number'] = $purchase_details['tax_number'];

            if ((float) $purchase_details['tax_amount'] != 0) {
                $this->data['selling_tax_type'] = $purchase_details['tax_type'];
                $this->data['selling_tax_percentage'] =   $purchase_details['tax_percentage'] . "%";
                $this->data['selling_tax_amount'] = "$ " . number_format((float) $purchase_details['tax_amount'], 2, '.', ',');
            }

            if ((float) $purchase_details['tax_amount_secondary'] != 0) {
                $this->data['selling_tax_type_secondary'] = $purchase_details['tax_type_secondary'];
                $this->data['selling_tax_percentage_secondary'] =   $purchase_details['tax_percentage_secondary'] . "%";
                $this->data['selling_tax_amount_secondary'] = "$ " . number_format((float) $purchase_details['tax_amount_secondary'], 2, '.', ',');
            }

            $this->data['selling_tax_amount_total'] = "$ " . number_format((float) $purchase_details['tax_amount_total'], 2, '.', ',');

            $this->data['selling_total_amount'] = "$ " . number_format((float) $total, 2, '.', ',');
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

                    case 'Textbox':

                        $this->data[Utility::slugify($disclosure['question'], '_')] = $disclosure['answer'];
                        break;

                    default:
                        # code...
                        break;
                }

                if ($disclosure['question'] === 'Brand') {
                    switch ($disclosure['answer']) {
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
                }

                // ! For Legacy Mapping
                if ($disclosure['question'] === 'Complete Disclosure') {
                    $this->data['deal_disclosure_comment'] = $disclosure['answer'];
                }
            }
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
