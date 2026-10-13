<?php

namespace Tenant\app\Libraries\PDFParser;

use App\Libraries\DealerTrack\Import\Region\RegionFactory;
use CURLFile;
use Tenant\app\Libraries\Calculator\LineCalculator;

class DealertrackPDFParser implements PDFParserInterface
{
    protected string $api_domain;

    public function __construct()
    {
        $this->api_domain = getEnv('zopai.host');
    }

    public function sendRequest(CURLFile $curl_file, array $options = []): ?array
    {

        $ch = curl_init();
        $postfields = array('file' => $curl_file);
        curl_setopt($ch, CURLOPT_URL, $this->api_domain . '/services/pdf/extract/dealertrack');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);

        $headers = array();
        $headers[] = 'Content-Type: multipart/form-data';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return [
                'errors' => curl_error($ch)
            ];
        }
        curl_close($ch);

        return json_decode($result, true);
    }

    public function mapResponse(array $data): array
    {
        $response = [];

        $response['has_trade_in'] = false;
        $response['has_trade_in'] = false;
        $response['finance'] = [];
        $response['payments'] = [];
        $response['fees'] = [];
        $response['base_breakdown'] = [
            "purchase_price" => "0",
            "discount" => 0,
            "subtotal" => 0,
            "trade_value" => 0,
            "net_difference" => 0,

            "purchase_tax_type" => "",
            "purchase_tax_percentage" => 0,
            "tax_on_purchase_price" => 0,

            "purchase_tax_type_secondary" => "",
            "tax_on_purchase_price_secondary" => 0,
            "purchase_tax_percentage_secondary" => 0,
            "tax_on_purchase_total" => 0,

            "total_tax_amount" => 0,
            "insurance_tax" => 0,

            "licence_fee" => 0,
            "lien_payout" => 0,
            "trade_equity" => 0,
            "gasoline" => 0,
            "lien_registration_fee" => 0,
            "total_balance_due" => 0,
            "plate_type" => "",
            "secondary_subtotal" => 0,
        ];
        $response['insurances'] = [];
        $response['warranties'] = [];

        $inventory_mapping_attributes = [
            [
                "inventory_form_attribute" => "vin",
                "data_form_attribute" => "vin",
            ],
            [
                "inventory_form_attribute" => "year",
                "data_form_attribute" => "year",
            ],
            [
                "inventory_form_attribute" => "make",
                "data_form_attribute" => "make",
            ],
            [
                "inventory_form_attribute" => "model",
                "data_form_attribute" => "model",
            ],
            [
                "inventory_form_attribute" => "trim",
                "data_form_attribute" => "trim",
            ],
            [
                "inventory_form_attribute" => "body_type",
                "data_form_attribute" => "bodyStyle",
            ],
            [
                "inventory_form_attribute" => "odometer",
                "data_form_attribute" => "odometer",
            ],
            [
                "inventory_form_attribute" => "stock_no",
                "data_form_attribute" => "stockNumber",
            ]

        ];

        $customer_mapping_attributes = [
            [
                "customer_form_attribute" => "first_name",
                "data_form_attribute" => "firstName",
            ],
            [
                "customer_form_attribute" => "last_name",
                "data_form_attribute" => "lastName",
            ],
            [
                "customer_form_attribute" => "phone",
                "data_form_attribute" => "phoneNumber",
            ],
            [
                "customer_form_attribute" => "mobile",
                "data_form_attribute" => "mobileNumber",
            ],
            [
                "customer_form_attribute" => "email",
                "data_form_attribute" => "email",
            ],
            [
                "customer_form_attribute" => "date_of_birth",
                "data_form_attribute" => "dateOfBirth",
            ],

        ];

        $warranty_as_fee = false;

        $region_name = '';


        $line_calculator = new LineCalculator();
        $default_taxes = $line_calculator->getLineDefaultTaxTypes();

        $purchase_price = 0;
        $trade_value = 0;
        $lien_payout = 0;
        $trade_equity = 0;

        if (!empty($data['creditApplicationData'])) {

            if (!empty($data['creditApplicationData']['sellingPrice'])) {
                $purchase_price = $data['creditApplicationData']['sellingPrice'];
            }

            if (!empty($data['creditApplicationData']['province'])) {
                $region_name = $data['creditApplicationData']['province'];
            }

            if (!empty($data['creditApplicationData']['customer'])) {
                $response['customer'] = [];
                foreach ($customer_mapping_attributes as $customer_attribute) {
                    $response['customer'][$customer_attribute['customer_form_attribute']] =
                        $data['creditApplicationData']['customer'][$customer_attribute['data_form_attribute']];
                }

                $customer_current_address_attributes = [
                    [
                        "customer_form_attribute" => "street_address",
                        "data_form_attribute" => "streetAddress",
                    ],
                    [
                        "customer_form_attribute" => "city",
                        "data_form_attribute" => "city",
                    ],
                    [
                        "customer_form_attribute" => "province",
                        "data_form_attribute" => "province",
                    ],
                    [
                        "customer_form_attribute" => "postal_code",
                        "data_form_attribute" => "postalCode",
                    ]

                ];

                foreach ($customer_current_address_attributes as $customer_attribute) {
                    $response['customer'][$customer_attribute['customer_form_attribute']] =
                        $data['creditApplicationData']['customer']['currentAddress'][$customer_attribute['data_form_attribute']];
                }
            }

            if (!empty($data['creditApplicationData']['inventory'])) {
                $response['inventory'] = [];
                foreach ($inventory_mapping_attributes as $inventory_attribute) {
                    $response['inventory'][$inventory_attribute['inventory_form_attribute']] =
                        $data['creditApplicationData']['inventory'][$inventory_attribute['data_form_attribute']];
                }
            }

            $region_identifier = $this->getRegionIdentifier($region_name);
            $regionFactory = RegionFactory::getInstance($region_identifier);

            if (!empty($data['creditApplicationData']['fees'])) {

                foreach ($data['creditApplicationData']['fees'] as $fee) {
                    if (!empty($fee['amount'])) {

                        if (trim($fee["label"]) === "Cash Down" || trim($fee["label"]) === "Cash Down Payment") {
                            array_push($response['payments'], ["description" => $fee["label"], "amount" => $fee["amount"], "type" => "", "is_downpayment_or_deposit" => 0]);
                        } else if (trim($fee["label"]) === "PPSA") {
                            $response['base_breakdown']["lien_registration_fee"] = $fee["amount"];
                        } else if (trim($fee["label"]) === "License Fee") {
                            $response['base_breakdown']["licence_fee"] = $fee["amount"];
                        } else if ($fee["label"] === "Rebate" || $fee["label"] === "Net Selling Price") {
                        } else {

                            $non_taxable_keyword_notations = $this->getNonTaxableKeyWords();
                            $non_taxable_fees_notation_pattern = '/(' . implode('|', array_map('preg_quote', $non_taxable_keyword_notations)) . ')/i';

                            if (!preg_match($non_taxable_fees_notation_pattern, $fee["label"])) {
                                $current_fee_line_item = $line_calculator->calculate($fee["amount"], 0, 1, $default_taxes['tax_type'], $default_taxes['tax_type_secondary'], 0);
                                array_push($response['fees'], [
                                    'name' =>  $fee["label"],
                                    'amount' => $fee["amount"],
                                    'description' => '',
                                    'tax_type'  => $current_fee_line_item['tax_type'],
                                    'tax_percentage'    => $current_fee_line_item['tax_percentage'],
                                    'tax_amount'    => $current_fee_line_item['tax_amount'],
                                    'tax_type_secondary'    => $current_fee_line_item['tax_type_secondary'],
                                    'tax_amount_secondary'  => $current_fee_line_item['tax_amount_secondary'],
                                    'tax_percentage_secondary'  => $current_fee_line_item['tax_percentage_secondary'],
                                    'tax_amount_total'  => $current_fee_line_item['tax_amount_total'],

                                    'cost'  => 0,
                                    'cost_tax_type' => $current_fee_line_item['tax_type'],
                                    'cost_tax_percentage'   => $current_fee_line_item['tax_percentage'],
                                    'cost_tax_amount'   => 0,
                                    'cost_tax_type_secondary'   => $current_fee_line_item['tax_type_secondary'],
                                    'cost_tax_amount_secondary' => 0,
                                    'cost_tax_percentage_secondary' => $current_fee_line_item['tax_percentage_secondary'],
                                    'cost_tax_amount_total' => 0
                                ]);
                            } else {
                                $current_fee_line_item = $line_calculator->calculate($fee["amount"], 0, 1, '', '', 0);
                                array_push($response['fees'], [
                                    'name' =>  $fee["label"],
                                    'amount' => $fee["amount"],
                                    'description' => '',
                                    'tax_type'  => $current_fee_line_item['tax_type'],
                                    'tax_percentage'    => $current_fee_line_item['tax_percentage'],
                                    'tax_amount'    => $current_fee_line_item['tax_amount'],
                                    'tax_type_secondary'    => $current_fee_line_item['tax_type_secondary'],
                                    'tax_amount_secondary'  => $current_fee_line_item['tax_amount_secondary'],
                                    'tax_percentage_secondary'  => $current_fee_line_item['tax_percentage_secondary'],
                                    'tax_amount_total'  => $current_fee_line_item['tax_amount_total'],

                                    'cost'  => 0,
                                    'cost_tax_type' => $current_fee_line_item['tax_type'],
                                    'cost_tax_percentage'   => $current_fee_line_item['tax_percentage'],
                                    'cost_tax_amount'   => 0,
                                    'cost_tax_type_secondary'   => $current_fee_line_item['tax_type_secondary'],
                                    'cost_tax_amount_secondary' => 0,
                                    'cost_tax_percentage_secondary' => $current_fee_line_item['tax_percentage_secondary'],
                                    'cost_tax_amount_total' => 0
                                ]);
                            }
                        }
                    }
                }
            }

            if (!empty($data['creditApplicationData']['aftermarketServices'])) {
                $after_service_taxes = $regionFactory->getAfterServiceDefaultTaxes();

                foreach ($data['creditApplicationData']['aftermarketServices'] as $aftermarket_service) {
                    $skip_keyword_notations = ["Tax"];
                    $skip_notation_pattern = '/(' . implode('|', array_map('preg_quote', $skip_keyword_notations)) . ')/i';

                    if (preg_match($skip_notation_pattern, $aftermarket_service["label"]) || empty($aftermarket_service['amount'])) {
                        continue;
                    }

                    $insurance_keyword_notations = ["Insurance"];
                    $insurance_notation_pattern = '/(' . implode('|', array_map('preg_quote', $insurance_keyword_notations)) . ')/i';

                    if (preg_match($insurance_notation_pattern, $aftermarket_service["label"]) || preg_match($insurance_notation_pattern, $aftermarket_service["type"])) {

                        $current_fee_line_item = $line_calculator->calculate($aftermarket_service["amount"], 0, 1, $after_service_taxes['insurance_tax_type_primary'], $after_service_taxes['insurance_tax_type_secondary'], 0);
                        array_push($response['insurances'], [
                            'type'  => '',
                            'amount' => $aftermarket_service["amount"],
                            'deductible'    => 0,
                            'duration'  => 0,
                            'description' => $aftermarket_service["label"],

                            'tax_type'  => $current_fee_line_item['tax_type'],
                            'tax_percentage'    => $current_fee_line_item['tax_percentage'],
                            'tax_amount'    => $current_fee_line_item['tax_amount'],
                            'tax_type_secondary'    => $current_fee_line_item['tax_type_secondary'],
                            'tax_amount_secondary'  => $current_fee_line_item['tax_amount_secondary'],
                            'tax_percentage_secondary'  => $current_fee_line_item['tax_percentage_secondary'],
                            'tax_amount_total'  => $current_fee_line_item['tax_amount_total'],

                            'cost'  => 0,
                            'cost_tax_type' => $current_fee_line_item['tax_type'],
                            'cost_tax_percentage'   => $current_fee_line_item['tax_percentage'],
                            'cost_tax_amount'   => 0,
                            'cost_tax_type_secondary'   => $current_fee_line_item['tax_type_secondary'],
                            'cost_tax_amount_secondary' => 0,
                            'cost_tax_percentage_secondary' => $current_fee_line_item['tax_percentage_secondary'],
                            'cost_tax_amount_total' => 0
                        ]);
                        continue;
                    }

                    $aftermarket_service_as_warranty_keyword_notations = ["Maintenance"];
                    $aftermarket_service_as_fees_notation_pattern = '/(' . implode('|', array_map('preg_quote', $aftermarket_service_as_warranty_keyword_notations)) . ')/i';

                    if (preg_match($aftermarket_service_as_fees_notation_pattern, $aftermarket_service["label"])) {
                        $current_fee_line_item = $line_calculator->calculate($aftermarket_service["amount"], 0, 1, $default_taxes['tax_type'], $default_taxes['tax_type_secondary'], 0);
                        array_push($response['fees'], [
                            'name' =>  $aftermarket_service["label"],
                            'amount' => $aftermarket_service["amount"],
                            'description' => '',
                            'tax_type'  => $current_fee_line_item['tax_type'],
                            'tax_percentage'    => $current_fee_line_item['tax_percentage'],
                            'tax_amount'    => $current_fee_line_item['tax_amount'],
                            'tax_type_secondary'    => $current_fee_line_item['tax_type_secondary'],
                            'tax_amount_secondary'  => $current_fee_line_item['tax_amount_secondary'],
                            'tax_percentage_secondary'  => $current_fee_line_item['tax_percentage_secondary'],
                            'tax_amount_total'  => $current_fee_line_item['tax_amount_total'],

                            'cost'  => 0,
                            'cost_tax_type' => $current_fee_line_item['tax_type'],
                            'cost_tax_percentage'   => $current_fee_line_item['tax_percentage'],
                            'cost_tax_amount'   => 0,
                            'cost_tax_type_secondary'   => $current_fee_line_item['tax_type_secondary'],
                            'cost_tax_amount_secondary' => 0,
                            'cost_tax_percentage_secondary' => $current_fee_line_item['tax_percentage_secondary'],
                            'cost_tax_amount_total' => 0
                        ]);

                        continue;
                    }

                    $secondary_warranty_tax = !empty($after_service_taxes['warranty_tax_type_secondary']) ? $after_service_taxes['warranty_tax_type_secondary'] : $default_taxes['tax_type_secondary'];
                    $current_fee_line_item = $line_calculator->calculate($aftermarket_service["amount"], 0, 1, $default_taxes['tax_type'], $secondary_warranty_tax, 0);
                    $warranty = [
                        'tax_type'  => $current_fee_line_item['tax_type'],
                        'tax_percentage'    => $current_fee_line_item['tax_percentage'],
                        'tax_amount'    => $current_fee_line_item['tax_amount'],
                        'tax_type_secondary'    => $current_fee_line_item['tax_type_secondary'],
                        'tax_amount_secondary'  => $current_fee_line_item['tax_amount_secondary'],
                        'tax_percentage_secondary'  => $current_fee_line_item['tax_percentage_secondary'],
                        'tax_amount_total'  => $current_fee_line_item['tax_amount_total'],

                        'cost'  => 0,
                        'cost_tax_type' => $current_fee_line_item['tax_type'],
                        'cost_tax_percentage'   => $current_fee_line_item['tax_percentage'],
                        'cost_tax_amount'   => 0,
                        'cost_tax_type_secondary'   => $current_fee_line_item['tax_type_secondary'],
                        'cost_tax_amount_secondary' => 0,
                        'cost_tax_percentage_secondary' => $current_fee_line_item['tax_percentage_secondary'],
                        'cost_tax_amount_total' => 0
                    ];

                    if ($warranty_as_fee) {
                        $warranty['name'] = $aftermarket_service["label"];
                        $warranty['amount'] = $aftermarket_service["amount"];
                        $warranty['description'] = '';

                        array_push($response['fees'], $warranty);
                    } else {

                        $warranty['company_name'] = '';
                        $warranty['duration'] = 0;
                        $warranty['distance'] = 0;
                        $warranty['amount'] = $aftermarket_service["amount"];
                        $warranty['description'] = $aftermarket_service["label"];
                        array_push($response['warranties'], $warranty);
                        $warranty_as_fee = true;
                    }
                }
            }

            array_push(
                $inventory_mapping_attributes,
                [
                    "inventory_form_attribute" => "trade_value",
                    "data_form_attribute" => "tradeValue",
                ],
                [
                    "inventory_form_attribute" => "lien_value",
                    "data_form_attribute" => "lienValue",
                ]
            );


            if (!empty($data['creditApplicationData']['tradeIn'])) {
                $response['trade_in'] = [];
                if (!empty($data['creditApplicationData']['tradeIn']['tradeValue'])) {
                    $response['has_trade_in'] = true;
                    foreach ($inventory_mapping_attributes as $inventory_attribute) {
                        $response['trade_in'][$inventory_attribute['inventory_form_attribute']] =
                            $data['creditApplicationData']['tradeIn'][$inventory_attribute['data_form_attribute']];
                    }

                    $trade_value = $data['creditApplicationData']['tradeIn']['tradeValue'];

                    if (!empty($data['creditApplicationData']['tradeIn']['lienValue'])) {
                        $lien_payout = $data['creditApplicationData']['tradeIn']['lienValue'];
                    }

                    $trade_equity = $trade_value - $lien_payout;
                    $response['trade_in']['trade_equity'] = $trade_value;
                }
            }
        }

        $base_breakdown_line_calculation = $line_calculator->calculate($purchase_price, 0, 1, $default_taxes['tax_type'], $default_taxes['tax_type_secondary'], 0);
        $response['base_breakdown']['purchase_price'] = $purchase_price;
        $response['base_breakdown']['trade_value']  = $trade_value;
        $response['base_breakdown']['purchase_tax_type'] = $default_taxes['tax_type'];
        $response['base_breakdown']['purchase_tax_percentage'] = $base_breakdown_line_calculation['tax_percentage'];
        $response['base_breakdown']['purchase_tax_type_secondary'] = $default_taxes['tax_type_secondary'];
        $response['base_breakdown']['purchase_tax_percentage_secondary'] = $base_breakdown_line_calculation['tax_percentage_secondary'];
        $response['base_breakdown']['lien_payout'] = $lien_payout;
        $response['base_breakdown']['trade_equity'] = $trade_equity;


        if (!empty($data['creditApplicationData']['finance'])) {
            if (!empty($data['creditApplicationData']['finance']['emiAmount'])) {
                $response['finance']['lien_holder_name'] = $data['creditApplicationData']['finance']['lienHolderName'];
                $response['finance']['first_payment_date'] = $data['creditApplicationData']['finance']['firstPaymentDate'];
                $response['finance']['interest_amount'] = $data['creditApplicationData']['finance']['costOfBorrowing'];
                $response['finance']['emi_amount'] = $data['creditApplicationData']['finance']['emiAmount'];
                $response['finance']['payment_frequency'] = $data['creditApplicationData']['finance']['paymentFrequency'];
                $response['finance']['term'] =  $data['creditApplicationData']['finance']['term'];
                $response['finance']['interest_rate'] =  $data['creditApplicationData']['finance']['interestRate'];
                $response['finance']['override_finance_calculation'] = "1";
                $response['finance']['total_amount'] = $data['creditApplicationData']['finance']['totalAmountFinanced'];
            }
        }

        return $response;
    }

    public function getRegionIdentifier(string $region)
    {
        if (strcasecmp(trim($region), "Alberta") == 0) {
            return 'CA-AB';
        } else  if (strcasecmp(trim($region), "British Columbia") == 0) {
            return 'CA-BC';
        } else  if (strcasecmp(trim($region), "Manitoba") == 0) {
            return 'CA-MB';
        } else  if (strcasecmp(trim($region), "Ontario") == 0) {
            return 'CA-ON';
        } else  if (strcasecmp(trim($region), "Quebec") == 0) {
            return 'CA-QC';
        }

        return '';
    }


    public function getNonTaxableKeyWords()
    {
        return ["Non-Taxable", "Lender Admin", "Financing Related Fee", "Administration Fee", "Registration Fee", "Membership Share"];
    }
}
