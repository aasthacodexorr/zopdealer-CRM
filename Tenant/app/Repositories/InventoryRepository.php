<?php

namespace Tenant\app\Repositories;

use App\Entities\MasterEntity;
use App\Helpers\Utility;
use App\Helpers\SyndicationUtility;
use App\Libraries\CloudStorage\CloudStorageFactory;
use App\Repositories\AdvanceRepository;
use Tenant\app\Models\InventoryModel;
use Tenant\app\Entities\Inventory;
use Carbon\Carbon;
use CodeIgniter\Cache\CacheFactory;
use Config\NonPersistantCache;
use Tenant\app\Libraries\CodeItNow\BarcodeBundle\Utils\QrCode;
use Exception;
use Tenant\app\Libraries\IndexService\Typesense;
use Tenant\app\Libraries\StockNumberGenerator\StockNumberGeneratorFactory;
use Tenant\app\Libraries\WebsiteCache\WebsiteCacheFactory;
use Tenant\app\Models\InventoryImportImageModel;
use Tenant\app\Models\InventoryNoteModel;
use Tenant\app\Models\InventoryWithDeletedModel;

/**
 * InventoryRepository CRUD and pagination for the Inventory Items
 * @DEPRECATED This is deprecated in favor of InventoryRepositoryV2.
 * It will be removed once all the functionalities are moved to  InventoryRepositoryV2
 */
class InventoryRepository extends AdvanceRepository
{

    /**
     * image_repository
     *
     * @var ImageRepository
     */
    private $image_repository;

    public function __construct()
    {
        parent::__construct(new InventoryModel());
        $this->image_repository = new ImageRepository();
    }

    public function show($id): ?array
    {

        return $this->model->asArray()->find($id);
    }

    public function storeImage($data)
    {
        $inventoryImageModel = new InventoryImportImageModel();
        $inventoryImageModel->save($data);
        return $inventoryImageModel->insertID;
    }

    public function imageList($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('inventory_image');
        $builder->select('*');
        $builder->where('inventory_id', $id);
        $builder->orderBy('sort_rank', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function onlyActiveImagesWithStaticImages($id)
    {
        $gallery = $this->imageList($id);
        $gallery = array_filter($gallery, function ($image) {
            return $image['active'] == '1' ? true : false;
        });

        $static_images = $this->image_repository->getAllActiveStaticImages();

        if (!empty($static_images)) {
            foreach ($static_images as $static_image) {
                array_splice($gallery, (int) $static_image['index'] - 1, 0, [$static_image]);
            }
        }

        return ($gallery);
    }

    public function lastRank($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('inventory_image');
        $builder->selectMax('sort_rank', 'last_rank');
        $builder->where('inventory_id', $id);
        return $builder->get()->getResultArray()[0]['last_rank'];
    }

    public function updateSortRank($rows)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('inventory_image');
        $builder->updateBatch($rows, 'id');
    }

    public function deleteImages($ids, $remove_from_cdn = false)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('inventory_image');
        foreach ($ids as $id) {
            $builder->select('*');
            $builder->where('id', $id);
            $row = $builder->get()->getResultArray()[0];
            $image_with_overlay_url = $row['image_with_overlay_url'];
            $image_url = $row['image_url'];

            if ($remove_from_cdn) {
                if (gettype($image_url) == "string") {
                    CloudStorageFactory::getInstance()->delete($image_url);
                }

                if (gettype($image_with_overlay_url) == "string") {
                    CloudStorageFactory::getInstance()->delete($image_with_overlay_url);
                }
            }


            $builder->delete(['id' => $id]);
        }
    }

    public function deleteImagesPathByInventoryID($inventory_id)
    {
        try {
            if (!empty($inventory_id)) {
                $db = \Config\Database::connect();
                $builder = $db->table('inventory_image');
                $builder->delete(['inventory_id' => $inventory_id]);
            }
        } catch (\Exception $ex) {
            log_message('critical', $ex->getMessage());
        }
    }

    public function purgeDeleteInventoryImagesWithOverlay($reset = true)
    {
        $db = \Config\Database::connect();

        CloudStorageFactory::getInstance()->emptyFolder('/' . UPLOAD_FOLDER . '/' . md5(db_connect()->database) . '/' . OVERLAY_FOLDER . '/');

        if ($reset) {
            $builder = $db->table('inventory_image');
            $builder->update(["image_with_overlay_url" => null]);
        } else {

            $prefix = getEnv('image.cdn');
            $columnToUpdate = 'image_with_overlay_url';
            $columnSource = 'image_url';


            $builder = $db->table('inventory_image');
            $builder->set($columnToUpdate, "CONCAT('{$prefix}', {$columnSource})", FALSE);
            $builder->notLike("image_url", "http");
            $builder->update();


            $builder = $db->table('inventory_image');
            $builder->set($columnToUpdate, $columnSource, FALSE);
            $builder->like("image_url", "http");
            $builder->update();
        }
    }

    public function changeActive($ids, $activeStatus)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('inventory_image');
        foreach ($ids as $id) {
            $builder->where('id', $id);
            $builder->update(['active' => $activeStatus]);
        }
    }

    public function storeFile($data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('inventory_file');
        $builder->replace($data);
    }

    public function deleteFile($header, $inventory_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('inventory_file');
        $builder->select('*');
        $builder->where('inventory_id', $inventory_id);
        $builder->where('header', $header);
        $builder->limit(1);
        $result = $builder->get()->getResultArray();
        $id = -1;
        if (count($result) > 0) {
            $id = $result[0]['id'];
            $file_path = $result[0]['file_url'];

            if (gettype($file_path) == "string") {
                CloudStorageFactory::getInstance()->delete($file_path);
            }
        }
        $builder->where('id', $id)->delete();
        return ['success' => 'Attachment deleted successfully.'];
    }

    public function deleteImportFilePath($header, $inventory_id)
    {
        if (!empty($header) && !empty($inventory_id)) {
            try {
                $db = \Config\Database::connect();
                $builder = $db->table('inventory_file');
                $builder->delete(['inventory_id' => $inventory_id, 'header' => $header]);
            } catch (\Exception $ex) {
                log_message('critical', $ex->getMessage());
            }
        }
    }

    public function getFile($header, $inventory_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('inventory_file');
        $builder->select('*');
        $builder->where('inventory_id', $inventory_id);
        $builder->where('header', $header);
        return $builder->get()->getResultArray();
    }

    public function fileList($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('inventory_file');
        $builder->select('*');
        $builder->where('inventory_id', $id);
        return $builder->get()->getResultArray();
    }

    /**
     * columnAggregation : Method to aggregate unique values from the column
     * @param $column : Requires Column For Aggregation
     * @param $type : $type is the type of comparision that is supposed to be done. Eg : numeric
     * @return $data : Array
     */
    public function columnAggregation($column, $type = null)
    {
        if ($type == 'numeric') {
            $columns =  $this->model->select($column . ' as name')->selectCount($column, 'count')->groupBy($column)->orderBy(ABS($column), 'ASC')->get()->getResultArray();

            usort($columns, function ($a, $b) {
                $a['name'] = $a['name'] == '' ? 0 : $a['name'];
                $b['name'] = $b['name'] == '' ? 0 : $b['name'];
                if ((int) $a['name'] == (int) $b['name']) {
                    return 0;
                }

                if ((int) $a['name'] < (int) $b['name']) {
                    return -1;
                }
                return 1;
            });
            return $columns;
        } else {
            return $this->model->select($column . ' as name')->selectCount($column, 'count')->groupBy($column)->orderBy($column, 'ASC')->get()->getResultArray();
        }
    }

    //TODO : Refactor allColumnAggregation and prepareDataForFilter to enable it to other Repositories
    public function allColumnAggregation($query, $status = false, $search = '')
    {
        $columns = [
            ['name' => 'make', 'type' => null],
            ['name' => 'model', 'type' => null],
            ['name' => 'year', 'type' => null],
            ['name' => 'trim', 'type' => null],
            ['name' => 'price', 'type' => 'numeric'],
            ['name' => 'interior_color', 'type' => null],
            ['name' => 'exterior_color', 'type' => null],
            ['name' => 'body_type', 'type' => null],
            ['name' => 'transmission', 'type' => null],
            ['name' => 'fuel_city', 'type' => 'numeric'],
            ['name' => 'fuel_hwy', 'type' => 'numeric'],
            ['name' => 'vehicle_type', 'type' => null],
            ['name' => 'fuel_type', 'type' => null],
            ['name' => 'drivetrain', 'type' => null],

        ];

        if ($status == true) {
            array_push($columns, ['name' => 'status', 'type' => null]);
        }


        $result = [];
        foreach ($columns as $column) {
            if ($column['type'] == 'numeric') {
                $this->onlyVisible();
                if ($search != '') {
                    $this->search($search);
                }
                $this->allQueries($query);
                $result[$column['name']] = $this->model->select($column['name'] . ' as name')->selectCount($column['name'], 'count')->where("deleted_at", NULL)->groupBy($column['name'])->orderBy(ABS($column['name']), 'ASC')->get()->getResultArray();
            } else {
                $this->onlyVisible();
                if ($search != '') {
                    $this->search($search);
                }
                $this->allQueries($query);
                $result[$column['name']] = $this->model->select($column['name'] . ' as name')->selectCount($column['name'], 'count')->where("deleted_at", NULL)->groupBy($column['name'])->orderBy($column['name'], 'ASC')->get()->getResultArray();
            }
        }

        return $result;
    }

    /**
     * @method prepareDataForFilter : prepares data recieved from the website to make search consistent.
     * @param $data : $data recieved.
     * @return $query : String
     */

    public function prepareDataForFilter($data)
    {
        $query = [];
        if (count($data) > 0) {
            // var_dump($data);
            foreach ($data as $key => $value) {
                if (
                    $key == 'zm_vehicle_type' || $key == 'zm_body_type' ||
                    $key == 'zm_year' || $key == 'zm_fuel_type' ||
                    $key == 'zm_exterior_color' || $key == 'zm_model' ||
                    $key == 'zm_make' || $key == 'zm_transmission' || $key == 'zm_status'
                    || $key == 'zm_sub_status' || $key == 'zm_stock_no' || $key == 'zm_inventory_id'
                    || $key == 'zm_drivetrain'

                ) {
                    $single_query = [
                        'header' => null,
                        'column' => str_replace('zm_', '', $key),
                        'join' => 'AND',
                        'comparator' => "is",
                        "type" => "Multi Select",
                        'value_1' => $value,
                        'value_2' => null,
                    ];

                    array_push($query, $single_query);
                } else if ($key == 'zm_price' || $key == 'zm_fuel_hwy' || $key == 'zm_fuel_city') {
                    // foreach ($value as $combined_value) {
                    $column = str_replace('zm_', '', $key);
                    $join = 'AND';
                    $value_2 = $key == 'zm_fuel_hwy' || $key == 'zm_fuel_city' ? 0.01 : 1;
                    // $value_1 =   explode('-', $combined_value)[0];
                    // $value_2 =   explode('-', $combined_value)[1];

                    $single_query = [
                        'header' => null,
                        'column' => $column,
                        'join' => $join,
                        'comparator' => "between",
                        "type" => 'Numeric Range Comparision',
                        'value_1' => $value,
                        'value_2' => $value_2,
                    ];

                    array_push($query, $single_query);
                    //}
                } else if ($key == "zm_dis") {
                    $single_query = [
                        'header' => null,
                        'column' => 'days_in_stock',
                        'join' => 'AND',
                        'comparator' => "less than",
                        "type" => "Numeric Comparision",
                        'value_1' => $value,
                        'value_2' => null,
                    ];
                    array_push($query, $single_query);
                } else if ($key == "zm_recent_stock_date") {
                    $single_query = [
                        'header' => null,
                        'column' => 'stock_date',
                        'join' => 'AND',
                        'comparator' => "less than",
                        "type" => "Relative Time Period Comparision",
                        'value_1' => $value,
                        'value_2' => null,
                    ];
                    array_push($query, $single_query);
                } else if ($key == "zm_feature_listing" || $key == "zm_visibility") {
                    $single_query = [
                        'header' => null,
                        'column' => str_replace('zm_', '', $key),
                        'join' => 'AND',
                        'comparator' => "is",
                        "type" => "Single Select",
                        'value_1' => $value,
                        'value_2' => null,
                    ];
                    array_push($query, $single_query);
                }
            }
        }

        return json_encode($query);
    }

    /**
     * onlyVisible Filters to show inventory with visible flag.
     *
     * @return object
     */
    public function onlyVisible()
    {
        $this->model->where($this->model->table . '.visibility', 1);
        return $this;
    }

    /**
     * replacePlaceholders Replaces the placeholders with intended values
     *
     * @param  array $single_inventory
     * @return array
     */
    public function replacePlaceholders($single_inventory): array
    {
        $fmt = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
        $vin = $single_inventory['vin'];
        $make = $single_inventory['make'];
        $trim = $single_inventory['trim'];
        $model = $single_inventory['model'];
        $year = $single_inventory['year'];
        $stock_no = $single_inventory['stock_no'];
        $body_type = $single_inventory['body_type'];
        $door = $single_inventory['door'];
        $displacement = !empty($single_inventory['litres']) ? $single_inventory['litres'] . 'L' : '';
        $cylinders = $single_inventory['engine'];
        $transmission = $single_inventory['transmission'];
        $drivetrain = $single_inventory['drivetrain'];
        $passengers = $single_inventory['passengers'];
        $fuel_type = $single_inventory['fuel_type'];
        $fuel_city = $single_inventory['fuel_city'];
        $fuel_hwy = $single_inventory['fuel_hwy'];
        $exterior_color = $single_inventory['exterior_color'];
        $interior_color = $single_inventory['interior_color'];
        $odometer = !empty($single_inventory['odometer']) && is_numeric($single_inventory['odometer']) ? number_format($single_inventory['odometer']) . ' ' . $single_inventory['km_miles'] : '';
        $location = is_null($single_inventory['location']) ? "" : $single_inventory['location'];

        $price = !empty($single_inventory['price']) && is_numeric($single_inventory['price']) && $single_inventory['price'] != 0 ?  $fmt->formatCurrency($single_inventory['price'], 'USD') : '';
        $special_price = !empty($single_inventory['special_price']) && is_numeric($single_inventory['special_price']) && $single_inventory['special_price'] != 0  ?  $fmt->formatCurrency($single_inventory['special_price'], 'USD') : '';
        $selling_price = $single_inventory['special_price_on'] == '1' ? $special_price : $price;

        $vehicle_tags = SyndicationUtility::parseTags($single_inventory['vehicle_tags'], ' <br/> ');


        $single_inventory['title'] = str_replace("{Vin}", $vin, $single_inventory['title']);
        $single_inventory['title'] = str_replace("{Make}", $make, $single_inventory['title']);
        $single_inventory['title'] = str_replace("{Trim}", $trim, $single_inventory['title']);
        $single_inventory['title'] = str_replace("{Model}", $model, $single_inventory['title']);
        $single_inventory['title'] = str_replace("{Year}", $year, $single_inventory['title']);

        $single_inventory['title'] = str_replace("{Stock Number}", $stock_no, $single_inventory['title']);
        $single_inventory['title'] = str_replace("{Door}", $door, $single_inventory['title']);
        $single_inventory['title'] = str_replace("{Body Type}", $body_type, $single_inventory['title']);
        $single_inventory['title'] = str_replace("{Displacement}", $displacement, $single_inventory['title']);
        $single_inventory['title'] = str_replace("{Cylinders}", $cylinders, $single_inventory['title']);
        $single_inventory['title'] = str_replace("{Transmission}", $transmission, $single_inventory['title']);
        $single_inventory['title'] = str_replace("{Drivetrain}", $drivetrain, $single_inventory['title']);
        $single_inventory['title'] = str_replace("{Passengers}", $passengers, $single_inventory['title']);

        $single_inventory['title'] = str_replace("{Fuel Type}", $fuel_type, $single_inventory['title']);
        $single_inventory['title'] = str_replace("{Fuel Consumption City}", $fuel_city, $single_inventory['title']);
        $single_inventory['title'] = str_replace("{Fuel Consumption HWY}", $fuel_hwy, $single_inventory['title']);
        $single_inventory['title'] = str_replace("{Odometer}", $odometer, $single_inventory['title']);
        $single_inventory['title'] = str_replace("{Exterior Colour}", $exterior_color, $single_inventory['title']);
        $single_inventory['title'] = str_replace("{Interior Colour}", $interior_color, $single_inventory['title']);
        $single_inventory['title'] = str_replace("{Price}", $price, $single_inventory['title']);
        $single_inventory['title'] = str_replace("{Special Price}", $special_price, $single_inventory['title']);
        $single_inventory['title'] = str_replace("{Location}", $location, $single_inventory['title']);
        $single_inventory['title'] = str_replace("{Selling Price}", $selling_price, $single_inventory['title']);
        $single_inventory['title'] = str_replace("{Vehicle Tags}", $vehicle_tags, $single_inventory['title']);

        $single_inventory['title'] = $this->replaceLocationPlaceholder($single_inventory['title'], $location);

        $single_inventory['vehicle_description'] = str_replace("{Vin}", $vin, $single_inventory['vehicle_description']);
        $single_inventory['vehicle_description'] = str_replace("{Make}", $make, $single_inventory['vehicle_description']);
        $single_inventory['vehicle_description'] = str_replace("{Trim}", $trim, $single_inventory['vehicle_description']);
        $single_inventory['vehicle_description'] = str_replace("{Model}", $model, $single_inventory['vehicle_description']);
        $single_inventory['vehicle_description'] = str_replace("{Year}", $year, $single_inventory['vehicle_description']);

        $single_inventory['vehicle_description'] = str_replace("{Stock Number}", $stock_no, $single_inventory['vehicle_description']);
        $single_inventory['vehicle_description'] = str_replace("{Door}", $door, $single_inventory['vehicle_description']);
        $single_inventory['vehicle_description'] = str_replace("{Body Type}", $body_type, $single_inventory['vehicle_description']);
        $single_inventory['vehicle_description'] = str_replace("{Displacement}", $displacement, $single_inventory['vehicle_description']);
        $single_inventory['vehicle_description'] = str_replace("{Cylinders}", $cylinders, $single_inventory['vehicle_description']);
        $single_inventory['vehicle_description'] = str_replace("{Transmission}", $transmission, $single_inventory['vehicle_description']);
        $single_inventory['vehicle_description'] = str_replace("{Drivetrain}", $drivetrain, $single_inventory['vehicle_description']);
        $single_inventory['vehicle_description'] = str_replace("{Passengers}", $passengers, $single_inventory['vehicle_description']);

        $single_inventory['vehicle_description'] = str_replace("{Fuel Type}", $fuel_type, $single_inventory['vehicle_description']);
        $single_inventory['vehicle_description'] = str_replace("{Fuel Consumption City}", $fuel_city, $single_inventory['vehicle_description']);
        $single_inventory['vehicle_description'] = str_replace("{Fuel Consumption HWY}", $fuel_hwy, $single_inventory['vehicle_description']);
        $single_inventory['vehicle_description'] = str_replace("{Odometer}", $odometer, $single_inventory['vehicle_description']);
        $single_inventory['vehicle_description'] = str_replace("{Exterior Colour}", $exterior_color, $single_inventory['vehicle_description']);
        $single_inventory['vehicle_description'] = str_replace("{Interior Colour}", $interior_color, $single_inventory['vehicle_description']);
        $single_inventory['vehicle_description'] = str_replace("{Price}", $price, $single_inventory['vehicle_description']);
        $single_inventory['vehicle_description'] = str_replace("{Special Price}", $special_price, $single_inventory['vehicle_description']);
        $single_inventory['vehicle_description'] = str_replace("{Location}", $location, $single_inventory['vehicle_description']);
        $single_inventory['vehicle_description'] = str_replace("{Selling Price}", $selling_price, $single_inventory['vehicle_description']);
        $single_inventory['vehicle_description'] = str_replace("{Vehicle Tags}", $vehicle_tags, $single_inventory['vehicle_description']);

        $single_inventory['vehicle_description'] = $this->replaceLocationPlaceholder($single_inventory['vehicle_description'], $location);
        return $single_inventory;
    }

    private function replaceLocationPlaceholder(String $current_string = '', String $location_name = '')
    {
        try {
            $complete_dealership_details = (new LocationRepository())->getLocationByName($location_name);

            if (!empty($complete_dealership_details)) {
                $current_string = str_replace("{Business Name}", $complete_dealership_details['business_name'], $current_string);
                $current_string = str_replace("{Dealership Email}", $complete_dealership_details['email'], $current_string);
                $dealership_phone = Utility::format_phone_us($complete_dealership_details['phone']);
                $current_string = str_replace("{Dealership Phone}", $dealership_phone, $current_string);
                $dealership_address = $complete_dealership_details['street'] . ', ' . $complete_dealership_details['city'] . ', ' . $complete_dealership_details['state']
                    . ', ' . $complete_dealership_details['zipcode'];
                $current_string = str_replace("{Dealership Address}", $dealership_address, $current_string);
            }
        } catch (\Exception $ex) {
        }

        return $current_string;
    }

    public function findAll()
    {
        return $this->model->asArray()->findAll();
    }

    public function onlyInstock()
    {
        $this->model->where($this->model->table . '.status', 'Instock');
        return $this;
    }

    public function getYesterdayInventory()
    {
        $today =   Carbon::parse(Carbon::now()->subDays(1));
        $this->model->where("DATE({$this->model->table}.created_at) = DATE('{$today}')", null, false);
        return $this;
    }

    public function onlyByDay($token)
    {
        $this->model->where($this->model->table . '.inventory_id % 2 = ' . $token, null, false);
        return $this;
    }

    public function bySyndicationFilter($key)
    {
        //$this->model->like('syndication', $key);
        $where = "JSON_SEARCH(UPPER(syndication), 'one', UPPER('{$key}')) is not null";
        $this->model->where($where, NULL, FALSE);
        return $this;
    }


    /**
     * deleteAllImagesForClient This method is used to delete all the images.
     *
     * @return void
     */
    protected function deleteAllImagesForClient()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('inventory_image');
        try {
            CloudStorageFactory::getInstance()->emptyFolder('/' . UPLOAD_FOLDER . '/' . md5(db_connect()->database) . '/' . OVERLAY_FOLDER . '/');
            CloudStorageFactory::getInstance()->emptyFolder('/' . UPLOAD_FOLDER . '/' . md5(db_connect()->database) . '/');
        } catch (\Exception $ex) {
            var_dump($ex->getMessage());
        }

        $builder->emptyTable('inventory_image');
    }

    /**
     * getThirdPartyImagesToDownload
     * @return array
     */
    public function getThirdPartyImagesToDownload(): ?array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('inventory_image');
        $builder->select('inventory_image.*');
        $builder->join('inventory', 'inventory.inventory_id = inventory_image.inventory_id', 'inner');
        $builder->whereIn("status", ["Instock"]);
        $builder->like('image_url', '%http%');
        $builder->orderBy('id', 'DESC');
        $query = $builder->get();

        return $query->getResultArray();
    }

    public function getImageListForOverlayProcessing(): ?array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('inventory_image');
        $builder->select('inventory_image.*');
        $builder->join('inventory', 'inventory.inventory_id = inventory_image.inventory_id', 'inner');
        $builder->where('inventory.deleted_at', null);
        $builder->whereIn("status", ["Instock"]);

        $builder->groupStart()
                ->notLike("image_url", "http")
                ->where('inventory_image.image_with_overlay_url', null)
                ->orWhere('inventory_image.image_with_overlay_url', '')
                ->orLike('inventory_image.image_with_overlay_url', getenv('image.cdn'))
                ->groupEnd();

        $builder->orderBy('id', 'DESC');
        $query = $builder->get();

        return $query->getResultArray();
    }

    /**
     * deleteAllFilesForClient This method deletes all the files for the clients.
     *
     * @return void
     */
    protected function deleteAllFilesForClient()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('inventory_file');

        try {
            CloudStorageFactory::getInstance()->emptyFolder('/' . ROOT_FILE . '/' . md5(db_connect()->database) . '/');
        } catch (\Exception $ex) {
            var_dump($ex->getMessage());
        }

        $builder->emptyTable('inventory_file');
    }

    /**
     * importInventory This is the imported inventories from syndication.
     *
     * @param  mixed $products Products are the array of inventories.
     * @return array
     */
    public function importInventory($products): array
    {
        $errors = [];
        $count = 0;
        //Delete Inventory Files
        $this->deleteAllFilesForClient();

        //Delete Images
        $this->deleteAllImagesForClient();

        //Soft Delete Repositories
        $this->model->where("1 = 1")->delete();

        //Update Or Save Details

        foreach ($products as $data) {
            $row = null;

            if (isset($data['stock_no'])) {
                $row = $this->model->where('stock_no', $data['stock_no'])->withDeleted()->asArray()->first();
            } else if (isset($data['vin'])) {
                $row = $this->model->where('vin', $data['vin'])->withDeleted()->asArray()->first();
            }

            $inventory = new Inventory($data);
            $inventory->deleted_at = NULL;

            if (isset($row['inventory_id'])) {
                $inventory->inventory_id = $row['inventory_id'];
            }

            //if $row is null than it means it is a new inventory
            if (empty($row) & empty($data['stock_no'])) {
                $inventory->stock_no = strtoupper(Utility::randomString(6));
            }

            if (empty($row)) {
                $inventory->visibility = "1";
                $inventory->status = "Instock";
            }

            if ($this->model->save($inventory) === false) {
                $errors[$inventory->stock_no] = $this->model->errors();
            } else {

                ++$count;
                $inventory_id = isset($inventory->inventory_id) ? $inventory->inventory_id : $this->model->insertID;

                //bulk insert with inventory_id

                if (!empty($data['photos'])) {
                    $inventory_images = [];
                    $image_urls = explode(';', $data['photos']);
                    foreach ($image_urls as $key => $value) {
                        $inventory_images[$key]['image_url'] = $value;
                        $inventory_images[$key]['inventory_id'] = (int)$inventory_id;
                        $inventory_images[$key]['active'] = 1;
                    }

                    $db = \Config\Database::connect();
                    $builder = $db->table('inventory_image');
                    $builder->insertBatch($inventory_images);
                }
            }
        }

        $this->model->purgeDeleted();

        if (count($errors) > 0) {
            return [
                'status' => 'error',
                'error' => $errors,
            ];
        } else {
            return [
                'status' => 'success',
                'count' => $count
            ];
        }
    }

    public function parseWindowStickerContent($content, $inventory): string
    {
        $data = (object)$inventory;
        $frmt = new \NumberFormatter('en_US', \NumberFormatter::DECIMAL);

        $parseDesc =  $this->replacePlaceholders($inventory);
        $down = Utility::getCompleteFinancingDetails($inventory, 0, 0);

        $data = [
            $data->vindisplay ? $data->vin : "",
            ($data->price == 0) ?  "Call For Price" : $frmt->format($data->price),
            $frmt->format($data->oprice),
            $data->image,
            $data->stock_no,
            $data->exterior_color,
            $data->interior_color,
            $data->vehicle_type,
            $data->fuel_type,
            $data->transmission,
            $data->door,
            $data->litres,
            $data->drivetrain,
            $data->body_type,
            $data->year,
            $data->make,
            $data->model,
            $data->trim,
            $frmt->format($data->odometer),
            $data->km_miles,
            $parseDesc['vehicle_description'],
            SyndicationUtility::parseDescription($parseDesc['vehicle_description'], 600),
            SyndicationUtility::parseDescription($parseDesc['vehicle_description'], 1000),
            SyndicationUtility::parseDescriptionWithHtml($parseDesc['vehicle_description'], 600),
            $data->business_logo,
            $data->business_name,
            $data->business_address,
            $data->business_city,
            $data->business_state,
            $data->business_code,
            $data->business_phone,
            $data->business_email,
            $data->business_fax,
            $data->QR,
            $data->engine,
            $data->passengers,
            $data->fuel_city,
            $data->fuel_hwy,
            $data->location,
            SyndicationUtility::parseSyncdicationOptions($data->standard, ', '),
            SyndicationUtility::parseSyncdicationOptions($data->standard, '<br> '),
            $data->certified_or_asis,
            $data->website,
            SyndicationUtility::parseTags($data->vehicle_tags, ', '),
            $down['downpayment'] ?? '',
            $down['interest_rate'] ?? '',
            $down['term'] ?? '',
            $down['payment_frequency'] ?? '',
            $down['selling_price'] ?? '',
            $down['emi_amount'] ?? '',
        ];

        $replacement = [
            "{{VIN}}",
            "{{PRICE}}",
            "{{OPRICE}}",
            "{{IMAGE}}",
            "{{STOCK}}",
            "{{EXTERIOR_COLOR}}",
            "{{INTERIOR_COLOR}}",
            "{{VEHICLE_TYPE}}",
            "{{FUEL_TYPE}}",
            "{{TRANSMISSION}}",
            "{{DOOR}}",
            "{{ENGINE}}",
            "{{DRIVETRAIN}}",
            "{{BODY}}",
            "{{YEAR}}",
            "{{MAKE}}",
            "{{MODEL}}",
            "{{TRIM}}",
            "{{ODOMETER}}",
            "{{KM_MILES}}",
            "{{DESCRIPTION}}",
            "{{DESCRIPTION_LIMITED}}",
            "{{DESCRIPTION_1000}}",
            "{{DESCRIPTION_HTML_600}}",
            "{{LOGO}}",
            "{{BNAME}}",
            "{{BADDRESS}}",
            "{{BCITY}}",
            "{{BSTATE}}",
            "{{BCODE}}",
            "{{BPHONE}}",
            "{{BEMAIL}}",
            "{{BFAX}}",
            "{{QR}}",
            "{{CYLINDER}}",
            "{{PASSENGERS}}",
            "{{FUEL_CITY}}",
            "{{FUEL_HWY}}",
            "{{LOCATION}}",
            "{{INSTALLED_OPTIONS}}",
            "{{INSTALLED_OPTIONS_STYLE1}}",
            "{{CERTIFICATION}}",
            "{{WEBSITE}}",
            "{{VEHICLE_TAGS}}",
            "{{DOWNPAYMENT}}",
            "{{INTEREST_RATE}}",
            "{{TERM}}",
            "{{PAYMENT_FREQUENCY}}",
            "{{SELLING_PRICE}}",
            "{{EMI_AMOUNT}}"
        ];

        $processedTemplate = str_replace($replacement, $data, $content);

        return $processedTemplate;
    }

    public function getWindowSticker($content, $size = 300): string
    {
        $qrCode = (new QrCode())
            ->setText($content)
            ->setSize($size)
            ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
            //->setPadding(5)
            ->setErrorCorrection('high')
            ->setImageType(QrCode::IMAGE_TYPE_PNG);

        return "data:" . $qrCode->getContentType() . ";base64," . $qrCode->generate() . "";
    }



    /**
     * updateSearchIndex
     *
     * @param  mixed $ids
     * @return array
     */
    public function updateSearchIndex($ids = []): ?array
    {
        try {

            $db = \Config\Database::connect();
            $builder = $db->table('inventory');

            parent::setModel(new InventoryModel());

            $complete_inventory = NULL;
            if (empty($ids)) {
                $complete_inventory = $this->model->where("{$this->model->table}.search_keyword", NULL)
                    ->orWhere("{$this->model->table}.search_keyword", "")
                    ->asArray()->findAll();
            } else {
                $complete_inventory = $this->model
                    ->whereIn("{$this->model->table}.inventory_id", $ids)
                    ->asArray()
                    ->findAll();
            }

            foreach ($complete_inventory as $key => $value) {
                try {
                    $data['data'] = $value;
                    $odometer = is_numeric($value['odometer']) ? intval($value['odometer']) : 0;

                    $price = (int) $value["price"];
                    $special_price =  (int) $value["special_price"];
                    $selling_price = $value["special_price_on"] == "1" ? $special_price : $price;

                    $inventory_with_updated_search_index = $this->model->createKeyWordString($data);
                    if (isset($value['inventory_id'])) {
                        $builder->where('inventory_id', $value['inventory_id'])
                            ->set([
                                'odometer_numeric' => $odometer,
                                'selling_price_numeric' => $selling_price,
                                'search_keyword' => $inventory_with_updated_search_index['data']['search_keyword']
                            ])
                            ->update();
                    }
                } catch (\Exception $single_ex) {
                    log_message('notice', $single_ex->getMessage());
                }
            }
        } catch (\Exception $e) {
            return ['errors' => 'There were some errors in updating the index.'];
        }

        return ['success' => 'Inventory index has been updated.'];
    }


    public function findSingleInventoryByStockId(String $stock_no)
    {
        return $this->model->where('stock_no', $stock_no)->asArray()->first();
    }

    /**
     * findSingleInventoryByVin
     *
     * @param  mixed $vin
     * @return void
     */
    public function findSingleInventoryByVin(String $vin)
    {
        return $this->model->where('vin', $vin)->asArray()->first();
    }

    /**
     * getAvailableStockNumber
     *
     * @param  mixed $inventory
     * @return String
     */
    public function getAvailableStockNumber(array $inventory): String
    {
        $options = [];
        $options['vin'] = !empty($inventory['vin']) ? $inventory['vin'] : '';

        $inventory_stock_number_generator_strategy = (new ConfigurationRepository())->findConfigurationColumnsByKey('inventory_stock_number_generator_strategy', 'value');
        $inventory_stock_number_length = (new ConfigurationRepository())->findConfigurationColumnsByKey('inventory_stock_number_length', 'value');
        $inventory_stock_number_prefix = (new ConfigurationRepository())->findConfigurationColumnsByKey('inventory_stock_number_prefix', 'value');

        $stock_number_generator_factory = (new StockNumberGeneratorFactory())->getInstance($inventory_stock_number_generator_strategy->value);
        $stock_no = $stock_number_generator_factory->generate(
            (int) $inventory_stock_number_length->value,
            $inventory_stock_number_prefix->value,
            10,
            $options
        );
        return $stock_no;
    }


    /**
     * findSingleInventoryByStockIdAndUpdate
     *
     * @param  String $stock_no
     * @param  array $data
     * @return void
     */
    public function findSingleInventoryByStockIdAndUpdate(String $stock_no, array $data)
    {
        $inventory = $this->findSingleInventoryByStockId($stock_no);
        if (!empty($inventory['inventory_id'])) {
            (new InventoryModel())->update($inventory['inventory_id'], $data);
            $this->updateTypesenseIndexService([$inventory['inventory_id']]);
            $this->purgeWebsiteCache([$inventory['inventory_id']]);
            $this->updateSearchIndex([$inventory['inventory_id']]);
        }
    }

    /**
     * removeSyndicationSlugFromAllInventories This function loops through the inventory and removes particular syndication from the function.
     *
     * @param  mixed $slug Syndication company slug
     * @return array
     */
    public function removeSyndicationSlugFromAllInventories(String $slug): array
    {
        try {
            $complete_inventory = $this->model->asArray()->findAll();
            $db = \Config\Database::connect();
            $builder = $db->table('inventory');
            foreach ($complete_inventory as $key => $value) {
                $syndication_updated = false;
                $syndications = $value["syndication"];
                $syndications = json_decode($syndications, true);
                if (($key = array_search($slug, $syndications)) !== false) {
                    unset($syndications[$key]);
                    $syndication_updated = true;
                }


                if ($syndication_updated && isset($value['inventory_id'])) {
                    $builder->where('inventory_id', $value['inventory_id'])->set(['syndication' => json_encode(array_values($syndications))])->update();
                }
            }
        } catch (\Exception $e) {
            return ['errors' => 'There were some errors in updating the syndication.'];
        }

        return ['success' => 'Syndication slug : ' .  $slug . ' removed.'];
    }

    /**
     * addSyndicationSlugForAllInventories This function loops through the inventories and adds syndication slug to them.
     *
     * @param  mixed $slug Syndication Company Slug
     * @return array
     */
    public function addSyndicationSlugForAllInventories(String $slug): array
    {
        try {
            $complete_inventory = $this->model->asArray()->findAll();
            $db = \Config\Database::connect();
            $builder = $db->table('inventory');
            foreach ($complete_inventory as $key => $value) {
                $syndication_updated = false;
                $syndications = $value["syndication"];
                $syndications = json_decode($syndications, true);
                if ((array_search($slug, $syndications)) === false) {
                    array_push($syndications, $slug);
                    $syndication_updated = true;
                }

                if ($syndication_updated && isset($value['inventory_id'])) {
                    $builder->where('inventory_id', $value['inventory_id'])->set(['syndication' => json_encode(array_values($syndications))])->update();
                }
            }
        } catch (\Exception $e) {
            return ['errors' => 'There were some errors in updating the syndication.'];
        }

        return ['success' => 'Syndication slug : ' .  $slug . ' added.'];
    }


    public function changeSyndicationSlugsFromInventories(array $slugs = [], array $inventory_ids = [], String $action = "ADD"): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('inventory');

        try {
            if (is_array($slugs) && !empty($slugs) && is_array($inventory_ids) && !empty($inventory_ids)) {

                $repository = get_called_class();
                $repository = new $repository;

                $current_data['zm_inventory_id'] = $inventory_ids;
                $query = $repository->prepareDataForFilter($current_data);

                $inventories = $repository->index(null, $header = null, null, $query, null, null, false, 'inventory_id', 'inventory');

                foreach ($inventories['inventory'] as $key => $inventory) {

                    $syndication_updated = false;
                    $syndications = $inventory["syndication"];
                    $syndications = json_decode($syndications, true);
                    foreach ($slugs as $slug) {
                        switch ($action) {
                            case 'REMOVE':
                                if (($slug_key = array_search($slug, $syndications)) !== false) {
                                    unset($syndications[$slug_key]);
                                    $syndication_updated = true;
                                }
                                break;

                            case 'ADD':
                                if ((array_search($slug, $syndications)) === false) {
                                    array_push($syndications, $slug);
                                    $syndication_updated = true;
                                }
                                break;

                            default:
                                # code...
                                break;
                        }
                    }

                    if ($syndication_updated && isset($inventory['inventory_id'])) {
                        $builder->where('inventory_id', $inventory['inventory_id'])->set(['syndication' => json_encode(array_values($syndications))])->update();
                    }
                }
            }
        } catch (\Exception $e) {
            return ['errors' => 'There were some errors in updating the syndication.'];
        }

        return ['success' => 'Syndication Slugs have been updated.'];
    }


    public function pricingBulkUpdate(array $conditions = [], String $column, String $amount, String $operator = 'EQUAL'): void
    {
        if (empty($conditions)) {
            return;
        }

        switch (strtoupper($operator)) {
            case 'EQUAL':
                $db = \Config\Database::connect();
                $builder = $db->table('inventory');
                $builder->set($column, $amount);

                foreach ($conditions as $condition_key => $condition_value) {
                    if (!empty($condition_value)) {
                        $builder->where($condition_key, $condition_value);
                    }
                }
                $builder->update();
                break;

            case 'INCREMENT':
                $db = \Config\Database::connect();
                $builder = $db->table('inventory');
                $builder->set($column, $column . '+' . $amount, false);

                foreach ($conditions as $condition_key => $condition_value) {
                    if (!empty($condition_value)) {
                        $builder->where($condition_key, $condition_value);
                    }
                }

                $builder->update();
                break;

            case 'DECREMENT':
                $db = \Config\Database::connect();
                $builder = $db->table('inventory');
                $builder->set($column, $column . '-' . $amount, false);

                foreach ($conditions as $condition_key => $condition_value) {
                    if (!empty($condition_value)) {
                        $builder->where($condition_key, $condition_value);
                    }
                }

                $builder->where($column . ' >=', $amount);
                $builder->update();
                break;

            default:
                break;
        }

        $this->updateTypesenseIndexService([]);
        $this->purgeWebsiteCache([]);
    }

    public function descriptionBulkUpdate(array $conditions = [], String $title = "", String $vehicle_description = ""): void
    {
        if (empty($conditions)) {
            return;
        }

        $db = \Config\Database::connect();
        $builder = $db->table('inventory');
        $builder->set("title", $title);
        $builder->set("vehicle_description", $vehicle_description);

        foreach ($conditions as $condition_key => $condition_value) {
            if (!empty($condition_value)) {
                $builder->where($condition_key, $condition_value);
            }
        }
        $builder->update();


        $this->updateTypesenseIndexService([]);
        $this->purgeWebsiteCache([]);
    }


    /**
     * markAllInventoriesAsSold This function is used to update all inventories as sold.
     * Should be used for inbound inventories where we want to avoid overridding creation timestamp or update timestamp.
     * 
     *
     * @return void
     */
    public function markAllInventoriesAsSold()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('inventory');
        $builder->set(['status' => 'Sold'])->update();

        $this->updateTypesenseIndexService([]);
        $this->purgeWebsiteCache([]);

        return ['success' => 'All inventories marked as sold.'];
    }


    /**
     * markAllInventoriesAsUnpublished This function is used to update all inventories as unpublished.
     * Should be used for inbound inventories where we want to avoid overridding creation timestamp or update timestamp.
     * 
     *
     * @return void
     */
    public function markAllInventoriesAsUnpublished()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('inventory');
        $builder->set(['status' => 'Unpublished'])->update();

        $this->updateTypesenseIndexService([]);
        $this->purgeWebsiteCache([]);

        return ['success' => 'All inventories marked as unpublished.'];
    }


    /**
     * purgeAllInventory
     *
     * @return void
     */
    public function purgeAllInventory()
    {
        $this->deleteAllFilesForClient();
        $this->deleteAllImagesForClient();
        $this->model->where("1 = 1")->delete();
        $this->model->purgeDeleted();
        $typesense = new Typesense();
        $typesense->setCollection(md5(db_connect()->database));
        $typesense->purge();
        return ['success' => 'Purge Deleted.'];
    }

    /**
     * purgeDelete Purge Deletes only deleted inventories.
     *
     * @return void
     */
    public function purgeDelete()
    {
        $this->model->purgeDeleted();
        return ['success' => 'Purge Deleted.'];
    }

    /**
     * updateTypesenseIndexService
     *
     * @param  mixed $inventory_ids
     * @return void
     */
    public function updateTypesenseIndexService(array $inventory_ids)
    {
        $bWebsite = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_website', 'value');
        $iType = (new ConfigurationRepository())->findConfigurationColumnsByKey('inventory_type', 'value');
        $wordPressType = (new ConfigurationRepository())->findConfigurationColumnsByKey('wordpress_type', 'value');

        $repository = get_called_class();
        $repository = new $repository;

        $static_images = $this->image_repository->getAllActiveStaticImages();

        $repository->setModel(new InventoryWithDeletedModel());

        $typesense = new Typesense();
        $typesense->setCollection(md5(db_connect()->database));
        $typesense->setIntColumns(['inventory_id', 'price', 'special_price', 'odometer', 'image_count', 'selling_price', 'make_rank', 'model_rank']);
        $typesense->setDateColumns(['created_at', 'updated_at', 'deleted_at' ,'stock_date']);
        $typesense->setArrayColumns(['vehicle_tags', 'custom_features']);
        $typesense->setStringColumns(['special_price_date', 'image_urls', 'files', 'fuel_city', 'fuel_type', 'fuel_hwy', 'door', 'special_price_type', 'transmission']);

        $query = null;

        if (!empty($inventory_ids)) {
            $current_data['zm_inventory_id'] = $inventory_ids;
            $query = $repository->prepareDataForFilter($current_data);
        }

        $inventories = $repository->index(null, $header = null, null, $query, null, null, false, 'inventory_id', 'inventory');

        foreach ($inventories['inventory'] as $key => $value) {
            $inventory =  $repository->replacePlaceholders($value);
            $inventory['id'] = $inventory['inventory_id'];
            $inventory['selling_price'] = $inventory['special_price_on'] == "1" ? $inventory['special_price'] : $inventory['price'];

            if ((int) $inventory['hide_price'] == 1) {
                $inventory['selling_price'] = 0;
                $inventory['price'] = 0;
                $inventory['special_price'] = 0;
                $inventory['selling_price_numeric'] = 0;
            }

            $inventory['emi_amount'] = Utility::getFinancingAmount($inventory, 0, 0);
            $inventory['emi_amount'] = is_numeric($inventory['emi_amount']) ? floatval($inventory['emi_amount']) : 0;

            $inventory['page_url'] = Utility::urlCreation("", $iType->value, $inventory, $wordPressType->value);

            $make_rank = strtoupper(substr($inventory['make'], 0, 3));
            $make_rank = str_pad($make_rank, 3, "aaa");

            $inventory['make_rank'] = implode('', unpack("C*", strtoupper($make_rank)));
            $inventory['make_rank']  = intval($inventory['make_rank']);

            $model_rank = strtoupper(substr($inventory['model'], 0, 3));
            $model_rank = str_pad($model_rank, 3, "aaa");

            $inventory['model_rank'] = implode('', unpack("C*", strtoupper($model_rank)));
            $inventory['model_rank']  = intval($inventory['model_rank']);

            $status_rank = strtoupper(substr($inventory['status'], 0, 3));
            $status_rank = str_pad($status_rank, 3, "aaa");

            $inventory['status_rank'] = implode('', unpack("C*", strtoupper($status_rank)));
            $inventory['status_rank']  = intval($inventory['status_rank']);

            $inventory['year_search'] = $inventory['year'];

            $inventory['vin'] = $inventory['vindisplay'] == "1" ? $inventory['vin'] : "";


            $unnecessary_attributes = ['syndication', 'days_in_stock', 'modification_hash'];
            foreach ($unnecessary_attributes as $attribute) {
                if (!empty($inventory[$attribute])) {
                    unset($inventory[$attribute]);
                }
            }

            $inventory['static_images'] = $static_images;
            $inventory = $typesense->formatSingleEntity($inventory);
            $inventories['inventory'][$key] = $inventory;
        }

        $typesense->bulkUpsert($inventories['inventory']);
    }

    public function updateTypesenseIndexServiceByStockNumber(string $stock_no): void
    {
        try {
            $inventory = $this->findSingleInventoryByStockId($stock_no);
            if (!empty($inventory)) {
                $this->updateTypesenseIndexService([$inventory['inventory_id']]);
                $this->purgeWebsiteCache([$inventory['inventory_id']]);
            }
        } catch (\Exception $ex) {
            log_message('notice', $ex->getMessage());
        }
    }

    /**
     * deleteAndReIndexService 
     *
     * @return void
     */
    public function deleteAndReIndexService()
    {
        $typesense = new Typesense();
        $typesense->setCollection(md5(db_connect()->database));
        $typesense->purge();

        $this->updateTypesenseIndexService([]);
        $this->purgeWebsiteCache([]);
    }

    /**
     * purgeInventoryAssets
     *
     * @return void
     */
    public function purgeInventoryAssets()
    {
        $this->deleteAllFilesForClient();
        $this->deleteAllImagesForClient();
        $this->updateTypesenseIndexService([]);
        $this->purgeWebsiteCache([]);
        return ['success' => 'Inventory Assets Purged.'];
    }


    /**
     * purgeWebsiteCache    This clears the cache on the wordpress for the VDP Pages
     *
     * @param  array $inventory_ids
     * @param  int $delay
     * @param  bool $skip_auto_purge_check
     * @return void
     */
    public function purgeWebsiteCache(array $inventory_ids, int $delay = 2, bool $skip_auto_purge_check = false)
    {
        try {

            $enable_website_auto_purge_on_update = (new ConfigurationRepository())->findConfigurationColumnsByKey('website_auto_purge_on_update', 'value');
            $is_auto_purge_enabled =  !empty($enable_website_auto_purge_on_update->value);

            if ($skip_auto_purge_check) {
                $is_auto_purge_enabled = true;
            }

            if (!$is_auto_purge_enabled) {
                return;
            }

            $bWebsite = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_website', 'value');
            $iType = (new ConfigurationRepository())->findConfigurationColumnsByKey('inventory_type', 'value');
            $wordPressType = (new ConfigurationRepository())->findConfigurationColumnsByKey('wordpress_type', 'value');

            $repository = get_called_class();
            $repository = new $repository;

            $query = null;

            $urls = [];

            $websiteCacheInstance = WebsiteCacheFactory::getInstance($wordPressType->value);

            if (!empty($inventory_ids)) {
                $current_data['zm_inventory_id'] = $inventory_ids;
                $query = $repository->prepareDataForFilter($current_data);
                $inventories = $repository->index(null, $header = null, null, $query, null, null, false, 'inventory_id', 'inventory');
                foreach ($inventories['inventory'] as $key => $value) {
                    $inventory =  $repository->replacePlaceholders($value);
                    $url = Utility::urlCreation($bWebsite->value, $iType->value, $inventory, $wordPressType->value);
                    array_push($urls, $url);
                }
            } else {
                $url = $websiteCacheInstance->getBulkDeleteURL($bWebsite->value, $iType->value);
                array_push($urls, $url);
            }

            $cache_factory = CacheFactory::getHandler(new NonPersistantCache());

            if (!empty($urls)) {
                foreach ($urls as $url) {
                    $payload = [];
                    $payload['delay'] = $delay;
                    $payload['url'] = $url;
                    $payload['website_type'] = $wordPressType->value;

                    $wci_key = "WCURI_" . $payload['url'];
                    $wci_key = md5($wci_key);

                    $cache_factory->save($wci_key, json_encode($payload), 60);
                    exec("php index.php WordpressJobQueue add $wci_key");
                }
            }
        } catch (Exception $ex) {
            log_message('notice', $ex->getMessage());
        }
    }

    public function updateDaysInStock(): void
    {
        $db      = \Config\Database::connect();
        $db->query("UPDATE inventory
        SET days_in_stock = DATEDIFF(NOW(), stock_date)
        WHERE `status` = 'INSTOCK';");
    }

    /**
     * saveNote Here the logic saves the note along with the attachment for the inventory
     *
     * @param  int $id
     * @param  mixed $data
     * @return array
     */
    public function saveNote(int $id, $data): array
    {
        $inventoryNote = new MasterEntity($data);
        $inventoryNote->inventory_id = $id;
        $inventoryNoteModel = new InventoryNoteModel();

        if ($inventoryNoteModel->save($inventoryNote) === false) {
            return $inventoryNoteModel->errors();
        } else {

            $inventory_note_id = $inventoryNoteModel->insertID;
            return [
                'success' => 'Note for inventory has been created.',
                'inventory_note_id' => $inventory_note_id,
            ];
        }
    }


    /**
     * allNotes This function returns all the notes related to a inventory
     *
     * @param  mixed $id
     * @return array
     */
    public function allNotes(int $id): array
    {
        $notes = (new InventoryNoteModel())->where('inventory_id', $id)->orderBy('created_at', 'DESC')->asArray()->findAll();
        return ['all_notes' => $notes];
    }

    /**
     * showNote This returns a single note by id.
     *
     * @param  mixed $id
     * @return array
     */
    public function showNote($id): ?array
    {
        return (new InventoryNoteModel())->asArray()->find($id);
    }

    /**
     * updateNote Update note. Accepts id and data to be updated.
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return void
     */
    public function updateNote($id, $data)
    {

        $inventoryNote = new MasterEntity($data);
        $inventoryNote->id = $id;
        $inventoryNoteModel = new InventoryNoteModel();


        if ($inventoryNoteModel->save($inventoryNote) === false) {
            return [
                $inventoryNoteModel->errors()
            ];
        } else {
            return [
                "success" => 'Inventory Note has been updated.',
            ];
        }
    }

    /**
     * deleteNote Delete a note for the lead response 
     *
     * @param  mixed $id
     * @return void
     */
    public function deleteNote(int $id)
    {
        $note = $this->showNote($id);
        if ($note == null) {
            return ['errors' => 'Could not find the note.'];
        }
        if (!empty($note['attachment'])) {
            $file_path = $note['attachment'];
            if (gettype($file_path) == "string") {
                CloudStorageFactory::getInstance()->delete($file_path);
            }
        }
        (new InventoryNoteModel())->delete($id);
        return ['success' => 'Inventory Note has been deleted successfully.'];
    }
}
