<?php

namespace Tenant\app\Models;

use App\Models\SearchableModel;
use CodeIgniter\Model;

class DealTradeInModel extends Model
{
    protected $table      = 'deal_trade_in';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'deal_id',
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
        'created_at',
        'updated_at',
        'deleted_at',
        'trade_value',
        'lien_value',
        'trade_equity',
        'lien_holder'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Tenant\app\Entities\Inventory';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'deal_id' => [
            "label" => 'Deal Id',
            "rules" => 'required|is_unique[deal_trade_in.deal_id,id,{id}]'
        ],
        'make'                          => [
            "label" => 'Make',
            "rules" => 'required'
        ],
        'model'                        => [
            "label" => 'Model',
            "rules" => 'required'
        ],
        'year'                        => [
            "label" => 'Year',
            "rules" => 'required'
        ],
        'vin'                        => [
            "label" => 'Vin',
            "rules" => 'required'
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
        ],
        'stock_no'                        => [
            "label" => 'Stock Number',
            "rules" => 'permit_empty'
        ],

        'trade_value'                        => [
            "label" => 'Trade Value',
            "rules" => 'permit_empty|decimal'
        ],
        'lien_value'                        => [
            "label" => 'Lien Value',
            "rules" => 'permit_empty|decimal'
        ],
        'trade_equity'                        => [
            "label" => 'Trade Equity',
            "rules" => 'permit_empty|decimal'
        ],

    ];

    protected $beforeInsert = ['upperCaseVIN'];
    protected $beforeUpdate = ['upperCaseVIN'];

    protected function upperCaseVIN(array $data)
    {
        if (!empty($data['data']['vin'])) {
            $data['data']['vin'] = strtoupper($data['data']['vin']);
        }
        return $data;
    }
}
