<?php

namespace Tenant\app\Repositories;

use App\Models\SearchableModel;
use App\Repositories\CRUDWithAdvanceRepository;
use CodeIgniter\Model;
use Tenant\app\Models\CraigslistModel;

/**
 * CraigslistRepository CRUD and pagination for the Craigslsit Logs
 */
class CraigslistRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new CraigslistModel());
        $this->entity = 'CodeIgniter\Entity';
        $this->entityLabel = 'Craigslist Log';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'craigslist_id';
    }


    public function createOrUpdate(array $data): array
    {
        try {

            if (empty($data['stock_no'])) {
                throw new \Exception("Stock No Missing.", 1);
            }

            if (empty($data['location'])) {
                throw new \Exception("Location Missing.", 1);
            }

            $instance = $this->model->where('stock_no', $data['stock_no'])->where('location', $data['location'])->asArray()->first();

            if (empty($instance)) {
                return parent::create($data);
            } else {
                return parent::update($instance['id'], $data);
            }
        } catch (\Exception $e) {

            return ["errors" => $e->getMessage()];
        }
    }

    public function show($id): ?array
    {
        return $this->model->where("post_id", $id)->asArray()->first();
    }

    public function delete($id)
    {
        return $this->model->where("post_id", $id)->delete();
    }

    public function filterDataAndPurgeDelete(String $column = '', String $value = ''): void
    {
        if (!empty($column)) {
            $this->model->where($column, $value)->delete();
        } else {
            $this->model->where("1 = 1")->delete();
        }

        $this->model->purgeDeleted();
    }

    public function checkForInventoryUpdate()
    {
        $all_locations = $this->allColumnAggregation([]);

        foreach ($all_locations['location'] as $location) {

            $inventoryRepository = new InventoryRepository();
            $inventoryRepository->setModel($this->dynamicLocationBasedModel($location['name']));
            //iterate through them
            $craigslist_inventories = $inventoryRepository->index(null, null, null, null, null, null, false, 'inventory_view_id', 'inventory_listing');

            foreach ($craigslist_inventories['inventory_listing'] as $inventory) {
                if ($inventory['modification_hash'] !== $inventory['current_modification_hash']) {
                    if (in_array($inventory['craigslist_status'], ["PUBLISHED", "UPDATED"]) && !empty($inventory['post_id'])) {
                        $this->createOrUpdate([
                            "status" => 'TO BE UPDATED',
                            "stock_no" => $inventory['stock_no'],
                            "current_modification_hash" => $inventory['modification_hash'],
                            "location" => $location['name']
                        ]);
                    }
                }
            }
        }


        //update
    }

    public function dynamicLocationBasedModel(String $location): Model
    {
        return new class($location) extends SearchableModel
        {
            protected $table      = 'inventory';
            protected $primaryKey = 'inventory_id';

            private $location;

            public function __construct($location)
            {
                $this->location = $location;
            }

            protected $searchableColumns = [
                'year',
                'make',
                'model',
                'trim',
                'odometer',
                'price',
                'exterior_color',
                'interior_color',
                'transmission',
                'body_type',
                'drivetrain',
                'stock_no',
                'vin',
                'engine'
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
                'facebook_category'
            ];

            protected $useSoftDeletes = true;
            protected $returnType    = 'Tenant\app\Entities\Inventory';
            protected $useTimestamps = true;

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

                if ($this->tempUseSoftDeletes === true) {
                    $builder->where($this->table . '.' . $this->deletedField, null);
                }

                $row = $builder
                    ->select("inventory.* ,
            ( SELECT IF(inventory.special_price_on < 1, inventory.price, inventory.special_price )) as selling_price,
             GROUP_CONCAT( DISTINCT inventory_image.image_with_overlay_url ORDER BY inventory_image.sort_rank SEPARATOR '; ') as image_with_overlay_urls,
             GROUP_CONCAT( DISTINCT inventory_image.image_url ORDER BY inventory_image.sort_rank SEPARATOR '; ') as image_urls,
             COUNT( inventory_image.image_url) as image_count,
             GROUP_CONCAT( DISTINCT inventory_disclosures.answer) as disclosure_brand_type,
             GROUP_CONCAT(DISTINCT(craigslist.post_id)) as post_id,
             GROUP_CONCAT(DISTINCT(craigslist.status)) as craigslist_status,
             GROUP_CONCAT(DISTINCT(craigslist.current_modification_hash)) as current_modification_hash,
             GROUP_CONCAT(DISTINCT(craigslist.url)) as craigslist_url,
             GROUP_CONCAT(DISTINCT(craigslist.created_at)) as craigslist_created_at,
             GROUP_CONCAT(DISTINCT(craigslist.updated_at)) as craigslist_updated_at
             ")
                    ->join('inventory_image', 'inventory_image.inventory_id = inventory.inventory_id AND inventory_image.active = 1', 'inner')
                    ->join('craigslist', 'inventory.stock_no = craigslist.stock_no AND craigslist.location = "' . $this->location . '"', 'left')
                    ->join('inventory_disclosures', 'inventory.inventory_id = inventory_disclosures.inventory_id AND inventory_disclosures.question = "Brand" ', 'left')
                    ->where('inventory.status', 'Instock')
                    ->where('inventory.visibility', '1')
                    ->where('inventory.deleted_at', NULL)
                    ->like('syndication', 'craigslist')
                    ->groupBy('inventory.inventory_id')
                    ->having('image_count > 1')
                    ->having('selling_price > 0')
                    ->limit($limit, $offset)
                    ->get();

                $row = $row->getResult($this->tempReturnType);

                $eventData = $this->trigger('afterFind', ['data' => $row, 'limit' => $limit, 'offset' => $offset]);

                $this->tempReturnType     = $this->returnType;
                $this->tempUseSoftDeletes = $this->useSoftDeletes;

                return $eventData['data'];
            }
        };
    }

    public function allColumnAggregation($query)
    {
        $columns = [
            ['name' => 'location', 'type' => null],

        ];


        $result = [];
        foreach ($columns as $column) {
            if ($column['type'] == 'numeric') {
                $this->allQueries($query);
                $result[$column['name']] = $this->model->select($column['name'] . ' as name')->selectCount($column['name'], 'count')->where("deleted_at", NULL)->groupBy($column['name'])->orderBy(ABS($column['name']), 'ASC')->get()->getResultArray();
            } else {
                $this->allQueries($query);
                $result[$column['name']] = $this->model->select($column['name'] . ' as name')->selectCount($column['name'], 'count')->where("deleted_at", NULL)->groupBy($column['name'])->orderBy($column['name'], 'ASC')->get()->getResultArray();
            }
        }

        return $result;
    }
}
