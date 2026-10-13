<?php

namespace Tenant\app\Controllers;

use App\Helpers\TenantNonPersistantCache;
use App\Helpers\Utility;
use Tenant\app\Repositories\InventoryRepository;
use Carbon\Carbon;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\ImageRepository;
use Tenant\app\Repositories\LocationRepository;

/**
 * WebsiteController : This Controller corresponds to the public api that can be used in either Wordpress or any 
 * client capable of consuming http APIs. These are public APIs.
 */
class WebsiteController extends \App\Controllers\BaseController
{
    public function inventory()
    {
        $data = null;

        $bWebsite = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_website', 'value');
        $iType = (new ConfigurationRepository())->findConfigurationColumnsByKey('inventory_type', 'value');
        $wordPressType = (new ConfigurationRepository())->findConfigurationColumnsByKey('wordpress_type', 'value');

        $query = [];
        $inventoryRepository = new InventoryRepository();
        $query = null;
        if (count($_GET) > 0) {
            $query = $inventoryRepository->prepareDataForFilter($_GET);
        }

        $items = 10;
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

        if ($this->request->getGet('only_current_month')) {
            if ($this->request->getGet('only_current_month') == "true") {
                $data->filterOnCurrentMonthAndYear("stock_date");
            }
        }

        $data = $data->index(null, $header = null, null, $query, $items, $sort, $paginate, 'inventory_id', 'inventory');

        foreach ($data['inventory'] as $key => $value) {
            $data['inventory'][$key] = $inventoryRepository->replacePlaceholders($value);
            $data['inventory'][$key]['page_url'] = Utility::urlCreation($bWebsite->value, $iType->value, $value, $wordPressType->value);
        }

        $this->response->setContentType('application/json')->setJSON($data)->send();
    }

    /**
     * inventory_id
     *
     * @param  mixed $id
     * @return void
     * @Deprecated
     */
    public function inventory_id($id)
    {
        $inventoryRepository = new InventoryRepository();
        $inventory = $inventoryRepository->show($id);
        $inventory = $inventoryRepository->replacePlaceholders($inventory);
        $inventory['files'] = $inventoryRepository->fileList($id);
        $this->response->setContentType('application/json')->setJSON($inventory)->send();
    }

    public function showCompleteInventory($id)
    {
        $inventoryRepository = new InventoryRepository();
        $inventory = $inventoryRepository->show($id);
        $inventory = $inventoryRepository->replacePlaceholders($inventory);
        $inventory['files'] = $inventoryRepository->fileList($id);
        $inventory['gallery'] = $inventoryRepository->onlyActiveImagesWithStaticImages($id);
        $this->response->setContentType('application/json')->setJSON($inventory)->send();
    }

    public function filters()
    {
        $query = [];
        $result = [];
        $status = false;
        $search = '';
        $inventoryRepository = new InventoryRepository();
        $query = null;
        if (count($_GET) > 0) {
            $query = $inventoryRepository->prepareDataForFilter($_GET);
        }

        $items = 10;
        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }

        if ($this->request->getGet('status')) {
            $status = $this->request->getGet('status') == "true" ? true : false;
        }

        if ($this->request->getGet('search')) {
            $search = $this->request->getGet('search');
        }

        $result = $inventoryRepository->allColumnAggregation($query, $status, $search);

        $this->response->setContentType('application/json')->setJSON($result)->send();
    }

    public function inventory_images($id)
    {
        $inventoryRepository = new InventoryRepository();
        $gallery = $inventoryRepository->onlyActiveImagesWithStaticImages($id);

        $this->response->setContentType('application/json')->setJSON([
            'gallery' => $gallery,
        ])->send();
    }


    public function siteMap()
    {
        $query = [];
        $inventoryRepository = new InventoryRepository();
        $query = null;
        if (count($_GET) > 0) {
            $query = $inventoryRepository->prepareDataForFilter($_GET);
        }


        $column = 'created_at';
        $order = 'DESC';

        $data = $inventoryRepository->onlyVisible();

        $bWebsite = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_website', 'value');
        $iType = (new ConfigurationRepository())->findConfigurationColumnsByKey('inventory_type', 'value');
        $wordPressType = (new ConfigurationRepository())->findConfigurationColumnsByKey('wordpress_type', 'value');


        $data = $data->index(null, null, null, $query, null, null, false, 'inventory_id', 'inventories');

        foreach ($data['inventories'] as $key => $value) {
            $data['inventories'][$key]['url'] = Utility::urlCreation($bWebsite->value, $iType->value, $value, $wordPressType->value);
            $data['inventories'][$key]['updated_at'] = Carbon::parse($data['inventories'][$key]['updated_at'])->toAtomString();
        }

        $this->response->setContentType("text/xml")->send();
        echo View('sitemap/template', $data);
    }

    public function getVehicleMakes()
    {
        $inventoryMakes = (new ConfigurationRepository())->findConfigurationColumnsByKey('inventory_makes', 'value');
        $data = $inventoryMakes->value;
        $this->response->setContentType('application/json')->setJSON($data)->send();
    }

    public function getWebsiteConfiguration()
    {

        $cache_key = 'phaeton_config';
        $cached_config = TenantNonPersistantCache::get($cache_key);

        $response = [];
        $response['dealership'] = [];

        if (!empty($cached_config)) {
            $this->response->setContentType('application/json')->setJSON($cached_config)->send();
            exit;
        }

        $location_response = (new LocationRepository())->filterData('status', '1')->filterData('show_on_website', '1')->index(null, $header = null, null, null, null, '{"default" : "DESC"}', false, 'location_view_id', 'locations');

        $location_count = 1;

        if (!empty($location_response['locations'])) {
            foreach ($location_response['locations'] as $location) {
                if ($location_count === 1) {
                    $response['dealership']['dealership_name'] = $location['business_name'];
                } else {
                    $response['dealership']['show_address_' . $location_count] = true;
                }

                $response['dealership']['full_address_' . $location_count] = $location['street'];
                $response['dealership']['city_' . $location_count] = $location['city'];
                $response['dealership']['province_' . $location_count] = $location['state'];
                $response['dealership']['postal_code_' . $location_count] = $location['zipcode'];
                $response['dealership']['country_' . $location_count] = $location['country'];
                $response['dealership']['email_' . $location_count] = $location['email'];
                $response['dealership']['sales_number_' . $location_count] = Utility::format_phone_us($location['phone']);
                $response['dealership']['fax_' . $location_count] = $location['fax'];
                $response['dealership']['address_' . $location_count . '_bar'] = $location['address_bar'];
                $response['dealership']['address_map_url_' . $location_count] = $location['address_map_url'];

                ++$location_count;
            }
        }

        $website_configs = (new ConfigurationRepository())->getModuleConfigurationAsArray('website');

        if (!empty($website_configs)) {
            foreach ($website_configs as $website_config) {
                if ($website_config['key'] === 'working_hours') {
                    $value =  json_decode($website_config['value'], true);
                    $response['dealership'][$website_config['key']] =  gettype($value) === "array" ? $value : [];
                } else {
                    $response['dealership'][$website_config['key']] = $website_config['value'];
                }
            }
        }

        $image_placeholder = (new ImageRepository())->getPlaceholder();

        if (!empty($image_placeholder)) {
            $response['dealership']['default_placeholder_image'] = Utility::getImageUrl($image_placeholder['image_url']);
        }

        $response['site']['collection'] = md5(db_connect()->database);

        TenantNonPersistantCache::save($cache_key, $response, 28800);

        $this->response->setContentType('application/json')->setJSON($response)->send();
    }
}
