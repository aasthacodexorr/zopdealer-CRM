<?php

namespace Tenant\app\Controllers;

use Tenant\app\Models\CraigslistListModel;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\FacebookMarketPlaceRepository;
use Tenant\app\Repositories\InventoryRepository;
use Tenant\app\Libraries\Mapper\Craigslist;
use App\Helpers\Utility;
use App\Helpers\SyndicationUtility;
use Tenant\app\Repositories\CraigslistRepository;

class CraigslistController extends \App\Controllers\BaseController
{

    /**
     * repository This repository corresponds to the Update or Create logic for Craigslist logs table.
     *
     * @var CraigslistRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new CraigslistRepository();
    }

    public function index()
    {
        $this->repository->setModel(new CraigslistListModel());

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
            $paginate = $paginate == 'false' ? false : true;
        }

        if ($this->request->getGet('column')) {
            $column = $this->request->getGet('column');
        }

        if ($this->request->getGet('order')) {
            $order = $this->request->getGet('order') == "ASC" ? "ASC" : "DESC";
        }


        $data = $this->repository;

        if ($this->request->getGet('search')) {
            $search = $this->request->getGet('search');
            if ($search != '') {
                $data = $this->repository->search($search);
            }
        }

        $sort = '{"' . $column . '" : "' . $order . '"}';

        $data = $data->index(null, $header = null, null, null, $items, $sort, $paginate, 'craigslist_view_id', 'craigslist_listing');

        $this->response->setContentType('application/json')->setJSON($data)->send();
    }

    public function show($id)
    {
        $data = $this->request->getPost();
        $rpa_token = (new ConfigurationRepository())->findConfigurationColumnsByKey('rpa_token', 'value');

        if (!empty($data['token']) && $data['token'] === $rpa_token->value) {
            $response = $this->repository->show($id);
            if (isset($response)) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Post Id couldn't be found."])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function delete($id)
    {
        $data = $this->request->getPost();
        $rpa_token = (new ConfigurationRepository())->findConfigurationColumnsByKey('rpa_token', 'value');

        if (!empty($data['token']) && $data['token'] === $rpa_token->value) {
            $response = $this->repository->show($id);
            if (isset($response)) {
                $this->repository->delete($id);
                $this->response->setContentType('application/json')->setJSON(["success" => "Post ID has been deleted."])->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Post ID couldn't be found."])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function createOrUpdate()
    {

        $data = $this->request->getPost();
        $rpa_token = (new ConfigurationRepository())->findConfigurationColumnsByKey('rpa_token', 'value');

        if (!empty($data['token']) && $data['token'] === $rpa_token->value) {

            unset($data['token']);
            $response = $this->repository->createOrUpdate($data);

            if (isset($response['success'])) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function filterDataAndPurgeDelete()
    {
        try {

            $data = $this->request->getPost();

            $column = isset($data['column']) ? $data['column'] : '';
            $value = isset($data['value']) ? $data['value'] : '';

            $this->repository->filterDataAndPurgeDelete($column, $value);
            $this->response->setContentType('application/json')->setJSON(['success' => 'Craigslist Logs have been deleted successfully'])->send();
        } catch (\Exception $ex) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => $ex->getMessage()])->send();
        }
    }

    public function generateCraigslistFeed($location)
    {

        $request = $this->request->getPost();

        $rpa_token = (new ConfigurationRepository())->findConfigurationColumnsByKey('rpa_token', 'value');

        $inventory_configurations = (new ConfigurationRepository())->getModuleConfigurationAsArray('inventory');

        $formatted_response = [];

        $craigslist_configuration_mapper_keys = ['craigslist_email_privacy_option', 'craigslist_show_phone_ok', 'craigslist_contact_phone_ok', 'craigslist_contact_text_ok', 'craigslist_contact_phone', 'craigslist_contact_phone_extension', 'craigslist_contact_name', 'craigslist_show_address_ok', 'craigslist_address_street', 'craigslist_address_cross_street', 'craigslist_address_city'];

        foreach ($inventory_configurations as $inventory_configuration) {
            if (in_array($inventory_configuration['key'], $craigslist_configuration_mapper_keys)) {
                $formatted_response[$inventory_configuration['key']] = $inventory_configuration['value'];
            }
        }

        if (!empty($request['token']) && $request['token'] === $rpa_token->value) {

            $craigslistMapper = new Craigslist();

            $inventoryRepository = new InventoryRepository();
            $inventoryRepository->setModel($this->repository->dynamicLocationBasedModel($location));

            $items = 100;
            $paginate = false;

            $column = 'created_at';
            $order = 'DESC';

            $view = '[{"header":"Year","column":"year","component":"text","visibility":"1"},{"header":"Make","column":"make","component":"text","visibility":"1"},{"header":"Model","column":"model","component":"text","visibility":"1"},{"header":"Trim","column":"trim","component":"text","visibility":"1"},{"header":"Odometer","column":"odometer","component":"text","visibility":"1"},{"header":"Exterior Color","column":"exterior_color","component":"text","visibility":"1"},{"header":"Interior Color","column":"interior_color","component":"text","visibility":"1"},{"header":"Transmission","column":"transmission","component":"text","visibility":"1"},{"header":"Cylinders","column":"engine","component":"text","visibility":"1"},{"header":"Drivetrain","column":"drivetrain","component":"text","visibility":"1"},{"header":"Selling Price","column":"selling_price","component":"text","visibility":"1"},{"header":"Vehicle Description","column":"vehicle_description","component":"text","visibility":"1"},{"header":"Vin","column":"vin","component":"text","visibility":"1"},{"header":"Stock No","column":"stock_no","component":"text","visibility":"1"},{"header":"Body Style","column":"body_type","component":"text","visibility":"1"},{"header":"Fuel Type","column":"fuel_type","component":"text","visibility":"1"},{"header":"Modification Hash","column":"modification_hash","component":"text","visibility":"1"},{"header":"Image URLs","column":"image_url","component":"text","visibility":"1"},{"header":"Posting Title","column":"title","component":"text","visibility":"1"},{"header":"Brand Type","column":"disclosure_brand_type","component":"text","visibility":"1"},{"header":"Post Id","column":"post_id","component":"text","visibility":"1"},{"header":"Post Status","column":"craigslist_status","component":"text","visibility":"1"},{"header":"Post URL","column":"craigslist_url","component":"text","visibility":"1"},{"header":"Published Date","column":"craigslist_created_at","component":"date","visibility":"1"},{"header":"Modified Date","column":"craigslist_updated_at","component":"date","visibility":"1"}]';

            $filter_params = [];

            $query = $inventoryRepository->prepareDataForFilter($filter_params);

            $data = $inventoryRepository;
            $sort = '{"' . $column . '" : "' . $order . '"}';

            $response = null;

            $response = $data->index(null, $header = null, null, $query, $items, $sort, $paginate, 'inventory_view_id', 'inventory_listing');

            $response['view'] = json_decode($view);

            $component = [];

            $headers = [];
            $titles = [];

            foreach ($response['view'] as $each_header) {
                if ($each_header->visibility == '1') {
                    array_push($headers, $each_header->column);
                    array_push($titles, $each_header->header);
                    $component[$each_header->column] = $each_header->component;
                }
            }


            $data = [];


            foreach ($response['inventory_listing'] as $key => $value) {
                array_push($data, []);
                foreach ($headers as $required_data_key) {
                    $cell = null;
                    $cell_value = null;

                    if (isset($response['inventory_listing'][$key][$required_data_key])) {
                        $cell_value = $response['inventory_listing'][$key][$required_data_key];
                    }

                    switch ($required_data_key) {
                        case 'odometer':
                            $cell = is_numeric($cell_value) ? $cell_value : 0;
                            break;

                        case 'transmission':
                            $cell = $craigslistMapper->exactMatch("transmissions", $cell_value, "other");
                            break;

                        case 'engine':
                            $engine = preg_replace("/[^0-9]/", "", $cell_value);
                            $cell = $craigslistMapper->exactMatch("cylinders", $engine, "other");
                            break;

                        case "drivetrain":
                            $cell = $craigslistMapper->exactMatch("drivetrains", $cell_value, "-");
                            break;

                        case "title":
                            $inventory = $inventoryRepository->replacePlaceholders($value);
                            $cell = Utility::stripHTMLTags($inventory['title']);
                            break;

                        case "vehicle_description":
                            $inventory = $inventoryRepository->replacePlaceholders($value);
                            $cell = $inventory['vehicle_description'];
                            break;

                        case "image_count":
                            $cell = (intval($cell_value) > 10) ? 10 : $cell_value;
                            break;

                        case "image_url":
                            $images_with_overlay = $response['inventory_listing'][$key]['image_with_overlay_urls'];
                            $images = $response['inventory_listing'][$key]['image_urls'];

                            $images_with_overlay = empty($images_with_overlay) ? [] : explode(';', $images_with_overlay);
                            $images = empty($images) ? [] : explode(';', $images);

                            $image_array = [];

                            foreach ($images as $image_key => $image_value) {
                                $image = empty($images_with_overlay[$image_key]) ? $images[$image_key] : $images_with_overlay[$image_key];
                                $image = Utility::getImageUrl($image);

                                array_push($image_array, $image);
                            }

                            $cell = implode(";", $image_array);
                            break;

                        case "body_type":
                            $cell = $craigslistMapper->exactMatch("body_types", $cell_value, "other");
                            break;

                        case "fuel_type":
                            $cell = $craigslistMapper->exactMatch("fuel_types", $cell_value, "other");
                            break;

                        case "exterior_color":
                            $cell = $craigslistMapper->exactMatch("exterior_colors", $cell_value, "custom");
                            break;

                        case "disclosure_brand_type":
                            $cell = $craigslistMapper->exactMatch("disclosure_brand_types", $cell_value, "clean");
                            break;

                        default:
                            $cell = $cell_value;
                            break;
                    }

                    $data[$key][$required_data_key] = $cell;
                }
            }

            if (empty($data)) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'No Inventories found.'])->send();
            } else {

                $formatted_response['inventory'] = $data;
                $this->response->setContentType('application/json')->setJSON($formatted_response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }
}
