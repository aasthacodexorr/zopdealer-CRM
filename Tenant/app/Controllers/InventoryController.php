<?php

namespace Tenant\app\Controllers;

use App\Helpers\Utility;
use App\Libraries\CloudStorage\CloudStorageFactory;
use Tenant\app\Repositories\InventoryRepository;
use CodeIgniter\Services;
use Tenant\app\Libraries\Vin\VinFactory;
use Tenant\app\Repositories\ImageRepository;
use Knp\Snappy\Pdf;
use Tenant\app\Libraries\Import\InventoryImport;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Libraries\PdfFiller\PDFFillerFactory;
use Tenant\app\Libraries\PDFParser\PDFParserFactory;
use Tenant\app\Models\DealCustomerPivotModel;
use Tenant\app\Models\DealInventoryPivotModel;
use Tenant\app\Repositories\CustomerRepository;
use Tenant\app\Repositories\DealRepository;
use Tenant\app\Repositories\InventoryCommonDependentRepository;
use Tenant\app\Repositories\InventoryCostRepository;
use Tenant\app\Repositories\InventoryPurchaseRepository;
use Tenant\app\Repositories\LocationRepository;
use Tenant\app\Repositories\TenantUserMetaDataRepository;
use \Intervention\Image\ImageManager;
use Tenant\app\Libraries\PdfFiller\PDFFillerConstant;
use Tenant\app\Repositories\CostSettingRepository;
use Tenant\app\Repositories\InventoryRepositoryV2;

/**
 * InventoryController : This controller is associated with inventory management.
 */
class InventoryController extends \App\Controllers\BaseController
{


    /**
     * repository This repository corresponds to the InventoryRepository which can be used by any method here.
     *
     * @var InventoryRepository
     */
    private $repository;

    /**
     * configuration_repository
     *
     * @var ConfigurationRepository
     */
    private $configuration_repository;

    private InventoryRepositoryV2 $repository_v2;

    private CostSettingRepository $cost_setting_repository;

    private InventoryCostRepository $inventory_cost_repository;

    public function __construct()
    {
        $this->repository = new InventoryRepository();
        $this->configuration_repository = new ConfigurationRepository();
        $this->repository_v2 = new InventoryRepositoryV2();
        $this->cost_setting_repository = new CostSettingRepository();
        $this->inventory_cost_repository = new InventoryCostRepository();
    }


    /**
     * index List of Inventories.
     *
     * @return void
     */
    public function index()
    {
        service('authPolicy')->check('Inventory-read');

        $query = [];
        $inventoryRepository = new InventoryRepository();
        $query = null;
        if (count($_GET) > 0) {
            $query = $inventoryRepository->prepareDataForFilter($_GET);
        }

        $items = 100;
        $paginate = true;

        $column = 'created_at';
        $order = 'DESC';


        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }

        if ($this->request->getGet('page_no')) {
            $_GET['page'] = $this->request->getGet('page_no');
        }

        if ($this->request->getGet('paginate')) {
            $paginate = $this->request->getGet('paginate');
            $paginate  = $paginate == 'false' ? false : true;
        }

        if ($this->request->getGet('column')) {
            $column = $this->request->getGet('column');
        }

        if ($this->request->getGet('order')) {
            $order = $this->request->getGet('order') == "ASC" ? "ASC" : "DESC";
        }

        if ($this->request->getGet('all')) {
            $data = $inventoryRepository;
        } else {
            $data = $inventoryRepository->onlyVisible();
        }


        if ($this->request->getGet('search')) {
            $search = $this->request->getGet('search');
            if ($search != '') {
                $data = $inventoryRepository->exactSearch($search);
            }
        }

        $sort = '{"' . $column . '" : "' . $order  . '"}';

        $data = $data->index(null, $header = null, null, $query, $items, $sort, $paginate, 'inventory_id', 'inventory');

        foreach ($data['inventory'] as $key => $value) {
            $data['inventory'][$key] = $inventoryRepository->replacePlaceholders($value);
        }

        $this->response->setContentType('application/json')->setJSON($data)->send();
    }

    public function getVehicleInformationByVIN(String $vin)
    {
        $default_vin_feature_value = (new ConfigurationRepository())->findConfigurationColumnsByKey('default_vin_feature_value', 'value')->value;
        $default_vin_feature_value = intval($default_vin_feature_value);

        $vin_request = VinFactory::getVinInstance();
        $vin_request->setDefaultValue($default_vin_feature_value);
        $vin_request->lookupVehicleByVin($vin);
        $response = $vin_request->getFormattedResponse();

        if (!empty($response['error'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        }
    }

    public function getVehicleInformationByTrimID(String $trim_id)
    {

        $default_vin_feature_value = (new ConfigurationRepository())->findConfigurationColumnsByKey('default_vin_feature_value', 'value')->value;
        $default_vin_feature_value = intval($default_vin_feature_value);

        $vin_request = VinFactory::getVinInstance();
        $vin_request->setDefaultValue($default_vin_feature_value);
        $vin_request->lookupVehicleByTrimID($trim_id);
        $response = $vin_request->getFormattedResponse();

        if (!empty($response['error'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        }
    }

    public function getBasicVehicleInformation(String $vin)
    {
        $default_vin_feature_value = (new ConfigurationRepository())->findConfigurationColumnsByKey('default_vin_feature_value', 'value')->value;
        $default_vin_feature_value = intval($default_vin_feature_value);

        $vin_request = VinFactory::getVinInstance();
        $vin_request->setDefaultValue($default_vin_feature_value);
        $vin_request->lookupVehicleByVin($vin);
        $response = $vin_request->getFormattedResponse();

        if (isset($response['error'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setJSON([
                'vin' => $response['vin'],
                'year' => $response['year'],
                'make' => $response['make'],
                'model' => $response['model'],
                'trim' => $response['trim']
            ])->send();
        }
    }

    public function create()
    {
        service('authPolicy')->check('Inventory-create');

        $data = $this->request->getPost();
        $data['stock_no'] = !empty($data['stock_no']) ? $data['stock_no'] : $this->repository->getAvailableStockNumber($data);
        $response = $this->repository_v2->create($data);

        if (isset($response['success'])) {
            $inventory_id = $response['inventory_id'];
            $this->includeDefaultExpense($inventory_id, !empty($data['stock_date']) ? $data['stock_date'] : null);
            $this->repository->updateTypesenseIndexService([$inventory_id]);
            $this->repository->purgeWebsiteCache([$inventory_id]);

            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    /**
     * show Shows the details of the inventory item.
     *
     * @param  int $id Id of inventory.
     * @return void
     */
    public function show($id)
    {
        service('authPolicy')->check('Inventory-read');

        $bWebsite = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_website', 'value');
        $iType = (new ConfigurationRepository())->findConfigurationColumnsByKey('inventory_type', 'value');
        $wordPressType = (new ConfigurationRepository())->findConfigurationColumnsByKey('wordpress_type', 'value');

        $inventory = $this->repository_v2->show($id);
        if (!empty($inventory)) {
            $inventory['page_url'] = Utility::urlCreation($bWebsite->value, $iType->value, $inventory, $wordPressType->value);
        }
        
        $this->response->setContentType('application/json')->setJSON($inventory)->send();
    }

    public function update($id)
    {
        service('authPolicy')->check('Inventory-update');

        $available_inventory = $this->repository_v2->show($id);
        if ($available_inventory == null || !isset($available_inventory['inventory_id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Inventory couldn't be found."
            ])->send();
            exit;
        }

        $data = $this->request->getPost();
        $data['inventory_id'] = $id;
        $response = $this->repository_v2->update($id, $data);
        if (isset($response['success'])) {
            if (!$this->isDevelopmentEnvironment()) {
                $this->repository->updateTypesenseIndexService([$id]);
                $this->repository->updateSearchIndex([$id]);
                $this->repository->purgeWebsiteCache([$id]);
            }
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    /**
     * updates This method accepts multiple inventory_ids to perform bulk updates for the inventory.
     *
     * @return void
     */
    public function updates()
    {
        service('authPolicy')->check('Inventory-update');
        $data = $this->request->getPost();
        $columns = $data;
        if (array_key_exists("inventory_ids", $columns)) {
            unset($columns['inventory_ids']);
        }

        $columns = array_filter($data, function ($column) {
            return (trim($column)  != '');
        });

        $inventory_ids = $data['inventory_ids'];
        $data['inventory_ids'] = explode(',', $data['inventory_ids']);

        if (count($data['inventory_ids']) > 0 && count($columns) > 0) {
            $this->repository_v2->multipleUpdates($data['inventory_ids'], $columns);

            $this->repository->updateSearchIndex($data['inventory_ids']);
            $this->repository->updateTypesenseIndexService($data['inventory_ids']);
            $this->repository->purgeWebsiteCache([]);
            $this->response->setContentType('application/json')->setJSON(["success" => "Columns Updated Successfully."])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["error" => "No Columns Or Rows Were Selected."])->send();
        }
    }

    public function partialUpdate()
    {
        service('authPolicy')->check('Inventory-update');
        $data = $this->request->getPost();

        $validated = $this->validate([
            'stock_no' => [
                'label' => 'Stock Number',
                'rules' => 'required',
            ],
            'column' => [
                'label' => 'Column',
                'rules' => 'required',
            ],
            'value' => [
                'label' => 'Value',
                'rules' => 'permit_empty',
            ],
        ]);

        if ($validated) {
            $inventory = [];
            $inventory['stock_no'] = $data['stock_no'];
            $inventory[$data['column']] = $data['value'];

            $inventoryImport = new InventoryImport();
            $errors = $inventoryImport->validate($inventory, [], []);

            if (empty($errors)) {
                $response = $inventoryImport->process($inventory, [], []);

                if (isset($response['success'])) {
                    $this->repository->updateTypesenseIndexServiceByStockNumber($data['stock_no']);
                    $this->response->setContentType('application/json')->setJSON($response)->send();
                } else {
                    $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
                }
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($errors)->send();
            }
        } else {
            $errors = $this->validator->getErrors();
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($errors)->send();
        }
    }

    public function pricingBulkUpdates()
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            $data = $this->request->getPost();

            $validated = $this->validate([
                'status' => [
                    'label' => 'Status',
                    'rules' => 'required',
                ],
                'year' => [
                    'label' => 'Year',
                    'rules' => 'permit_empty',
                ],
                'make' => [
                    'label' => 'Make',
                    'rules' => 'permit_empty',
                ],
                'model' => [
                    'label' => 'Model',
                    'rules' => 'permit_empty',
                ],
                'column' => [
                    'label' => 'Column',
                    'rules' => 'required',
                ],
                'operator' => [
                    'label' => 'Adjust By',
                    'rules' => 'required',
                ],
                'amount' => [
                    'label' => 'Amount',
                    'rules' => 'required|decimal',
                ]
            ]);

            $query_conditions = [];
            $attributes = ['year', 'make', 'model', 'status'];

            foreach ($attributes as $attribute) {
                if (!empty($data[$attribute])) {
                    $query_conditions[$attribute] = $data[$attribute];
                }
            }

            if ($validated) {
                $this->repository->pricingBulkUpdate($query_conditions, $data['column'], $data['amount'], $data['operator']);
                $this->response->setContentType('application/json')->setJSON(["success" => "Bulk pricing changes are successful."])->send();
            } else {
                $errors = $this->validator->getErrors();
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($errors)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function descriptionBulkUpdates()
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            $data = $this->request->getPost();

            $validated = $this->validate([
                'status' => [
                    'label' => 'Status',
                    'rules' => 'required',
                ],
                'year' => [
                    'label' => 'Year',
                    'rules' => 'permit_empty',
                ],
                'make' => [
                    'label' => 'Make',
                    'rules' => 'permit_empty',
                ],
                'model' => [
                    'label' => 'Model',
                    'rules' => 'permit_empty',
                ],
                'vehicle_description' => [
                    'label' => 'Vehicle Description',
                    'rules' => 'required',
                ],
                'title' => [
                    'label' => 'Title',
                    'rules' => 'required',
                ],
                'change_default' => [
                    'label' => 'Change Default',
                    'rules' => 'permit_empty',
                ]
            ]);

            $query_conditions = [];
            $attributes = ['year', 'make', 'model', 'status'];

            foreach ($attributes as $attribute) {
                if (!empty($data[$attribute])) {
                    $query_conditions[$attribute] = $data[$attribute];
                }
            }

            if ($validated) {
                $vehicle_description = !empty($data['vehicle_description']) ? $data['vehicle_description'] : "";
                $title = !empty($data['title']) ? $data['title'] : "";

                $this->repository->descriptionBulkUpdate($query_conditions, $title, $vehicle_description);

                $change_default = !empty($data['change_default']) ? intval($data['change_default']) : 0;
                if (!empty($change_default)) {
                    (new ConfigurationRepository())->setConfiguration(['editor_default_title' => $title, 'editor_default_vehicle_description' => $vehicle_description]);
                }

                $this->response->setContentType('application/json')->setJSON(["success" => "Bulk Description changes are successful."])->send();
            } else {
                $errors = $this->validator->getErrors();
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($errors)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }


    public function bulkSyndicationUpdates()
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            $data = $this->request->getPost();

            $validated = $this->validate([
                'slugs' => [
                    'label' => 'Syndication Company is required',
                    'rules' => 'required',
                ],
                'inventory_ids' => [
                    'label' => 'Inventories are required',
                    'rules' => 'required',
                ],
                'action' => [
                    'label' => 'Please select an action',
                    'rules' => 'required',
                ],
            ]);
            if ($validated) {
                $slugs = explode(',', $data['slugs']);
                $inventory_ids = explode(',', $data['inventory_ids']);

                $response = $this->repository->changeSyndicationSlugsFromInventories($slugs, $inventory_ids, $data['action']);

                if (isset($response['success'])) {
                    $this->response->setContentType('application/json')->setJSON($response)->send();
                } else {
                    $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
                }
            } else {
                $errors = $this->validator->getErrors();
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($errors)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    /**
     * multipleDeletes This deletes multiple inventories.
     *
     * @return void
     */
    public function multipleDeletes()
    {
        service('authPolicy')->check('Inventory-delete');
        $data = $this->request->getPost();

        $data['inventory_ids'] = explode(',', $data['inventory_ids']);

        if (count($data['inventory_ids']) > 0) {

            $this->repository_v2->multipleDeletes($data['inventory_ids']);

            if (!$this->isDevelopmentEnvironment()) {
                $this->repository->updateTypesenseIndexService($data['inventory_ids']);
                $this->repository->purgeWebsiteCache([]);
            }

            $this->response->setContentType('application/json')->setJSON(["success" => "Selected data has been deleted."])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["error" => "There were some errors in deleting data."])->send();
        }
    }

    /**
     * saveImage Saves images related to the inventory.
     *
     * @param  int $id Id of inventory.
     * @return void
     */
    public function saveImage($id)
    {
        service('authPolicy')->check('Inventory-update');
        $validated = $this->validate([
            'file' => [
                'uploaded[file]',
                'mime_in[file,image/jpg,image/jpeg,image/gif,image/png,image/webp,image/heic,image/heif]',
                'max_size[file,32000]',
            ],
        ]);

        $inventory_id = -1;
        $inventoryRepository = new InventoryRepository();
        try {
            $inventoryInstance = $inventoryRepository->model->find($id);
            $inventory_id = $inventoryInstance->inventory_id;
        } catch (\Exception $ex) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['error' => "Couldn't find the Inventory."])->send();
            exit;
        }
        if ($validated) {
            $file = $this->request->getFile('file');
            $name = $file->getRandomName();
            $file_mime = $file->getMimeType();
            $image_save_path = UPLOAD_FOLDER . "/" . md5(db_connect()->database) . "/" . $name;

            if (in_array($file_mime, ['image/webp', 'image/heic', 'image/heif'])) {

                $image_save_path = str_ireplace("heif", "jpg", $image_save_path);
                $image_save_path = str_ireplace("heic", "jpg", $image_save_path);
                $image_save_path = str_ireplace("webp", "jpg", $image_save_path);

                $manager = new ImageManager(array('driver' => 'imagick'));
                $manager->make($file->getPathname())->encode('jpg', 100)->save();
            }

            CloudStorageFactory::getInstance()->save($file, $image_save_path);

            /** Data image_url, inventory_id, sort_rank */
            $data = [];
            $data['image_url'] = "/" . $image_save_path;

            $imageRepository = new ImageRepository();
            $overlayImage = $imageRepository->getActiveOverlayImages();

            if ($this->isZopStudioEnabled() || count($overlayImage) > 0) {
                $data['image_with_overlay_url'] =  getenv('image.cdn') . "/" . $image_save_path;
            }

            $image_path = $data['image_url'];
            $pos = strrpos($image_path, '/', -1);
            $image_path = substr($image_path, $pos + 1);

            $database = db_connect()->database;


            if (count($overlayImage) > 0) {
                if (isset($overlayImage[0]['image_url'])) {

                    $folder = 'image';
                    $image_url = $overlayImage[0]['image_url'];
                    $pos = strrpos($image_url, '/', -1);
                    $overlay_image_file = substr($image_url, $pos + 1);

                    exec("php index.php imageQueue sendSingleImage $database $folder $overlay_image_file $image_path");
                }
            }

            if ($this->isZopStudioEnabled()) {
                exec("php index.php AIStudioImageQueue sendSingleImage $database $image_path");
            }

            $data['inventory_id'] = $inventory_id;
            $data['sort_rank'] = $this->request->getPost('sort_rank');
            $id = $inventoryRepository->storeImage($data);
            $data['id'] = $id;

            $data['success'] = 'Image Uploaded Successfully';

            $this->repository->updateTypesenseIndexService([$data['inventory_id']]);
            $this->repository->purgeWebsiteCache([$data['inventory_id']]);
            $this->response->setContentType('application/json')->setJSON($data)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    /**
     * imageList Retrieves list of images for a particular inventory.
     *
     * @param  int $id Id of inventory.
     * @return void
     */
    public function imageList($id)
    {
        service('authPolicy')->check('Inventory-update');
        $inventory_id = -1;
        $inventoryRepository = new InventoryRepository();
        try {
            $inventory_id = $inventoryRepository->model->find($id)->inventory_id;
        } catch (\Exception $ex) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['error' => "Couldn't find the Inventory."])->send();
            exit;
        }
        $gallery = $inventoryRepository->imageList($id);
        $last_rank = $inventoryRepository->lastRank($id);
        $this->response->setContentType('application/json')->setJSON([
            'last_rank' => $last_rank,
            'gallery' => $gallery,
        ])->send();
    }

    /**
     * sortImageRank This function sorts through the rank for the images stored in the gallery.
     *
     * @param  mixed $id
     * @return void
     */
    public function sortImageRank($id)
    {
        service('authPolicy')->check('Inventory-update');
        $data = $this->request->getPost();
        $data["gallery"] = json_decode($data["gallery"], true);
        $i = 0;
        $rows = [];
        $inventoryRepository = new InventoryRepository();
        foreach ($data["gallery"] as $row) {
            $i += 1;
            array_push($rows, [
                'id' => (int) $row['id'],
                'sort_rank' => $i
            ]);

            $inventoryRepository->updateSortRank($rows);
        }

        $inventoryInstance = $inventoryRepository->model->find($id);
        $stock_no = $inventoryInstance->stock_no;
        $inventory_id = $inventoryInstance->inventory_id;
        $this->repository->updateTypesenseIndexService([$inventory_id]);
        $this->repository->purgeWebsiteCache([$inventory_id]);

        $response = ['success' => 'Vehicle images have been updated.'];
        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    /**
     * deleteImages This function performs bulk delete.
     *
     * @param  int $id Id of the inventory
     * @return void
     */
    public function deleteImages($id)
    {
        service('authPolicy')->check('Inventory-update');
        $inventoryRepository = new InventoryRepository();
        $data = $this->request->getPost();
        $imageIds = json_decode($data['ids'], true);
        if (count($imageIds) > 0) {
            $inventoryRepository->deleteImages($imageIds);
            $this->imageList($id);
        }

        $inventoryInstance = $inventoryRepository->model->find($id);
        $stock_no = $inventoryInstance->stock_no;
        $inventory_id = $inventoryInstance->inventory_id;
        $this->repository->updateTypesenseIndexService([$inventory_id]);
        $this->repository->purgeWebsiteCache([$inventory_id]);
    }

    /**
     * changeImageActiveState This function changes the active state of the images.
     *
     * @param  mixed $id
     * @return void
     */
    public function changeImageActiveState($id)
    {
        service('authPolicy')->check('Inventory-update');
        $inventoryRepository = new InventoryRepository();
        $data = $this->request->getPost();
        $imageIds = json_decode($data['ids'], true);
        $activation = $data['activation'];
        if (count($imageIds) > 0) {
            $inventoryRepository->changeActive($imageIds, $activation);
            $this->imageList($id);
        }

        $inventoryInstance = $inventoryRepository->model->find($id);
        $stock_no = $inventoryInstance->stock_no;
        $inventory_id = $inventoryInstance->inventory_id;
        $this->repository->updateTypesenseIndexService([$inventory_id]);
        $this->repository->purgeWebsiteCache([$inventory_id]);
    }


    /**
     * zipWebsiteImages This function Zips the images shown on the website.
     *
     * @param  mixed $id
     * @return void
     */
    public function zipWebsiteImages($id)
    {
        $throttler = Services::throttler(false);

        service('authPolicy')->check('Inventory-read');

        if ($throttler->check('INVENTORY_BULK_IMAGE_DOWNLOAD_' . md5(db_connect()->database), 10, DAY, 1) || service('authPolicy')->isSuperOwner()) {
            try {
                $inventory = $this->repository->show($id);
                if (empty($inventory)) {
                    throw new \Exception("Inventory Couldn't be found.", 1);
                }
            } catch (\Exception $ex) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['error' => "Couldn't find the Inventory."])->send();
                exit;
            }
            //Get Inventory Images And Download them
            $gallery = $this->repository->onlyActiveImagesWithStaticImages($id);

            $images_to_download = [];

            foreach ($gallery as $image) {
                $image_url = empty($image['image_with_overlay_url']) ? $image['image_url'] : $image['image_with_overlay_url'];
                $image_url = Utility::getImageUrl($image_url);
                array_push($images_to_download, $image_url);
            }

            //ZIP
            $options = new \ZipStream\Option\Archive();
            $options->setSendHttpHeaders(true);

            // create a new zipstream object
            $zip = new \ZipStream\ZipStream('' . $inventory['year'] . '-' . $inventory['make'] . '-' . $inventory['model'] . '.zip', $options);

            if (empty($images_to_download)) {
                $this->response->setContentType('application/json')->setStatusCode('400')
                    ->setJSON(['error' => "Couldn't find any images."])->send();
                $zip->finish();
            } else {

                foreach ($images_to_download as $image_url) {
                    $fp = tmpfile();
                    fwrite($fp, file_get_contents($image_url));
                    rewind($fp);
                    $zip->addFileFromStream(substr($image_url, strrpos($image_url, '/') + 1), $fp);
                    fclose($fp);
                }
                $zip->finish();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('429')->setJSON(['error' => "Exceeded Daily Limit"])->send();
        }
    }

    /**
     * saveFile This function is to save the file associated with a particular inventory.
     *
     * @param  mixed $id
     * @return void
     */
    public function saveFile($id)
    {
        service('authPolicy')->check('Inventory-update');
        $data = $this->request->getPost();
        $inventory_id = -1;
        $stock_no = null;
        $inventoryRepository = new InventoryRepository();
        try {

            $inventoryInstance = $inventoryRepository->model->find($id);
            $inventory_id = $inventoryInstance->inventory_id;
            $stock_no = $inventoryInstance->stock_no;
        } catch (\Exception $ex) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['error' => "Couldn't find the Inventory."])->send();
            exit;
        }


        $file = $this->request->getFile('file');
        $bool = is_null($file);
        if ($bool) {
            // Step 1 Check if the current input is a file and delete it.
            // Then update the field
            $row = $inventoryRepository->getFile($this->request->getPost('header'), $inventory_id);

            if (count($row) > 0) {
                $file_path = $row[0]['file_url'];
                if (gettype($file_path) == "string") {
                    CloudStorageFactory::getInstance()->delete($file_path);
                }
            }

            $data = [];
            //$data['image_url'] = $name;
            $data['file_url'] = $this->request->getPost('file_url');
            $data['inventory_id'] = $inventory_id;
            $data['header'] = $this->request->getPost('header');
            $data['file'] = 0;
            $inventoryRepository->storeFile($data);
            $this->repository->updateTypesenseIndexService([$data['inventory_id']]);
            $this->repository->purgeWebsiteCache([$data['inventory_id']]);
            $this->response->setContentType('application/json')->setJSON(["success" => "File Url Added.", "file_url" => $data['file_url']])->send();
        } else {

            // Step 1 check file if it is valid. If it is check if the file is there and then delete it.
            $validated = $this->validate([
                'file' => [
                    'uploaded[file]',
                    'mime_in[file,application/pdf]',
                    'max_size[file,32000]',
                ],
            ]);

            if ($validated) {
                //Delete existing file and insert new file.
                $row = $inventoryRepository->getFile($this->request->getPost('header'), $inventory_id);
                if (count($row) > 0) {
                    $file_path = $row[0]['file_url'];
                    if (gettype($file_path) == "string") {
                        CloudStorageFactory::getInstance()->delete($file_path);
                    }
                }

                $file = $this->request->getFile('file');
                $name = $file->getRandomName();

                CloudStorageFactory::getInstance()->save($file, ROOT_FILE . '/' . md5(db_connect()->database) . '/' . $name);
                /** Data image_url, inventory_id, sort_rank */
                $data = [];
                //$data['image_url'] = $name;
                $data['file_url'] = "/" . ROOT_FILE . "/" . md5(db_connect()->database) . "/" . $name;
                $data['inventory_id'] = $inventory_id;
                $data['header'] = $this->request->getPost('header');
                $data['file'] = 1;
                $inventoryRepository->storeFile($data);
                $this->repository->updateTypesenseIndexService([$data['inventory_id']]);
                $this->repository->purgeWebsiteCache([$data['inventory_id']]);
                $this->response->setContentType('application/json')->setJSON(["success" => "File Uploaded Successfully.", "file_url" => $data['file_url']])->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
                exit;
            }
        }
    }

    /**
     * deleteFile This function is to delete the file for a particular inventory.
     *
     * @param  int $id Id of the inventory
     * @return void
     */
    public function deleteFile($id)
    {
        service('authPolicy')->check('Inventory-update');
        $data = $this->request->getPost();
        $inventory_id = -1;
        $stock_no = null;
        $inventoryRepository = new InventoryRepository();
        try {
            $inventoryInstance = $inventoryRepository->model->find($id);
            $inventory_id = $inventoryInstance->inventory_id;
            $stock_no = $inventoryInstance->stock_no;
        } catch (\Exception $ex) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['error' => "Couldn't find the Inventory."])->send();
            exit;
        }

        $response = $inventoryRepository->deleteFile($data['header'], $inventory_id);
        $this->repository->updateTypesenseIndexService([$inventory_id]);
        $this->repository->purgeWebsiteCache([$inventory_id]);
        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    /**
     * listFile Retrieves list of files related to an inventory.
     *
     * @param  int $id Id of an inventory
     * @return void
     */
    public function listFile($id)
    {
        service('authPolicy')->check('Inventory-update');
        $inventory_id = -1;
        $inventoryRepository = new InventoryRepository();
        try {
            $inventory_id = $inventoryRepository->model->find($id)->inventory_id;
        } catch (\Exception $ex) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['error' => "Couldn't find the Inventory."])->send();
            exit;
        }

        $files = $inventoryRepository->fileList($id);
        $this->response->setContentType('application/json')->setJSON([
            'files' => $files,
        ])->send();
    }

    /**
     * Sticker Retrieves Window Sticker file related to an inventory for download.
     *
     * @param  string $id Inventory ID
     * @return string
     */
    public function sticker(int $id)
    {
        $template_path = ROOTPATH . "/public/wslayout/";
        $image = ROOTPATH . "public/media/logo/placeholder.png";
        $business_logo = ROOTPATH . "/public/media/logo/";
        $sticker_name = time() . mt_rand() . ".pdf";
        $window_sticker = ROOTPATH . "/public/media/ws/" . $sticker_name;
        $clean_up = false;

        try {
            $inventoryRepository = new InventoryRepository();
            $inventory = $inventoryRepository->show($id);

            $bWebsite = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_website', 'value');
            $iType = (new ConfigurationRepository())->findConfigurationColumnsByKey('inventory_type', 'value');
            $wordPressType = (new ConfigurationRepository())->findConfigurationColumnsByKey('wordpress_type', 'value');

            $content = Utility::urlCreation($bWebsite->value, $iType->value, $inventory, $wordPressType->value);
            $QRimage = (new InventoryRepository())->getWindowSticker($content, 130);

            $WStemplate = (new ConfigurationRepository())->findConfigurationColumnsByKey('wsticker_template', 'value');
            if ($WStemplate) {
                $template = $WStemplate->value . ".php";
            }

            $image_placeholder = (new ImageRepository())->getPlaceholder();


            $gallery = $inventoryRepository->onlyActiveImagesWithStaticImages($id);
            if (!empty($gallery)) {
                $gallery = array_slice($gallery, 0, 1);

                $imageX = (isset($gallery[0]['image_with_overlay_url']) && ($gallery[0]['image_with_overlay_url'] != "")) ? $gallery[0]['image_with_overlay_url'] : $gallery[0]['image_url'];

                if (CloudStorageFactory::getInstance()->has($imageX)) {
                    CloudStorageFactory::getInstance()->get($imageX);
                    $imageX = ROOTPATH . "/public/" . $imageX;
                    if (file_exists($imageX)) {
                        $image = $imageX;
                        $clean_up = true;
                    }
                } else {
                    if (@getimagesize($imageX)) {
                        $image = $imageX;
                        $clean_up = true;
                    }
                }
            }

            if (isset($image_placeholder['image_url']) && !$clean_up) {
                if (CloudStorageFactory::getInstance()->has($image_placeholder['image_url'])) {
                    CloudStorageFactory::getInstance()->get($image_placeholder['image_url']);
                    $imageX = ROOTPATH . "/public/" . $image_placeholder['image_url'];
                    if (file_exists($imageX)) {
                        $image = $imageX;
                        $clean_up = true;
                    }
                }
            }

            $disclosures = (new InventoryCommonDependentRepository())
                ->switchInventoryCommonDependentState('disclosures')
                ->filterByInventoryIdAsArray($id);

            $inventory['certified_or_asis'] = "As-Is";

            if (!empty($disclosures)) {
                foreach ($disclosures as $disclosure) {
                    if (!empty($disclosure['question'])) {
                        if ($disclosure['question'] === "Certified" && $disclosure['answer'] === "Yes") {
                            $inventory['certified_or_asis'] = "Certified";
                        }
                    }
                }
            }

            $business = (new LocationRepository())->getLocationByName($inventory['location']);
            $blogo = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_logo', 'value');
            $wsorientation = (new ConfigurationRepository())->findConfigurationColumnsByKey('show_landscapewindowsticker', 'value');

            $inventory['business_logo'] = ($blogo->value == "") ? "" : $business_logo . $blogo->value;
            $inventory['business_name'] = $business['business_name'];
            $inventory['business_address'] = $business['street'];
            $inventory['business_state'] = $business['state'];
            $inventory['business_city'] = $business['city'];
            $inventory['business_code'] = $business['zipcode'];
            $inventory['business_country'] = $business['country'];
            $inventory['business_phone'] = $business['phone'];
            $inventory['business_fax'] = $business['fax'];


            if (isset($inventory['business_phone'])) {
                $inventory['business_phone'] = Utility::format_phone_us($inventory['business_phone']);
            }
            $inventory['business_email'] = $business['email'];
            $inventory['oprice'] = $inventory['price']; // Need to place above $inventory['price']
            $inventory['price'] = ($inventory['special_price_on'] != 0) ? $inventory['special_price'] : $inventory['price'];
            $inventory['image'] = $image;
            $inventory['QR'] = "<img src=\"" . $QRimage . "\" />";
            $inventory['website'] = $bWebsite->value;

            // $contentQ = file_get_contents($template_path . $template); 
            $contentQ = view('wslayout/' . $template, $inventory);
            $content = $inventoryRepository->parseWindowStickerContent($contentQ, $inventory);

            $snappy = new Pdf();
            //$path = ROOTPATH . 'vendor/wemersonjanuario/wkhtmltopdf-windows/bin/64bit/wkhtmltopdf';
            $path = \h4cc\WKHTMLToPDF\WKHTMLToPDF::PATH;
            $snappy->setBinary($path);
            if ($wsorientation->value) {
                $snappy->setOption('orientation', 'Landscape');
            }
            $snappy->setOption('margin-top', '0mm');
            $snappy->setOption('margin-bottom', '0mm');
            $snappy->setOption('margin-right', '0mm');
            $snappy->setOption('margin-left', '0mm');
            $snappy->generateFromHtml($content, $window_sticker);
        } catch (\TypeError $ex) {
            //echo $ex->getMessage();
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['error' => "Couldn't find the Inventory."])->send();
            exit;
        } catch (\Error $error) {
            //echo $error->getMessage();
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['error' => "Couldn't find the Inventory."])->send();
            exit;
        }

        $this->response->setContentType('application/json')->setJSON(['file' => "/media/ws/" . $sticker_name])->send();

        //cleaning up the image after the work is done.
        if ($clean_up) {
            if (file_exists($image)) {
                unlink($image);
            }
        }
    }


    /**
     * Sticker Retrieves Window Sticker file related to an inventory for download.
     *
     * @param  string $id Inventory ID
     * @return string
     */
    public function stickerV2(int $id)
    {
        $image = ROOTPATH . "public/media/logo/placeholder.png";
        $business_logo = ROOTPATH . "/public/media/logo/";
        $clean_up = false;

        try {
            $inventoryRepository = new InventoryRepository();
            $inventory = $inventoryRepository->show($id);

            $bWebsite = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_website', 'value');
            $iType = (new ConfigurationRepository())->findConfigurationColumnsByKey('inventory_type', 'value');
            $wordPressType = (new ConfigurationRepository())->findConfigurationColumnsByKey('wordpress_type', 'value');

            $content = Utility::urlCreation($bWebsite->value, $iType->value, $inventory, $wordPressType->value);
            $QRimage = (new InventoryRepository())->getWindowSticker($content, 130);

            $WStemplate = (new ConfigurationRepository())->findConfigurationColumnsByKey('wsticker_template', 'value');
            if ($WStemplate) {
                $template = $WStemplate->value . ".php";
            }

            $image_placeholder = (new ImageRepository())->getPlaceholder();


            $gallery = $inventoryRepository->onlyActiveImagesWithStaticImages($id);
            if (!empty($gallery)) {
                $gallery = array_slice($gallery, 0, 1);

                $imageX = (isset($gallery[0]['image_with_overlay_url']) && ($gallery[0]['image_with_overlay_url'] != "")) ? $gallery[0]['image_with_overlay_url'] : $gallery[0]['image_url'];

                if (CloudStorageFactory::getInstance()->has($imageX)) {
                    CloudStorageFactory::getInstance()->get($imageX);
                    $imageX = ROOTPATH . "/public/" . $imageX;
                    if (file_exists($imageX)) {
                        $image = $imageX;
                        $clean_up = true;
                    }
                } else {
                    if (@getimagesize($imageX)) {
                        $image = $imageX;
                        $clean_up = true;
                    }
                }
            }

            if (isset($image_placeholder['image_url']) && !$clean_up) {
                if (CloudStorageFactory::getInstance()->has($image_placeholder['image_url'])) {
                    CloudStorageFactory::getInstance()->get($image_placeholder['image_url']);
                    $imageX = ROOTPATH . "/public/" . $image_placeholder['image_url'];
                    if (file_exists($imageX)) {
                        $image = $imageX;
                        $clean_up = true;
                    }
                }
            }

            $disclosures = (new InventoryCommonDependentRepository())
                ->switchInventoryCommonDependentState('disclosures')
                ->filterByInventoryIdAsArray($id);

            $inventory['certified_or_asis'] = "As-Is";

            if (!empty($disclosures)) {
                foreach ($disclosures as $disclosure) {
                    if (!empty($disclosure['question'])) {
                        if ($disclosure['question'] === "Certified" && $disclosure['answer'] === "Yes") {
                            $inventory['certified_or_asis'] = "Certified";
                        }
                    }
                }
            }

            $business = (new LocationRepository())->getLocationByName($inventory['location']);
            $blogo = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_logo', 'value');
            $wsorientation = (new ConfigurationRepository())->findConfigurationColumnsByKey('show_landscapewindowsticker', 'value');

            $inventory['business_logo'] = ($blogo->value == "") ? "" : $business_logo . $blogo->value;
            $inventory['business_name'] = $business['business_name'];
            $inventory['business_address'] = $business['street'];
            $inventory['business_state'] = $business['state'];
            $inventory['business_city'] = $business['city'];
            $inventory['business_code'] = $business['zipcode'];
            $inventory['business_country'] = $business['country'];
            $inventory['business_phone'] = $business['phone'];
            $inventory['business_fax'] = $business['fax'];


            if (isset($inventory['business_phone'])) {
                $inventory['business_phone'] = Utility::format_phone_us($inventory['business_phone']);
            }
            $inventory['business_email'] = $business['email'];
            $inventory['oprice'] = $inventory['price'];
            $inventory['price'] = ($inventory['special_price_on'] != 0) ? $inventory['special_price'] : $inventory['price'];
            $inventory['image'] = $image;
            $inventory['QR'] = "<img src=\"" . $QRimage . "\" />";
            $inventory['website'] = $bWebsite->value;

            $contentQ = view('wslayout/' . $template, $inventory);
            $content = $inventoryRepository->parseWindowStickerContent($contentQ, $inventory);

            $snappy = new Pdf();

            $path = \h4cc\WKHTMLToPDF\WKHTMLToPDF::PATH;
            $snappy->setBinary($path);
            if ($wsorientation->value) {
                $snappy->setOption('orientation', 'Landscape');
            }
            $snappy->setOption('margin-top', '0mm');
            $snappy->setOption('margin-bottom', '0mm');
            $snappy->setOption('margin-right', '0mm');
            $snappy->setOption('margin-left', '0mm');

            $this->response->setHeader('Content-Type', 'application/pdf');
            echo $snappy->getOutputFromHtml($content);
        } catch (\TypeError $ex) {
            //echo $ex->getMessage();
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['error' => "Couldn't find the Inventory."])->send();
            exit;
        } catch (\Error $error) {
            //echo $error->getMessage();
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['error' => "Couldn't find the Inventory."])->send();
            exit;
        }

        //cleaning up the image after the work is done.
        if ($clean_up) {
            if (file_exists($image)) {
                unlink($image);
            }
        }
    }

    /**
     * updateSearchIndex This method is to update the search index for all inventories.
     *
     * @return void
     */
    public function updateSearchIndex()
    {
        $inventory_repository = new InventoryRepository();

        $response = $inventory_repository->updateSearchIndex();
        $inventory_repository->deleteAndReIndexService();
        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send()->setHeader('Access-Control-Allow-Origin', '*');
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send()->setHeader('Access-Control-Allow-Origin', '*');
        }
    }

    public function generatePDF($bill_type, $id)
    {
        service('authPolicy')->check('Inventory-read');

        if ($bill_type == 'public-purchase' || $bill_type == 'dealer-purchase') {
            $this->existenceOrRightsCheckForInventory();
        }
        try {
            $inventoryRepository = new InventoryRepository();
            $response['inventory'][0] = $inventoryRepository->show($id);

            $location = !empty($response['inventory'][0]['location']) ? $response['inventory'][0]['location'] : "";

            $response['location'] = (new LocationRepository())->getLocationByName($location);

            if (!empty($response['inventory'][0])) {
                $response['purchase_details'] = (new InventoryPurchaseRepository())
                    ->findByInventoryId($response['inventory'][0]['inventory_id']);
            }

            if (!empty($response['inventory'][0])) {
                $response['disclosures'] = (new InventoryCommonDependentRepository())
                    ->switchInventoryCommonDependentState('disclosures')
                    ->filterByInventoryIdAsArray($id);
            }

            if (!empty($response['inventory'][0])) {
                $response['inventory_inspection'] = (new InventoryCommonDependentRepository())
                    ->switchInventoryCommonDependentState('internal-inspection')
                    ->filterByInventoryIdAsArray($id);
            }

            $configuration = new ConfigurationRepository();

            $pdf = null;

            $response['current_user'] = (new TenantUserMetaDataRepository())->getUserMetaDataById(service('authPolicy')->id);

            if ($bill_type == 'test-drive') {
                $test_drive_template = ($configuration->findConfigurationColumnsByKey('test_drive_template', ['value']))->value;
                $pdf = PDFFillerFactory::getInstance($test_drive_template, PDFFillerConstant::TEST_DRIVE);
            } else if ($bill_type == 'public-purchase') {
                $public_purchase_template = ($configuration->findConfigurationColumnsByKey('public_purchase_template', ['value']))->value;
                $pdf = PDFFillerFactory::getInstance($public_purchase_template, PDFFillerConstant::PUBLIC_PURCHASE_AGREEMENT);
            } else if ($bill_type == 'dealer-purchase') {
                $dealer_purchase_template = ($configuration->findConfigurationColumnsByKey('dealer_purchase_template', ['value']))->value;
                $pdf = PDFFillerFactory::getInstance($dealer_purchase_template, PDFFillerConstant::PUBLIC_PURCHASE_AGREEMENT);
            } else if ($bill_type == 'vehicle-disclosures') {
                $vehicle_disclosure_template = ($configuration->findConfigurationColumnsByKey('vehicle_disclosure_template', ['value']))->value;
                $pdf = PDFFillerFactory::getInstance($vehicle_disclosure_template, PDFFillerConstant::VEHICLE_DISCLOSURES);
            } else if ($bill_type == 'internal-inspection') {
                $vehicle_internal_inspection_template = ($configuration->findConfigurationColumnsByKey('vehicle_internal_inspection_template', ['value']))->value;
                $pdf = PDFFillerFactory::getInstance($vehicle_internal_inspection_template, PDFFillerConstant::INTERNAL_INSPECTION_CHECKLIST);
            }
            if ($bill_type == 'buyers-guide') {
                $buyers_guide_template = ($configuration->findConfigurationColumnsByKey('buyers_guide_template', ['value']))->value;
                $pdf = PDFFillerFactory::getInstance($buyers_guide_template, PDFFillerConstant::BUYERS_GUIDE);
            }

            $this->response->setHeader('Content-Type', 'application/pdf');
            $pdf->dataMap($response)->output();
        } catch (\Error $error) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "Couldn't generate the Test Driving form."])->send();
            exit;
        }
    }


    public function printInventoryRecap($id)
    {
        service('authPolicy')->check('Inventory-read');
        $this->existenceOrRightsCheckForInventory();

        $inventory = $this->repository->show($id);

        if ($inventory == null || !isset($inventory)) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Inventory couldn't be found."
            ])->send();
            exit;
        }

        $inventory_purchase_repository = new InventoryPurchaseRepository();
        $purchase = $inventory_purchase_repository->findByInventoryId($id);

        $inventory_expense_repository = new InventoryCostRepository();
        $expenses = $inventory_expense_repository->findByInventoryId($id);

        if (is_file(APPPATH . 'Views/templates/inventory-recap.php')) {
            $snappy = new Pdf();
            $path = \h4cc\WKHTMLToPDF\WKHTMLToPDF::PATH;
            $snappy->setBinary($path);
            $template = view('Views/templates/inventory-recap', array_merge(
                $inventory,
                ['purchase' => $purchase],
                ['expenses' => $expenses]
            ));

            $this->response->setHeader('Content-Type', 'application/pdf');
            echo $snappy->getOutputFromHtml($template);
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => `Purchase & expenses template couldn't be found.`])->send();
        }
    }

    /**
     * purgeAllInventory This deletes all the inventories.
     *
     * @return void
     */
    public function purgeAllInventory()
    {
        $data = $this->request->getPost();
        $purge = (new ConfigurationRepository())->findConfigurationColumnsByKey('enable_purge', 'value')->value;
        $purge = intval($purge);

        $validated = $this->validate([
            'password' => [
                'label' => 'Password',
                'rules' => 'required|min_length[6]',
            ]
        ]);

        if (empty($purge)) {
            $this->validator->setError('configuration', 'Purge Functionality not enabled for this tenant.');
        }

        if ($validated) {
            $valid_password = password_verify($data['password'], getEnv('inventory.purge'));
            if (!$valid_password) {
                $this->validator->setError('password', 'Wrong Password');
            }
        }

        if (service('authPolicy')->isSuperOwner() && $validated && $valid_password && $purge) {

            $response = $this->repository->purgeAllInventory();
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {

            $errors = !service('authPolicy')->isSuperOwner() ? ['errors' => ERROR_RESOURCE_PERMISSION] : $this->validator->getErrors();

            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($errors)->send();
        }
    }


    public function purgeDelete()
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            $response = $this->repository->purgeDelete();
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function markAllInventoriesAsSold()
    {
        $enable_sold_button = (new ConfigurationRepository())->findConfigurationColumnsByKey('enable_inventories_sold_button', 'value')->value;
        $enable_sold_button = intval($enable_sold_button);

        if (service('authPolicy')->isSuperOwner() && $enable_sold_button) {
            $response = $this->repository->markAllInventoriesAsSold();
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }


    public function markAllInventoriesAsUnpublished()
    {
        $enable_unpublished_button = (new ConfigurationRepository())->findConfigurationColumnsByKey('enable_inventories_unpublished_button', 'value')->value;
        $enable_unpublished_button = intval($enable_unpublished_button);

        if (service('authPolicy')->isSuperOwner() && $enable_unpublished_button) {
            $response = $this->repository->markAllInventoriesAsUnpublished();
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }


    public function purgeInventoryAssets()
    {
        $enable_purge_inventory_assets = (new ConfigurationRepository())->findConfigurationColumnsByKey('enable_inventory_purge_assets', 'value')->value;
        $enable_purge_inventory_assets = intval($enable_purge_inventory_assets);

        if (service('authPolicy')->isSuperOwner() && $enable_purge_inventory_assets) {
            $response = $this->repository->purgeInventoryAssets();
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    /**
     * noteCreate This creates a new note corresponding to the inventory along with an attachment.
     *
     * @param  int $id This is the inventory Id for which the note is supposed to be created.
     * @return void
     */
    public function noteCreate($id)
    {

        $inventory = $this->repository->show($id);

        if ($inventory == null || !isset($inventory['inventory_id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Inventory couldn't be found."
            ])->send();
            exit;
        }

        $data = $this->request->getPost();
        $validated = true;
        $response = [];

        if (!array_key_exists('attachment', $this->request->getFiles())) {
            //save details
            $response = $this->repository->saveNote($id, $data);
        } else {
            $validated = $this->validate([
                'attachment' => [
                    'uploaded[attachment]',
                    'mime_in[attachment,application/pdf,image/jpg,image/jpeg,image/gif,image/png]',
                    'max_size[attachment,32000]',
                ],
            ]);

            if ($validated) {
                //save file
                $file = $this->request->getFile('attachment');
                $name = $file->getRandomName();

                CloudStorageFactory::getInstance()->save($file, ROOT_FILE . '/' . md5(db_connect()->database) . '/' . $name);
                $data['attachment'] = "/" . ROOT_FILE . "/" . md5(db_connect()->database) . "/" . $name;

                //save details
                $response = $this->repository->saveNote($id, $data);
            } else {

                //throw error
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
                exit;
            }
        }

        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    /**
     * showNote This method accepts two paramters Inventory id as well as id of the note.
     * Inventory Id is simply used for routing purpose.
     *
     * @param  mixed $inventory_id
     * @param  mixed $id
     * @return void
     */
    public function showNote($inventory_id, $id)
    {
        $inventory = $this->repository->show($inventory_id);

        if ($inventory == null || !isset($inventory['inventory_id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Inventory couldn't be found."
            ])->send();
            exit;
        }

        $response = $this->repository->showNote($id);
        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    /**
     * allNotes This method shows the list of all the notes related to a particular inventory.
     *
     * @param  mixed $inventory_id Inventory Id.
     * @return void
     */
    public function allNotes($inventory_id)
    {
        $inventory = $this->repository->show($inventory_id);

        if ($inventory == null || !isset($inventory['inventory_id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Inventory couldn't be found."
            ])->send();
            exit;
        }

        $response = $this->repository->allNotes($inventory_id);
        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    /**
     * updateNote This method allows the user to update the note, if he is note owner or tenant owner or superowner.
     *
     * @param  int $inventory_id Inventory Id.
     * @param  int $id Note Id to be updated.
     * @return void
     */
    public function updateNote($inventory_id, $id)
    {

        $inventory = $this->repository->show($inventory_id);

        if ($inventory == null || !isset($inventory['inventory_id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Inventory couldn't be found."
            ])->send();
            exit;
        }

        $note = $this->repository->showNote($id);
        if (!empty($note)) {
            if (!(service('authPolicy')->hasOwnerRole()
                || service('authPolicy')->isSuperOwner()
                || service('authPolicy')->isResourceOwner($note['user_id']))) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'You do not have permission to update note.'])->send();
                exit;
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'Note could not be found.'])->send();
            exit;
        }

        $data = $this->request->getPost();
        $response = $this->repository->updateNote($id, $data);

        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    /**
     * deleteNote This method allows the user to delete the note, if he is note owner or tenant owner or superowner.
     *
     * @param  int $inventory_id Inventory Id.
     * @param  int $id Note Id to be updated.
     * @return void
     */
    public function deleteNote($inventory_id, $id)
    {
        $inventory = $this->repository->show($inventory_id);

        if ($inventory == null || !isset($inventory['inventory_id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Inventory couldn't be found."
            ])->send();
            exit;
        }

        $note = $this->repository->showNote($id);
        if (!empty($note)) {
            if (!(service('authPolicy')->hasOwnerRole()
                || service('authPolicy')->isSuperOwner()
                || service('authPolicy')->isResourceOwner($note['user_id']))) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'You do not have permission to delete note.'])->send();
                exit;
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'Note could not be found.'])->send();
            exit;
        }
        $response = $this->repository->deleteNote($id);
        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }


    /**
     * getInventoryDeals
     *
     * @param  mixed $id
     * @return void
     */
    public function getInventoryDeals($id)
    {

        $this->existenceOrRightsCheckForInventory();

        $available_inventory = $this->repository->show($id);

        if ($available_inventory == null || !isset($available_inventory['inventory_id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Inventory couldn't be found."
            ])->send();
            exit;
        }

        $this->repository->setModel(new DealInventoryPivotModel());

        $response = $this->repository
            ->filterData('stock_no', $available_inventory['stock_no'])
            ->index(null, null, null, null, 20, null, false, 'view_id', 'deals');

        foreach ($response['deals'] as $key => $deal) {
            $response['deals'][$key] = (new DealRepository())->show($deal['deal_id']);

            if (isset($response['deals'][$key])) {

                $this->repository->setModel(new DealCustomerPivotModel());

                $customers  = $this->repository
                    ->filterData('deal_id', $deal['deal_id'])
                    ->index(null, null, null, null, 20, null, false, 'view_id', 'customers');

                if (isset($customers['customers'])) {
                    if (!empty($customers['customers'])) {

                        foreach ($customers['customers'] as $sub_key => $customer) {
                            $customer_entity = (new CustomerRepository())->show($customer['customer_id']);
                            $customers['customers'][$sub_key]['details'] = $customer_entity;
                        }

                        if (!empty($response['deals'][$key])) {
                            $response['deals'][$key]['customers'] = $customers['customers'];
                        }
                    }
                }
            }
        }
        $response['deals'] = array_values(array_filter($response['deals']));

        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    public function parsePDF($source)
    {
        $validated = $this->validate([
            'file' => [
                'uploaded[file]',
                'mime_in[file,application/pdf]',
                'max_size[file,15000]',
            ],
            'auction_as_seller'  => [
                "label" => 'Auction As Seller',
                "rules" => 'permit_empty|is_natural'
            ],
        ]);

        $data = $this->request->getPost();

        if ($validated) {

            $file_tmp  = $_FILES['file']['tmp_name'];
            $cFile = curl_file_create($file_tmp);
            $success = false;
            $pdfParser = PDFParserFactory::getInstance($source);

            $response = [];

            if ($pdfParser !== null) {
                $pdf_parser_response = $pdfParser->sendRequest($cFile, [
                    'auction_as_seller' => isset($data['auction_as_seller']) ? $data['auction_as_seller'] : 1
                ]);

                if (!empty($pdf_parser_response['status'])) {
                    if ($pdf_parser_response['status']  === 'success') {
                        $success = true;
                        $response = $pdfParser->mapResponse($pdf_parser_response);
                    }
                }
            }
            if ($success) {
                $file = $this->request->getFile('file');
                $name = $file->getRandomName();
                CloudStorageFactory::getInstance()->save($file, ROOT_FILE . '/' . md5(db_connect()->database) . '/' . $name);
                $response['inventory_purchase_url'] = "/" . ROOT_FILE . "/" . md5(db_connect()->database) . "/" . $name;
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "We were unable to parse the PDF."])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    public function vehicleDescriptionReWriter()
    {
        $validated = $this->validate([
            'content' => [
                'label' => 'Content',
                'rules' => 'required',
            ],
        ]);

        $response = [];

        if ($validated) {
            $data = $this->request->getPost();
            $ch = curl_init();

            $instructions = $this->getVehicleDescriptionRewriterAudienceInstruction($data);
            $instructions = $this->getVehicleDescriptionRewriterEmojisInstruction($data, $instructions);
            $instructions = $this->getVehicleDescriptionRewriterPlatformInstruction($data, $instructions);
            $instructions = $this->getVehicleDescriptionLength($data, $instructions);

            $payload = [];
            $payload['instructions'] = $instructions;
            $payload['content'] = $data['content'];

            curl_setopt($ch, CURLOPT_URL, getEnv('zopai.host') . '/services/vehicle-description');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                $response = [
                    'errors' => curl_error($ch)
                ];
            }
            curl_close($ch);

            $response = json_decode($result, true);

            if (!empty($response['success'])) {
                if (!empty($response['description'])) {
                    $response['description'] = nl2br($response['description']);
                }
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    private function getVehicleDescriptionLength($data, array $response = [])
    {
        if (!empty($data['length_of_description'])) {
            switch ($data['length_of_description']) {
                case 'Short':
                    array_push($response, "Vehicle description should be shorter and highlight the key points.");
                    break;

                case 'Long':
                    array_push($response, "Vehicle description should be longer and more comprehensive.");
                    break;
            }
        }

        return $response;
    }

    private function getVehicleDescriptionRewriterPlatformInstruction($data, array $response = [])
    {
        if (!empty($data['platform_optimized'])) {
            switch ($data['platform_optimized']) {
                case 'Social Media':
                    array_push($response, "Optimization the vehicle description for social media platform.");
                    break;

                case 'Website':
                    array_push($response, "Optimization the vehicle description for automotive dealership website.");
                    break;

                case 'Both':
                    array_push($response, "Optimization the vehicle description for automotive dealership website and social media platform.");
                    break;
            }
        }
        return $response;
    }

    private function getVehicleDescriptionRewriterEmojisInstruction($data, array $response = [])
    {
        if (!empty($data['add_emojis'])) {
            switch ($data['add_emojis']) {
                case 'Yes':
                    array_push($response, "Add Emojis to the vehicle description.");
                    break;
            }
        }

        return $response;
    }

    private function getVehicleDescriptionRewriterAudienceInstruction($data, array $response = [])
    {
        if (!empty($data['audience'])) {
            switch ($data['audience']) {
                case 'First-Time':
                    array_push($response, "Target audience for this vehicle is First-Time Buyer");
                    break;

                case 'Family':
                    array_push($response, "Vehicle description for this vehicle should be Family Oriented.");
                    break;

                case 'Luxury':
                    array_push($response, "Target audience for this vehicle is either Luxury or Premium Buyer.");
                    break;

                case 'Enthusiast':
                    array_push($response, "Vehicle description for this vehicle should be towards an auto enthusiast or performance oriented.");
                    break;

                case 'Eco-Conscious':
                    array_push($response, "Vehicle description for this vehicle should be Eco-Conscious oriented.");
                    break;

                case 'Budget':
                    array_push($response, "Target audience for this vehicle is either Price Sensitive or Budget buyet.");
                    break;
                default:
                    # code...
                    break;
            }
        }

        return $response;
    }

    /**
     * existenceOrRightsCheckForInventory Checks if user has enough rights to open the sensitive documents.
     *
     * @return void
     */
    protected function existenceOrRightsCheckForInventory()
    {
        if (!service('authPolicy')->hasElevatedRoles()) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "You do not have enough privelages to see the deal."])->send();
            exit;
        }
    }

    private function isZopStudioEnabled(): bool
    {
        try {
            $enable_zop_studio_config = $this->configuration_repository->findConfigurationColumnsByKeyAsArray('enable_zop_studio', ['value']);
            return !empty($enable_zop_studio_config['value']);
        } catch (\Exception $ex) {
            return false;
        }
    }

    private function includeDefaultExpense($inventory_id, $stock_date = null): void
    {

        try {
            $default_expenses = $this->cost_setting_repository->index(null, null, null, json_encode([
                [
                    'header' => null,
                    'column' => 'include_cost_by_default',
                    'join' => 'AND',
                    'comparator' => "is",
                    "type" => "Single Select",
                    'value_1' => "1",
                    'value_2' => null,
                ]
            ]), null, null, false);

            if (!empty($default_expenses['data_list'])) {
                foreach ($default_expenses['data_list'] as $default_expense) {
                    $default_expense['inventory_id'] = $inventory_id;
                    $default_expense['purchase_date'] = strtotime($stock_date) !== false ? $stock_date : null;
                    unset($default_expense['id']);
                    $this->inventory_cost_repository->create($default_expense);
                }
            }
        } catch (\Exception $ex) {
            log_message('notice', $ex->getMessage());
        }
    }

    private function isDevelopmentEnvironment(): bool
    {
        $ci_env = getenv('CI_ENVIRONMENT');
        return $ci_env === 'development';
    }
}
