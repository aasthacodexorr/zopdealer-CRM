<?php

namespace Tenant\app\Libraries\ThirdPartyInventory;

use Carbon\Carbon;
use Tenant\app\Libraries\Calculator\LineCalculator;

class OpenLaneCSVAdapter implements ThirdPartyAdapterInterface
{

    public function preprocess(array $payload, array $optional_data = []): array
    {

        $line_calculator = new LineCalculator();
        $default_taxes = $line_calculator->getLineDefaultTaxTypes();
        $sale_date_object = Carbon::now();
        $response = [];

        if (!empty($payload["Sale date"])) {
            try {
                $sale_date_object = Carbon::parse($payload["Sale date"]);
            } catch (\Exception $ex) {
                $sale_date_object = Carbon::now();
            }
        }

        $mapper = [
            [
                "inventory_attribute" => "year",
                "payload_attribute" => "Year",
                "type" => "numeric",
            ],
            [
                "inventory_attribute" => "make",
                "payload_attribute" => "Make",
                "type" => "string",
            ],
            [
                "inventory_attribute" => "model",
                "payload_attribute" => "Model",
                "type" => "string",
            ],
            [
                "inventory_attribute" => "trim",
                "payload_attribute" => "Trim",
                "type" => "string",
            ],
            [
                "inventory_attribute" => "vin",
                "payload_attribute" => "Vin",
                "type" => "string",
            ],
            [
                "inventory_attribute" => "interior_color",
                "payload_attribute" => "Interior color",
                "type" => "string",
            ],
            [
                "inventory_attribute" => "exterior_color",
                "payload_attribute" => "Exterior color",
                "type" => "string",
            ],
            [
                "inventory_attribute" => "odometer",
                "payload_attribute" => "Odometer",
                "type" => "numeric",
            ],
            [
                "inventory_attribute" => "km_miles",
                "payload_attribute" => "Odometer unit",
                "type" => "km_or_miles",
            ],
            [
                "inventory_attribute" => "stock_no",
                "payload_attribute" => "stock_no",
                "type" => "string",
            ]
        ];

        $optional_attributes = [
            'status',
            'sub_status',
            'feature_listing',
            'vehicle_type',
            'visibility',
            'odometer',
            'special_price',
            'price',
            'location',
            'title',
            'vehicle_description',
            'financing_on',
            'downpayment',
            'interest_rate',
            'term',
            'payment_frequency'
        ];

        foreach ($optional_attributes as $optional_attribute) {
            if (isset($optional_data[$optional_attribute])) {
                $response[$optional_attribute] = $optional_data[$optional_attribute];
            }
        }

        foreach ($mapper as $attribute_mapper) {
            if (!empty($payload[$attribute_mapper['payload_attribute']])) {
                $response[$attribute_mapper['inventory_attribute']] = $this->formatValue($payload[$attribute_mapper['payload_attribute']], $attribute_mapper['type']);
            }
        }

        if (!empty($payload["Purchase price"])) {
            $response['purchase'] = [];
            $response['purchase']['first_name'] = '';
            $response['purchase']['purchase_from_flag'] = "1";
            $response['purchase']['purchase_date'] = $sale_date_object->format('m/d/Y');
            $response['purchase']['price'] = $this->formatValue($payload["Purchase price"], 'decimal');

            $purchase_line_total = $line_calculator->calculate((float) $response['purchase']['price'], 0, 1, $default_taxes['tax_type'], $default_taxes['tax_type_secondary']);
            $response['purchase']['discount'] = 0;
            $response['purchase']['tax_amount'] = $purchase_line_total['tax_amount'];
            $response['purchase']['tax_percentage'] = $purchase_line_total['tax_percentage'];
            $response['purchase']['tax_type'] = $purchase_line_total['tax_type'];
            $response['purchase']['tax_type_secondary'] = $purchase_line_total['tax_type_secondary'];
            $response['purchase']['tax_amount_secondary'] = $purchase_line_total['tax_amount_secondary'];
            $response['purchase']['tax_percentage_secondary'] = $purchase_line_total['tax_percentage_secondary'];
            $response['purchase']['tax_amount_total'] = $purchase_line_total['tax_amount_total'];

            $purchase_mapper = [
                [
                    "purchase_attribute" => "street_address",
                    "payload_attribute" => "Address",
                    "type" => "alpha_numeric_punct",
                ],
                [
                    "purchase_attribute" => "city",
                    "payload_attribute" => "City",
                    "type" => "alpha_numeric_punct",
                ],
                [
                    "purchase_attribute" => "province",
                    "payload_attribute" => "State",
                    "type" => "alpha_numeric_punct",
                ],
                [
                    "purchase_attribute" => "postal_code",
                    "payload_attribute" => "Zip",
                    "type" => "string",
                ],
                [
                    "purchase_attribute" => "first_name",
                    "payload_attribute" => "Seller",
                    "type" => "alpha_numeric_punct",
                ],
                [
                    "purchase_attribute" => "company_name",
                    "payload_attribute" => "Seller",
                    "type" => "alpha_numeric_punct",
                ]
            ];

            foreach ($purchase_mapper as $attribute_mapper) {
                if (!empty($payload[$attribute_mapper['payload_attribute']])) {
                    $response['purchase'][$attribute_mapper['purchase_attribute']] = $this->formatValue($payload[$attribute_mapper['payload_attribute']], $attribute_mapper['type']);
                }
            }
        }

        $response['expenses'] = [];
        $expense_attributes = [
            "Fees buy fee",
            "Fees internet fee",
            "Fees car history fee",
            "Fees certification fee",
            "Fees online buyer fee",
            "Fees third party fee",
            "Fees reg transfer fee",
            "Fees inspection fee",
            "Fees post sale inspection fee",
            "Fees floor plan fee",
            "Fees sublet transportation fee",
            "Fees assurance fee",
            "Fees assurance plus fee",
            "Fees transportation fee",
            "Fees other fees"
        ];

        foreach ($expense_attributes as $expense_attribute) {
            $cost = $this->formatValue($payload[$expense_attribute], 'decimal');
            if (!empty((float) $cost)) {
                $expense_title = str_replace("Fees ", "", $expense_attribute);
                $expense_title = ucwords($expense_title);
                $expense = $line_calculator->calculate((float) $cost, 0, 1, $default_taxes['tax_type'], $default_taxes['tax_type_secondary']);
                $expense['name'] = $expense_title;
                $expense['description'] = '';
                $expense['price'] = $cost;
                $expense['quantity'] = 1;
                $expense['discount'] = 0;
                $expense['vendor_name'] = !empty($payload["Seller"]) ? $this->formatValue($payload["Seller"], 'alpha_numeric_punct') : "";
                $expense['purchase_date'] = $sale_date_object->format('m/d/Y');


                array_push($response['expenses'], $expense);
            }
        }

        return $response;
    }

    private function formatValue($value, $data_type)
    {
        switch ($data_type) {
            case 'numeric':
                return preg_replace('/[^0-9]/', '', $value);
                break;

            case 'km_or_miles':
                return strtolower($value) === "miles" ? "Miles" : "KM";
                break;

            case 'decimal':
                return preg_filter('/[^\d.]|(?<!\d)\./', '', $value);
                break;

            case 'alpha_numeric_punct':
                $value = preg_replace("/[^A-Za-z0-9 -]/", "", $value);
                return trim($value);
                break;

            default:
                return $value;
                break;
        }
    }
}
