<?php

namespace Tenant\app\Models;

use App\Models\ExactSearchableModel;

class InventoryModel extends ExactSearchableModel
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
        'created_at',
        'updated_at',
        'deleted_at',
        'phonetic_string',
        'kijiji_category',
        'autotrader_category',
        'facebook_category',
        'kijijinpv_category',
        'search_keyword',
        'odometer_numeric',
        'selling_price_numeric',
        'modification_hash',
        'days_in_stock'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'Tenant\app\Entities\Inventory';
    protected $useTimestamps = true;

    protected $validationRules    = [
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
            "rules" => 'required|is_unique[inventory.stock_no,inventory_id,{inventory_id}]'
        ],

    ];

    public function findAll(int $limit = 0, int $offset = 0)
    {
        $builder = $this->builder();
        $db = \Config\Database::connect();

        $imageSubquery = $db->table('inventory_image')
            ->select("
                    inventory_id,
                    GROUP_CONCAT(DISTINCT image_with_overlay_url ORDER BY sort_rank SEPARATOR '; ') AS image_with_overlay_urls,
                    GROUP_CONCAT(DISTINCT image_url ORDER BY sort_rank SEPARATOR '; ') AS image_urls,
                    GROUP_CONCAT(DISTINCT updated_at ORDER BY sort_rank SEPARATOR '; ') AS image_timestamps,
                    COUNT(image_url) AS image_count
                ", false)
            ->where('active', 1)
            ->groupBy('inventory_id');


        $fileSubquery = $db->table('inventory_file')
            ->select("
                inventory_id,
                GROUP_CONCAT(DISTINCT header, SPACE(10), file_url SEPARATOR ';') AS files
            ", false)
            ->groupBy('inventory_id');

        if ($this->tempUseSoftDeletes === true) {
            $builder->where($this->table . '.' . $this->deletedField, null);
        }

        $row = $builder
            ->select("inventory.* ,
             img.image_with_overlay_urls,
             img.image_urls,
             img.image_timestamps,
            files.files,
            COALESCE(img.image_count, 0) AS image_count", false)
            ->join(
                '(' . $imageSubquery->getCompiledSelect() . ') img',
                'img.inventory_id = inventory.inventory_id',
                'left'
            )
            ->join(
                '(' . $fileSubquery->getCompiledSelect() . ') files',
                'files.inventory_id = inventory.inventory_id',
                'left'
            )
            ->groupBy('inventory.inventory_id')
            ->limit($limit, $offset)
            ->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row, 'limit' => $limit, 'offset' => $offset]);

        $this->tempReturnType     = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];
    }

    protected $beforeInsert = ['upperCaseVIN', 'roundOfPrice', 'createKeyWordString', 'getCurrentHash'];
    protected $beforeUpdate = ['upperCaseVIN', 'roundOfPrice', 'createKeyWordString', 'getCurrentHash'];

    protected function roundOfPrice(array $data)
    {
        if (isset($data['data']['price'])) {
            if (is_numeric($data['data']['price'])) {
                $data['data']['price'] = (int) $data['data']['price'];
            }
        }

        if (isset($data['data']['special_price'])) {
            if (is_numeric($data['data']['special_price'])) {
                $data['data']['special_price'] = (int) $data['data']['special_price'];
            }
        }

        return $data;
    }

    protected function upperCaseVIN(array $data)
    {
        if (!empty($data['data']['vin'])) {
            $data['data']['vin'] = strtoupper($data['data']['vin']);
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