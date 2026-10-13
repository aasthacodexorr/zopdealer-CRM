<?php

namespace Tenant\app\Controllers;

use Tenant\app\Repositories\WorkOrderRepository;
use App\Policies\AuthPolicy;
use Carbon\Carbon;
use Knp\Snappy\Pdf;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\CustomerRepository;
use Tenant\app\Repositories\InventoryRepository;
use Tenant\app\Repositories\LocationRepository;
use Tenant\app\Repositories\TenantUserMetaDataRepository;
use Tenant\app\Repositories\WorkJobCommonDependentRepository;
use Tenant\app\Repositories\WorkJobRepository;
use Tenant\app\Repositories\WorkOrderCommonDependentRepository;

class WorkOrderController extends \App\Controllers\BaseController
{
    /**
     * repository This repository corresponds to the WorkOrderRepository which can be used by any method here.
     *
     * @var WorkOrderRepository
     */
    private $repository;

    /**
     * secondary_repository
     *
     * @var WorkOrderCommonDependentRepository
     */
    private $secondary_repository;

    /**
     * work_job_repository
     *
     * @var WorkJobRepository
     */
    private $work_job_repository;

    /**
     * work_job_common_repository
     *
     * @var WorkJobCommonDependentRepository
     */
    private $work_job_common_repository;

    public function __construct()
    {
        $this->repository = new WorkOrderRepository();
        $this->secondary_repository = new WorkOrderCommonDependentRepository();
        $this->work_job_repository = new WorkJobRepository();
        $this->work_job_common_repository = new WorkJobCommonDependentRepository();
    }

    public function index()
    {

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
                $data = $this->repository->exactSearch($search);
            }
        }

        $sort = '{"' . $column . '" : "' . $order  . '"}';

        $data = $data->index(null, $header = null, null, $query, $items, $sort, $paginate, 'work_order_view_id', 'work_order_listing');

        $this->response->setContentType('application/json')->setJSON($data)->send();
    }

    public function show($id)
    {
        $response = $this->repository->show($id);

        if (isset($response['id'])) {
            $response['customer'] = $this->secondary_repository
                ->switchWorkOrderCommonDependentState('customer')->findByWorkOrderId($response['id']);
            $response['inventory'] = $this->secondary_repository
                ->switchWorkOrderCommonDependentState('inventory')->findByWorkOrderId($response['id']);
        }

        if (isset($response)) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Work Order couldn't be found."])->send();
        }
    }

    public function create()
    {
        $data = $this->request->getPost();
        $response = $this->repository->create($data);

        if (isset($response['success'])) {

            if (isset($data['customer']) && is_numeric($data['customer'])) {

                $this->secondary_repository
                    ->switchWorkOrderCommonDependentState('customer')
                    ->create(['work_order_id' => $response['work_order_id'], 'customer_id' => $data['customer']]);
            }

            if (isset($data['inventory']) && !empty($data['inventory'])) {

                $this->secondary_repository
                    ->switchWorkOrderCommonDependentState('inventory')
                    ->create(['work_order_id' => $response['work_order_id'], 'stock_no' => $data['inventory']]);
            }

            $this->repository->updateSearchIndex([$response['work_order_id']]);

            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function update($id)
    {
        $work_order = $this->repository->show($id);

        if ($work_order == null || !isset($work_order['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Work Order couldn't be found."
            ])->send();
        } else {
            $data = $this->request->getPost();

            $response = $this->repository->update($id, $data);

            if (isset($response['success'])) {
                $this->secondary_repository
                    ->switchWorkOrderCommonDependentState('customer')->deleteByWorkOrderId($id);

                $this->secondary_repository
                    ->switchWorkOrderCommonDependentState('inventory')->deleteByWorkOrderId($id);

                if (isset($data['customer']) && is_numeric($data['customer'])) {

                    $this->secondary_repository
                        ->switchWorkOrderCommonDependentState('customer')
                        ->create(['work_order_id' => $id, 'customer_id' => $data['customer']]);
                }

                if (isset($data['inventory']) && !empty($data['inventory'])) {

                    $this->secondary_repository
                        ->switchWorkOrderCommonDependentState('inventory')
                        ->create(['work_order_id' => $id, 'stock_no' => $data['inventory']]);
                }

                $this->repository->updateSearchIndex([$id]);

                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        }
    }

    public function multipleDeletes()
    {
        $data = $this->request->getPost();
        if (!empty($data['work_order_ids'])) {

            $data['work_order_ids'] = explode(',', $data['work_order_ids']);

            if (count($data['work_order_ids']) > 0) {
                $this->repository->delete($data['work_order_ids']);
                $this->response->setContentType('application/json')->setJSON(["success" => "Selected data has been deleted."])->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["error" => "There were some errors in deleting data."])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['error' => "Please select work orders to delete."])->send();
        }
    }

    public function updates()
    {
        $data = $this->request->getPost();
        $columns = $data;
        if (array_key_exists("work_order_ids", $columns)) {
            unset($columns['work_order_ids']);
        }

        $columns = array_filter($data, function ($column) {
            return (trim($column)  != '');
        });

        $work_order_ids = $data['work_order_ids'];
        $data['work_order_ids'] = explode(',', $data['work_order_ids']);

        if (count($data['work_order_ids']) > 0 && count($columns) > 0) {

            $this->repository->updates($data['work_order_ids'], $columns);

            (new WorkOrderRepository())->updateSearchIndex($data['work_order_ids']);

            $this->response->setContentType('application/json')->setJSON(["success" => "Columns Updated Successfully."])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["error" => "No Columns Or Rows Were Selected."])->send();
        }
    }

    public function generatePDF($type, $id)
    {

        $work_order = $this->repository->show($id);

        if ($work_order == null || !isset($work_order['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Work Order couldn't be found."
            ])->send();
        } else {

            if (!empty($work_order['check_in_date_time'])) {
                $work_order['check_in_date_time'] = Carbon::parse($work_order['check_in_date_time'])->isoFormat('M/D/YY hh:mm a');
            }

            if (!empty($work_order['assigned_advisor']) && is_numeric($work_order['assigned_advisor'])) {
                $users = (new TenantUserMetaDataRepository())->getUserMetaDataById($work_order['assigned_advisor']);
                if (is_array($users)) {
                    foreach ($users as $user) {
                        $work_order['assigned_advisor'] = $user['name'];
                    }
                }
            }

            $blogo = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_logo', 'value');
            $logo = ($blogo->value == "") ? "" : ROOT_BUSINESS_LOGO . $blogo->value;

            $inventory = $this->secondary_repository
                ->switchWorkOrderCommonDependentState('inventory')->findByWorkOrderId($id);

            $inventory = !empty($inventory[0]['stock_no']) ? (new InventoryRepository())->findSingleInventoryByStockId($inventory[0]['stock_no']) : null;

            $location_name = !empty($inventory) ? $inventory['location'] : '';

            $location = (new LocationRepository())->getLocationByName($location_name);

            $customer = $this->secondary_repository
                ->switchWorkOrderCommonDependentState('customer')->findByWorkOrderId($id);

            $customer = !empty($customer[0]['customer_id']) ? (new CustomerRepository())->show($customer[0]['customer_id']) : null;

            $work_jobs = $this->work_job_repository->filterData("work_order_id", $id)->index($paginate = false);

            if (!empty($work_jobs['data_list']) && is_array($work_jobs['data_list'])) {
                foreach ($work_jobs['data_list'] as $key => $work_job) {
                    $work_jobs['data_list'][$key]['products'] =
                        $this->work_job_common_repository->switchWorkJobCommonDependentState('products')->findByWorkJobId($work_job['id']);
                }
            }

            if (is_file(APPPATH . 'Views/templates/' . $type . '.php')) {
                $snappy = new Pdf();
                $path = \h4cc\WKHTMLToPDF\WKHTMLToPDF::PATH;
                $snappy->setBinary($path);
                $template = view('Views/templates/' . $type, compact(['logo', 'location', 'inventory', 'customer', 'work_order', 'work_jobs']));
                $this->response->setHeader('Content-Type', 'application/pdf');
                echo $snappy->getOutputFromHtml($template);
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => `Template couldn't be found.`])->send();
            }
        }
    }
}
