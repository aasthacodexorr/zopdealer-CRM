<?php

namespace Tenant\app\Controllers;

use App\Controllers\BaseController;
use App\Helpers\Utility;
use App\Libraries\Schedule\ScheduleConstant;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Tenant\app\Libraries\Mapper\Facebook;
use Tenant\app\Libraries\Report\ReportFactory;
use Tenant\app\Models\FacebookMarketplaceListModel;
use Tenant\app\Models\FacebookMarketplacePublishedListModel;
use Tenant\app\Models\FacebookMarketplaceUnmarkedSoldListModel;
use Tenant\app\Models\InventoryFBMPModel;
use Tenant\app\Repositories\InventoryReportRepository;
use Tenant\app\Repositories\InventoryRepository;
use Tenant\app\Repositories\ConfigurationRepository;

class InventoryReportController extends BaseController
{
    /**
     * repository This repository corresponds to the InventoryReportRepository for reporting filters.
     *
     * @var InventoryReportRepository
     */
    private $repository;

    protected $configuration_repository;


    public function __construct()
    {
        $this->repository = new InventoryReportRepository();
        $this->configuration_repository = new ConfigurationRepository();
    }



    public function reportIndex()
    {

        if (service('authPolicy')->hasElevatedRoles()) {

            $items = 100;
            $paginate = true;

            $column = 'created_at';
            $order = 'DESC';


            $query = null;

            if (count($_GET) > 0) {
                $query = $this->repository->prepareDataForFilter($_GET);
            }


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


            $data = $this->repository;

            if ($this->request->getGet('search')) {
                $search = $this->request->getGet('search');
                if ($search != '') {
                    $data = $this->repository->search($search);
                }
            }

            $sort = '{"' . $column . '" : "' . $order  . '"}';

            $data = $data->index(null, $header = null, null, $query, $items, $sort, $paginate, 'inventory_view_id', 'inventory_listing');

            $this->response->setContentType('application/json')->setJSON($data)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function generateReport($report_type)
    {
        if (service('authPolicy')->hasElevatedRoles() && $this->request->getPost('view')) {

            $items = 100;
            $paginate = false;

            $column = 'created_at';
            $order = 'DESC';

            $view = null;

            $search = NULL;

            if ($this->request->getGet('items')) {
                $items = $this->request->getGet('items');
            }

            if ($this->request->getGet('page_no')) {
                $_GET['page'] = $this->request->getGet('page_no');
            }

            if ($this->request->getGet('column')) {
                $column = $this->request->getGet('column');
            }

            if ($this->request->getGet('order')) {
                $order = $this->request->getGet('order') == "ASC" ? "ASC" : "DESC";
            }

            if ($this->request->getPost('view')) {
                $view = $this->request->getPost('view');
            }

            if ($this->request->getGet('search')) {
                $search = $this->request->getGet('search');
            }


            $reportFactory = (new ReportFactory())->getInstance(ScheduleConstant::INVENTORY_REPORT);
            $reportFactory->setDataFromEntityRepository($view, $_GET, $search, $column, $order, $items, $paginate)
                ->output('php://output', strtoupper($report_type));

            $this->response->setHeader('Content-Type', $reportFactory->getContentTypeHeaderBasedOnReportType(strtoupper($report_type)));
            die;
        } elseif (!$this->request->getPost('view')) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'View Parameter Missing.'])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function generateFBMPFeed()
    {

        $request = $this->request->getPost();
        $rpa_token = (new ConfigurationRepository())->findConfigurationColumnsByKey('rpa_token', 'value');

        $enable_fbmp_group_posting = (new ConfigurationRepository())->findConfigurationColumnsByKey('enable_fbmp_group_posting', 'value');

        $fbmp_list_of_groups = (new ConfigurationRepository())->findConfigurationColumnsByKey('fbmp_list_of_groups', 'value');

        $fbmp_list_of_groups = json_decode($fbmp_list_of_groups->value, true);

        $fbmp_merge_config = (new ConfigurationRepository())->findConfigurationColumnsByKeyAsArray('fbmp_merge_model_and_trim', ['value']);
        $is_fbmp_merge_enabled = isset($fbmp_merge_config['value']) ? ($fbmp_merge_config['value'] == '1') : false;

        if (!empty($request['token']) && $request['token'] === $rpa_token->value) {

            $fbmp_location = (new ConfigurationRepository())->findConfigurationColumnsByKey('fbmp_location', 'value');

            $facebookMapper = new Facebook();

            $inventoryRepository = new InventoryRepository();
            $inventoryRepository->setModel(new FacebookMarketplaceListModel());

            if (!empty($request['onlySold'])) {
                $inventoryRepository->setModel(new FacebookMarketplaceUnmarkedSoldListModel());
            }

            if (!empty($request['onlyPublished'])) {
                $inventoryRepository->setModel(new FacebookMarketplacePublishedListModel());
            }

            $items = 100;
            $paginate = false;

            $column = 'created_at';
            $order = 'DESC';

            $view = '[{"header":"Vehicle Type","column":"vehicle_type","component":"text","visibility":"1"},{"header":"Year","column":"year","component":"text","visibility":"1"},{"header":"Make","column":"make","component":"text","visibility":"1"},{"header":"Model","column":"model","component":"text","visibility":"1"},{"header":"Trim","column":"trim","component":"text","visibility":"1"},{"header":"Odometer","column":"odometer","component":"text","visibility":"1"},{"header":"Exterior Color","column":"exterior_color","component":"text","visibility":"1"},{"header":"Interior Color","column":"interior_color","component":"text","visibility":"1"},{"header":"Automatic Transmission","column":"transmission","component":"text","visibility":"1"},{"header":"Selling Price","column":"selling_price","component":"text","visibility":"1"},{"header":"Location","column":"location","component":"text","visibility":"1"},{"header":"Vehicle Description","column":"vehicle_description","component":"text","visibility":"1"},{"header":"Vin","column":"vin","component":"text","visibility":"1"},{"header":"Stock No","column":"stock_no","component":"text","visibility":"1"},{"header":"Body Style","column":"body_type","component":"text","visibility":"1"},{"header":"Fuel Type","column":"fuel_type","component":"text","visibility":"1"},{"header":"Modification Hash","column":"modification_hash","component":"text","visibility":"1"},{"header":"Image URLs","column":"image_url","component":"text","visibility":"1"},{"header":"Post Id","column":"post_id","component":"text","visibility":"1"},{"header":"Post Status","column":"facebook_status","component":"text","visibility":"1"},{"header":"Published Date","column":"facebook_created_at","component":"date","visibility":"1"},{"header":"Modified Date","column":"facebook_updated_at","component":"date","visibility":"1"}]';

            $filter_params = [];

            $query = $inventoryRepository->prepareDataForFilter($filter_params);

            $data = $inventoryRepository;
            $sort = '{"' . $column . '" : "' . $order  . '"}';

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

                        case 'post_id':
                        case 'facebook_status':
                        case 'facebook_created_at':
                        case 'facebook_updated_at':
                            $cell =  $cell_value;
                            break;
                        case 'vehicle_type':
                            $category = $response['inventory_listing'][$key]['facebook_category'];
                            $cell = empty($category) ? "Car/Truck" : $category;
                            break;

                        case 'odometer':
                            $cell = is_numeric($cell_value) ?  $cell_value : 0;
                            break;

                        case 'transmission':
                            $cell_value = $facebookMapper->exactMatch("transmission", $cell_value, "Manual");
                            $cell = stristr($cell_value, "Automatic") ? "yes" : "no";
                            break;

                        case "vehicle_description":
                            $cell = $this->fbmpVehicleDescription($value, $inventoryRepository);
                            break;

                        case "image_count":
                            $cell = (intval($cell_value) > 10) ? 10 : $cell_value;
                            break;

                        case "image_xml":
                            $cell = "";
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
                            $cell = $facebookMapper->exactMatch("body_types", $cell_value, "Other");
                            break;

                        case "fuel_type":
                            $cell = $facebookMapper->exactMatch("fuel_types", $cell_value, "Other");
                            break;

                        case "interior_color":
                            $cell = $facebookMapper->exactMatch("interior_colors", $cell_value, "Other");
                            break;

                        case "exterior_color":
                            $cell = $facebookMapper->exactMatch("exterior_colors", $cell_value, "Other");
                            break;


                        case "make":
                            $cell = $facebookMapper->exactMatch("makes", $cell_value, $cell_value);
                            break;
                        case "location":
                            $cell = $fbmp_location->value;
                            break;

                        case "model":
                            $trim = isset($value['trim']) ? trim($value['trim']) : '';

                            if ($is_fbmp_merge_enabled && !empty($trim)) {
                                $cell = trim($cell_value . ' ' . $trim);
                            } else {
                                $cell = $cell_value;
                            }
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
                $this->response->setContentType('application/json')->setJSON(['enable_group_posting' => $enable_fbmp_group_posting->value, 'fbmp_list_of_groups' => $fbmp_list_of_groups, 'inventory' => $data])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    protected function fbmpVehicleDescription($inventory, $inventoryRepository)
    {
        try {
            $enable_fbmp_vehicle_description_config = $this->configuration_repository->findConfigurationColumnsByKeyAsArray('enable_fbmp_vehicle_description_template', ['value']);
            $is_fbmp_vehicle_description_enabled = !empty($enable_fbmp_vehicle_description_config['value']);

            if ($is_fbmp_vehicle_description_enabled) {
                $fbmp_vehicle_description_template_config = $this->configuration_repository->findConfigurationColumnsByKeyAsArray('fbmp_vehicle_description_template', ['value']);
                $inventory['vehicle_description'] = $fbmp_vehicle_description_template_config['value'];
            }

            $inventory_with_updated_placeholders = $inventoryRepository->replacePlaceholders($inventory);
            return \Soundasleep\Html2Text::convert($inventory_with_updated_placeholders['vehicle_description']);
        } catch (\Exception $ex) {
            $inventory_with_updated_placeholders = $inventoryRepository->replacePlaceholders($inventory);
            return strip_tags(html_entity_decode($inventory_with_updated_placeholders['vehicle_description']));
        } catch (\Error $er) {
            $inventory_with_updated_placeholders = $inventoryRepository->replacePlaceholders($inventory);
            return strip_tags(html_entity_decode($inventory_with_updated_placeholders['vehicle_description']));
        }
    }
}
