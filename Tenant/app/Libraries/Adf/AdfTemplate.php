<?php

namespace Tenant\app\Libraries\Adf;

use Carbon\Carbon;
use Spatie\ArrayToXml\ArrayToXml;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\InventoryRepository;
use Tenant\app\Repositories\LocationRepository;

class AdfTemplate
{
    /**
     * data
     *
     * @var mixed
     */
    private $data = [];

    /**
     * vehicle_map
     *
     * @var array
     */
    private $vehicle_map = [
        [
            'data_attribute' => 'year',
            'xml_attribute' => 'year'
        ],
        [
            'data_attribute' => 'make',
            'xml_attribute' => 'make'
        ],
        [
            'data_attribute' => 'model',
            'xml_attribute' => 'model'
        ],
        [
            'data_attribute' => 'vin',
            'xml_attribute' => 'vin'
        ],
        [
            'data_attribute' => 'stock_no',
            'xml_attribute' => 'stock'
        ],
        [
            'data_attribute' => 'trim',
            'xml_attribute' => 'trim'
        ],
        [
            'data_attribute' => 'door',
            'xml_attribute' => 'doors'
        ],
        [
            'data_attribute' => 'body_type',
            'xml_attribute' => 'bodystyle'
        ],
        [
            'data_attribute' => 'transmission',
            'xml_attribute' => 'transmission'
        ],
        [
            'data_attribute' => 'odometer',
            'xml_attribute' => 'odometer',
            '_attributes' => ['status' => 'unknown'],
        ]
    ];

    /**
     * customer_map
     *
     * @var array
     */
    private $customer_map = [
        [
            'data_attribute' => 'first_name',
            'xml_attribute' => '__custom:name:1',
            '_attributes' => ['part' => 'first'],
        ],
        [
            'data_attribute' => 'last_name',
            'xml_attribute' => '__custom:name:2',
            '_attributes' => ['part' => 'last'],
        ],
        [
            'data_attribute' => 'email',
            'xml_attribute' => 'email',
            '_attributes' => ['preferredcontact' => '1']
        ],
        [
            'data_attribute' => 'phone',
            'xml_attribute' => '__custom:phone:1',
            '_attributes' => ['type' => 'voice'],
        ],
        [
            'data_attribute' => 'mobile_phone',
            'xml_attribute' => '__custom:phone:2',
            '_attributes' => ['type' => 'cellphone'],
        ],
        [
            'data_attribute' => 'address',
            'xml_attribute' => '__custom:address:1',
            '_attributes' => ['type' => 'home'],
            'sub_tree' => [
                [
                    'data_attribute' => 'home_address',
                    'xml_attribute' => 'street',
                    '_attributes' => ['line' => '1'],

                ],
                [
                    'data_attribute' => 'city',
                    'xml_attribute' => 'city'

                ],
                [
                    'data_attribute' => 'province',
                    'xml_attribute' => 'regioncode'

                ],
                [
                    'data_attribute' => 'postal_code',
                    'xml_attribute' => 'postalcode'

                ]
            ]
        ],
        [
            'data_attribute' => 'address',
            'xml_attribute' => '__custom:address:2',
            '_attributes' => ['type' => 'work'],
            'sub_tree' => [
                [
                    'data_attribute' => 'employer_address',
                    'xml_attribute' => 'street',
                    '_attributes' => ['line' => '1'],

                ],
                [
                    'data_attribute' => 'employer_city',
                    'xml_attribute' => 'city'

                ],
                [
                    'data_attribute' => 'employer_province',
                    'xml_attribute' => 'regioncode'

                ],
                [
                    'data_attribute' => 'employer_postal_code',
                    'xml_attribute' => 'postalcode'

                ]
            ]
        ],
        [
            'data_attribute' => 'message',
            'xml_attribute' => 'comments',
        ],
        [
            'data_attribute' => 'comments',
            'xml_attribute' => 'comments',
        ]
    ];

    /**
     * current_map
     *
     * @var array
     */
    private $current_map = [];


    private $location_map = [
        [
            'data_attribute' => 'email',
            'xml_attribute' => 'email',
        ],
        [
            'data_attribute' => 'phone',
            'xml_attribute' => 'phone',
            '_attributes' => ['type' => 'voice'],
        ],
        [
            'data_attribute' => 'fax',
            'xml_attribute' => 'fax',
            '_attributes' => ['type' => 'voice'],
        ],
        [
            'data_attribute' => 'address',
            'xml_attribute' => 'address',
            'sub_tree' => [
                [
                    'data_attribute' => 'street',
                    'xml_attribute' => 'street',
                    '_attributes' => ['line' => '1'],

                ],
                [
                    'data_attribute' => 'city',
                    'xml_attribute' => 'city'

                ],
                [
                    'data_attribute' => 'state',
                    'xml_attribute' => 'regioncode'

                ],
                [
                    'data_attribute' => 'zipcode',
                    'xml_attribute' => 'postalcode'

                ]
            ]
        ],
    ];


    private $configuration_repository;

    /**
     * __construct This accepts data from form
     *
     * @param  array $data
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->configuration_repository = new ConfigurationRepository();
    }

    /**
     * mapper
     *
     * @return string
     */
    public function mapper(): string
    {
        $this->current_map['requestdate'] = Carbon::now()->toIso8601String();

        $stock_no = !empty($this->data['stock_no']) ? $this->data['stock_no'] : "";
        $lead_source = !empty($this->data['source']) ? $this->data['source'] : "";
        $provider_name = (isset($this->data['provider_name']) && !empty($this->data['provider_name']))
            ? $this->data['provider_name'] : $this->getDefaultProviderName();

        $interest = "";
        $status = "used";

        switch ($lead_source) {
            case 'Web Lead - Trade-In':
                $interest = "trade-in";
                $status = "used";
                break;

            case 'Web Lead - Test Drive':
                $interest = "test-drive";
            default:
                # code...
                break;
        }

        $this->getInterestVehicleData($stock_no, $status, $interest);
        $this->customerData();
        $this->locationData();

        $this->providerData($lead_source, $provider_name);

        $xml = [];
        $xml['prospect'] = $this->current_map;

        $arrayToXml = new ArrayToXml($xml, 'adf');
        $arrayToXml->dropXmlDeclaration();
        return '<?xml version="1.0" encoding="UTF-8"?><?ADF version "1.0"?>' . $arrayToXml->toXml();
    }

    private function getInterestVehicleData(String $stock_no = "", String $status = "", String $interest = "")
    {
        $vehicle = (new InventoryRepository())->findSingleInventoryByStockId($stock_no);

        if (!empty($vehicle) && !empty($stock_no)) {
            $status = $vehicle['vehicle_type'] == "New" ? 'new' : 'used';
            $interest = empty($interest) ? "buy" : $interest;

            $this->current_map['vehicle'] = [];
            $this->current_map['vehicle']['_attributes'] = [
                'status' => $status,
                'interest' => $interest
            ];

            foreach ($this->vehicle_map as $key => $each_field) {
                if (!empty($vehicle[$each_field['data_attribute']])) {
                    $this->current_map['vehicle'][$each_field['xml_attribute']] =
                        ['_value' =>  $vehicle[$each_field['data_attribute']]];

                    if (!empty($each_field['_attributes'])) {
                        $this->current_map['vehicle'][$each_field['xml_attribute']]['_attributes']
                            = $each_field['_attributes'];
                    }
                }
            }
        } elseif (
            !empty($this->data['year'])
            || !empty($this->data['model'])
            || !empty($this->data['make'])
        ) {
            $this->current_map['vehicle'] = [];
            $this->current_map['vehicle']['_attributes'] = [
                'status' => $status,
                'interest' => $interest
            ];

            $vehicle_indexes = ['year', 'make', 'model', 'odometer', 'vin'];
            foreach ($vehicle_indexes as $single_index) {
                if (!empty($this->data[$single_index])) {
                    $this->current_map['vehicle'][$single_index] = $this->data[$single_index];
                }
            }
        }
    }

    private function customerData()
    {

        $this->current_map['customer'] = [];
        $this->current_map['customer']['contact'] = [];
        foreach ($this->customer_map as $key => $each_field) {
            if (!empty($this->data[$each_field['data_attribute']])  && empty($each_field['sub_tree'])) {
                $this->current_map['customer']['contact'][$each_field['xml_attribute']] =
                    ['_value' => $this->data[$each_field['data_attribute']]];

                if (!empty($each_field['_attributes'])) {
                    $this->current_map['customer']['contact'][$each_field['xml_attribute']]['_attributes']
                        = $each_field['_attributes'];
                }
            } elseif (!empty($each_field['sub_tree'])) {
                $this->current_map['customer']['contact'][$each_field['xml_attribute']] = [];

                if (!empty($each_field['_attributes'])) {
                    $this->current_map['customer']['contact'][$each_field['xml_attribute']]['_attributes']
                        = $each_field['_attributes'];
                }

                foreach ($each_field['sub_tree'] as $sub_key => $each_sub_key) {

                    if (!empty($this->data[$each_sub_key['data_attribute']])) {
                        $this->current_map['customer']['contact'][$each_field['xml_attribute']][$each_sub_key['xml_attribute']] =
                            ['_value' => $this->data[$each_sub_key['data_attribute']]];
                    }
                }
            }
        }

        $comment_attributes = ['message', 'comments'];

        foreach ($comment_attributes as $key) {
            if (!empty($this->data[$key])) {
                $this->current_map['customer']['comments'] = $this->data[$key];
            }
        }

        $this->overrideCommentWithExtendedData();
    }

    private function locationData()
    {
        $location = (new LocationRepository())->getLocationByName();

        if (!empty($location)) {
            $this->current_map['vendor'] = [];
            $this->current_map['vendor']['vendorname'] = $location['name'];
            $this->current_map['vendor']['contact'] = [
                '_attributes' => ['primarycontact' => '1']
            ];

            foreach ($this->location_map as $key => $each_field) {
                if (!empty($location[$each_field['data_attribute']]) && empty($each_field['sub_tree'])) {
                    $this->current_map['vendor']['contact'][$each_field['xml_attribute']] =
                        ['_value' =>  $location[$each_field['data_attribute']]];


                    if (!empty($each_field['_attributes'])) {
                        $this->current_map['vendor']['contact'][$each_field['xml_attribute']]['_attributes']
                            = $each_field['_attributes'];
                    }
                } elseif (!empty($each_field['sub_tree'])) {
                    $this->current_map['vendor']['contact'][$each_field['xml_attribute']] = [];
                    foreach ($each_field['sub_tree'] as $sub_key => $each_sub_key) {

                        if (!empty($location[$each_sub_key['data_attribute']])) {
                            $this->current_map['vendor']['contact'][$each_field['xml_attribute']][$each_sub_key['xml_attribute']] =
                                ['_value' =>  $location[$each_sub_key['data_attribute']]];
                        }
                    }
                }
            }
        }
    }

    private function providerData(String $lead_source = "", String $provider_name = "Zop Media")
    {
        $this->current_map['provider'] = [];
        $this->current_map['provider']['name'] = $provider_name;
        $this->current_map['provider']['service'] = $lead_source;
        $this->current_map['provider']['url'] = "https://www.zopmedia.com";
        $this->current_map['provider']['email'] = "support@zopmedia.com";
    }

    private function getDefaultProviderName(): String
    {
        try {
            $adf_default_provider_name =  $this->configuration_repository->findConfigurationColumnsByKey('adf_default_provider_name', 'value')->value;
            return $adf_default_provider_name;
        } catch (\Exception $ex) {
            return "Zop Media";
        }
    }

    private function overrideCommentWithExtendedData(): void
    {
        try {
            $override_comment_with_extended_data =  $this->configuration_repository->findConfigurationColumnsByKey('override_comment_with_extended_data', 'value')->value;
            $override_comment_with_extended_data =  !empty($override_comment_with_extended_data);

            if ($override_comment_with_extended_data && !empty($this->data['other_data'])) {
                $other_data = json_decode($this->data['other_data'], true);

                if (is_array($other_data)) {
                    $attribs = $other_data;
                    $formatted_attribs = array_reduce(
                        array_keys($attribs),
                        function ($carry, $key) use ($attribs) {
                            $formatted_key = ucwords(str_replace('_', ' ', $key));
                            $formatted_string =  $carry . ' ' . $formatted_key . ': ' . htmlspecialchars($attribs[$key]);
                            $formatted_string .= ' ' . trim('
                            ', '\t ');
                            return $formatted_string;
                        },
                        trim('
                        ', '\t ')
                    );

                    $this->current_map['customer']['comments'] = $formatted_attribs;
                }
            }
        } catch (\Exception $ex) {
            log_message('warning', $ex->getMessage());
        }
    }
}
