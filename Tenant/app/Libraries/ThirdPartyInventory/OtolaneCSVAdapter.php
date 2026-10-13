<?php

namespace Tenant\app\Libraries\ThirdPartyInventory;

use Carbon\Carbon;
use Tenant\app\Libraries\Calculator\LineCalculator;
use Tenant\app\Libraries\Mapper\Zop;

class OtolaneCSVAdapter implements ThirdPartyAdapterInterface
{

    public function preprocess(array $payload, array $optional_data = []): array
    {
        $line_calculator = new LineCalculator();
        $sale_date_object = Carbon::now();
        $zop_mapper = new Zop();
        $default_taxes = $line_calculator->getLineDefaultTaxTypes();
        $response = [];

        $mapper = [
            [
                "inventory_attribute" => "year",
                "payload_attribute" => "year",
                "type" => "numeric",
            ],
            [
                "inventory_attribute" => "make",
                "payload_attribute" => "make",
                "type" => "string",
            ],
            [
                "inventory_attribute" => "model",
                "payload_attribute" => "model",
                "type" => "string",
            ],
            [
                "inventory_attribute" => "trim",
                "payload_attribute" => "trim",
                "type" => "string",
            ],
            [
                "inventory_attribute" => "vin",
                "payload_attribute" => "vin",
                "type" => "string",
            ],
            [
                "inventory_attribute" => "exterior_color",
                "payload_attribute" => "colour",
                "type" => "string",
            ],
            [
                "inventory_attribute" => "odometer",
                "payload_attribute" => "odometer",
                "type" => "numeric",
            ],
            [
                "inventory_attribute" => "price",
                "payload_attribute" => "List price",
                "type" => "decimal",
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

        $response["km_miles"] = "KM";
        $response['body_type'] = !empty($payload['vehicle type']) ? $zop_mapper->exactMatch('body_types', $payload['vehicle type'], $payload['vehicle type']) : '';
        $response['drivetrain'] = !empty($payload['drive']) ? $zop_mapper->exactMatch('drivetrains', $payload['drive'], "Other/Don’t Know") :
            "Other/Don’t Know";

        $response['transmission'] = !empty($payload['transmission']) ? $zop_mapper->exactMatch('transmissions', $payload['transmission'], "Other") :
            "Other";

        $response['engine'] = !empty($payload['cylinders']) ? $zop_mapper->exactMatch('cylinders', $payload['cylinders'], "Other") :
            "Other";

        $response['status'] = !empty($payload['status']) ? $zop_mapper->exactMatch('statuses', $payload['status'], "Unpublished") :
            "Unpublished";

        $response['vehicle_type'] = !empty($payload['cert/as-is']) ? $zop_mapper->exactMatch('vehicle_types', $payload['cert/as-is'], "Used") :
            "Used";

        $response['stock_no'] = !empty($payload['stock #']) ? $payload['stock #'] : "";

        if (!empty($payload['dii'])) {
            if (is_numeric($payload['dii'])) {
                $sale_date_object->subDays(intval($payload['dii']));
            }
        }

        $response['stock_date'] = $sale_date_object->format('Y-m-d');

        $expenses_amount = !empty($payload['expenses']) ? (float) $this->formatValue($payload['expenses'], 'decimal') : 0;
        $response['expenses'] = [];

        if (!empty($expenses_amount)) {
            $expense = $line_calculator->calculate((float) $expenses_amount, 0, 1, $default_taxes['tax_type'], $default_taxes['tax_type_secondary']);
            $expense['name'] = "NO NAME";
            $expense['description'] = '';
            $expense['price'] = $expenses_amount;
            $expense['quantity'] = 1;
            $expense['discount'] = 0;
            $expense['vendor_name'] = "NO NAME";
            $expense['purchase_date'] = $sale_date_object->format('m/d/Y');
            array_push($response['expenses'], $expense);
        }

        $purchase_price = !empty($payload['purchase price']) ? (float) $this->formatValue($payload['purchase price'], 'decimal') : 0;
        if (!empty($purchase_price)) {
            $response['purchase'] = [];
            $response['purchase']['first_name'] = '';
            $response['purchase']['purchase_from_flag'] = "1";
            $response['purchase']['purchase_date'] = $sale_date_object->format('m/d/Y');
            $response['purchase']['price'] =  $purchase_price;

            $purchase_line_total = $line_calculator->calculate((float) $purchase_price, 0, 1, $default_taxes['tax_type'], $default_taxes['tax_type_secondary']);
            $response['purchase']['discount'] = 0;
            $response['purchase']['tax_amount'] = $purchase_line_total['tax_amount'];
            $response['purchase']['tax_percentage'] = $purchase_line_total['tax_percentage'];
            $response['purchase']['tax_type'] = $purchase_line_total['tax_type'];
            $response['purchase']['tax_type_secondary'] = $purchase_line_total['tax_type_secondary'];
            $response['purchase']['tax_amount_secondary'] = $purchase_line_total['tax_amount_secondary'];
            $response['purchase']['tax_percentage_secondary'] = $purchase_line_total['tax_percentage_secondary'];
            $response['purchase']['tax_amount_total'] = $purchase_line_total['tax_amount_total'];

            $response['purchase']['first_name'] = $response['purchase']['company_name'] = "NO NAME";
            $response['purchase']['key_count'] = !empty($payload['key #']) ? $this->formatValue($payload['key #'], 'numeric') : '';
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
