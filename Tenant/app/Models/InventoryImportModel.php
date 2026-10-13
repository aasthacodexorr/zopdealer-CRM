<?php

namespace Tenant\app\Models;

use App\Models\ExactSearchableModel;
use Carbon\Carbon;

class InventoryImportModel extends ExactSearchableModel
{
    protected $table      = 'inventory';
    protected $primaryKey = 'inventory_id';

    protected $searchableColumns = [
        'year',
        'make',
        'model',
        'stock_no',
        'vin'
    ];

    protected $hashableColumns = [
        'vin_company',
        'vin',
        'vindisplay',
        'stock_no',
        'status',
        'sub_status',
        'feature_listing',
        'vehicle_type',
        'visibility',
        'vehicle_tags',
        'title',
        'year',
        'make',
        'model',
        'trim',
        'body_type',
        'door',
        'engine',
        'litres',
        'transmission',
        'drivetrain',
        'passengers',
        'odometer',
        'km_miles',
        'fuel_type',
        'fuel_city',
        'fuel_hwy',
        'exterior_color',
        'interior_color',
        'price',
        'hide_price',
        'special_price_on',
        'special_price',
        'special_price_type',
        'financing_on',
        'financing_special',
        'downpayment',
        'interest_rate',
        'term',
        'payment_frequency',
        'location',
        'vehicle_description',
        'custom_features',
        'technical_specification',
        'generic_equipment',
        'standard',
        'optional',
    ];

    protected $allowedFields = [
        'vin_company',
        'vin',
        'vindisplay',
        'stock_no',
        'stock_date',
        'status',
        'sub_status',
        'feature_listing',
        'vehicle_type',
        'visibility',
        'vehicle_tags',
        'title',
        'year',
        'make',
        'model',
        'trim',
        'body_type',
        'door',
        'engine',
        'litres',
        'transmission',
        'drivetrain',
        'passengers',
        'odometer',
        'km_miles',
        'fuel_type',
        'fuel_city',
        'fuel_hwy',
        'exterior_color',
        'interior_color',
        'price',
        'hide_price',
        'special_price_on',
        'special_price',
        'special_price_type',
        'special_price_date',
        'show_stripe',
        'financing_on',
        'financing_special',
        'downpayment',
        'interest_rate',
        'term',
        'payment_frequency',
        'location',
        'vehicle_description',
        'custom_features',
        'technical_specification',
        'generic_equipment',
        'standard',
        'optional',
        'syndication',
        'deleted_at',
        'phonetic_string',
        'kijiji_category',
        'autotrader_category',
        'facebook_category',
        'kijijinpv_category',
        'modification_hash'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'Tenant\app\Entities\InventoryImport';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'vin' => [
            "label" => 'Vin',
            "rules" => 'required'
        ],
        'stock_no' => [
            "label" => 'Stock Number',
            "rules" => 'required'
        ],
        'stock_date' => [
            "label" => 'Stock Date',
            "rules" => 'permit_empty|valid_date[Y-m-d]'
        ],
        'make' => [
            "label" => 'Make',
            "rules" => 'required'
        ],
        'model' => [
            "label" => 'Model',
            "rules" => 'required'
        ],
        'year' => [
            "label" => 'Year',
            "rules" => 'required|is_natural'
        ],
        'price'                        => [
            "label" => 'Price',
            "rules" => 'permit_empty|decimal'
        ],
        'special_price'                        => [
            "label" => 'Special Price',
            "rules" => 'permit_empty|decimal'
        ],
        'odometer'                        => [
            "label" => 'Odometer',
            "rules" => 'permit_empty|is_natural'
        ]
    ];

    protected $beforeInsert = ['upperCaseVIN', 'setStockDateIfNotAvailable', 'createKeyWordString', 'getCurrentHash'];
    protected $beforeUpdate = ['upperCaseVIN', 'getCurrentHash'];

    protected function upperCaseVIN(array $data)
    {
        if (!empty($data['data']['vin'])) {
            $data['data']['vin'] = strtoupper($data['data']['vin']);
        }
        return $data;
    }


    protected function setStockDateIfNotAvailable(array $data)
    {
        if (empty($data['data']['stock_date'])) {
            $data['data']['stock_date'] = Carbon::now()->format('Y-m-d');
        }

        return $data;
    }

    protected function getCurrentHash(array $data)
    {
        $hash = '';
        foreach ($this->hashableColumns as $key) {
            if (isset($data['data'][$key])) {

                switch ($key) {
                    case 'vehicle_description':
                        $hash .= strip_tags($data['data'][$key]);
                        break;

                    default:
                        $hash .= $data['data'][$key];
                        break;
                }
                $hash .= ' ';
            }
        }

        $data['data']['modification_hash'] = !empty($hash) ? md5($hash) : '';
        return $data;
    }
}
