<?php

namespace Tenant\app\Models;

use App\Models\SearchableModel;
use CodeIgniter\Model;

class SocialConnectListModel extends SearchableModel
{
    protected $table      = 'inventory';
    protected $primaryKey = 'inventory_id';

    protected $searchableColumns = [
        'year', 'make', 'model', 'trim', 'odometer',
        'price', 'exterior_color', 'interior_color', 'transmission',
        'body_type', 'drivetrain', 'stock_no', 'vin', 'engine'
    ];

    protected $allowedFields = [
        'vin_company',
        'vin', 'vindisplay', 'stock_no', 'stock_date', 'status', 'sub_status',
        'feature_listing', 'vehicle_type', 'visibility', 'vehicle_tags', 'title',
        'year', 'make', 'model', 'trim', 'body_type',
        'door', 'engine', 'litres', 'transmission', 'drivetrain',
        'passengers', 'odometer', 'km_miles', 'fuel_type', 'fuel_city',
        'fuel_hwy', 'exterior_color', 'interior_color', 'price', 'hide_price',
        'special_price_on', 'special_price', 'special_price_type', 'special_price_date', 'show_stripe',
        'financing_on', 'financing_special', 'downpayment', 'interest_rate', 'term',
        'payment_frequency', 'location', 'vehicle_description', 'custom_features',
        'technical_specification', 'generic_equipment', 'standard', 'optional',
        'syndication', 'created_at', 'updated_at', 'deleted_at', 'phonetic_string',
        'kijiji_category', 'autotrader_category', 'facebook_category'
    ];

    protected $useSoftDeletes = false;
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

    /**
     * findAll Overidding findAll method and returning the objects with Images Array.
     *
     * @param  mixed $limit
     * @param  mixed $offset
     * @return void
     */
    public function findAll(int $limit = 0, int $offset = 0)
    {
        $builder = $this->builder();

        $row = $builder
            ->select("inventory.* ,
            ( SELECT IF(inventory.special_price_on < 1, inventory.price, inventory.special_price )) as selling_price,
             GROUP_CONCAT( DISTINCT inventory_image.image_with_overlay_url ORDER BY inventory_image.sort_rank SEPARATOR '; ') as image_with_overlay_urls,
             GROUP_CONCAT( DISTINCT inventory_image.image_url ORDER BY inventory_image.sort_rank SEPARATOR '; ') as image_urls,
             COUNT( inventory_image.image_url) as image_count,
                GROUP_CONCAT(DISTINCT(social_connect.instagram_status)) as instagram_status,
                GROUP_CONCAT(DISTINCT(social_connect.google_status)) as google_status,
                GROUP_CONCAT(DISTINCT(social_connect.facebook_status)) as facebook_status,
                GROUP_CONCAT(DISTINCT(social_connect.created_at)) as socialconnect_created_at,
                GROUP_CONCAT(DISTINCT(social_connect.updated_at)) as socialconnect_updated_at
             ")
            ->join('inventory_image', 'inventory_image.inventory_id = inventory.inventory_id AND inventory_image.active = 1', 'inner')
            ->join('social_connect', 'inventory.stock_no = social_connect.stock_no', 'left')
            ->where('inventory.status', 'Instock')
            ->where('inventory.visibility', '1')
            ->where('inventory.deleted_at', NULL)
            ->like('syndication', 'socialconnect')
            ->groupBy('inventory.inventory_id')
            ->having('image_count > 0')
            ->having('selling_price > 0')
            ->limit($limit, $offset)
            ->orderBy('inventory.created_at', 'DESC')
            ->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row, 'limit' => $limit, 'offset' => $offset]);

        $this->tempReturnType     = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];
    }


    /**
     * findOneByInventoryId Method to fetch details of a single inventory by inventory_id.
     *
     * @param int $inventoryId
     * @return mixed
     */
    public function findOneByInventoryId(int $inventoryId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('inventory');
        $builder->select('inventory.*,
                (SELECT GROUP_CONCAT(image_url ORDER BY sort_rank  ASC SEPARATOR ";")
                 FROM inventory_image
                 WHERE inventory_id = inventory.inventory_id
                 AND active = 1 
                 ) as image_urls , (SELECT GROUP_CONCAT(image_with_overlay_url ORDER BY sort_rank ASC SEPARATOR ";")
                 FROM inventory_image
                 WHERE inventory_id = inventory.inventory_id
                 AND active = 1 
                 ) as image_with_overlay_urls' ) ;
        $builder->where('inventory.inventory_id', $inventoryId);
        return $builder->get()->getRowArray();
    }

    
    protected $beforeInsert = ['roundOfPrice', 'createPhoneticString'];
    protected $beforeUpdate = ['roundOfPrice', 'createPhoneticString'];

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
}
