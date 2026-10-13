<?php

namespace Tenant\app\Controllers;

use App\Libraries\Schedule\ScheduleConstant;
use Tenant\app\Libraries\Report\ReportFactory;
use Tenant\app\Models\InventoryPurchaseReportModel;
use Tenant\app\Repositories\InventoryPurchaseRepository;
use Tenant\app\Repositories\InventoryRepository;
use Tenant\app\Repositories\AttachmentRepository;

class InventoryPurchaseController extends \App\Controllers\BaseController
{

    private InventoryPurchaseRepository $repository;
    private InventoryRepository $secondary_repository;
    private AttachmentRepository    $attachment_repository;

    public function __construct()
    {
        $this->repository = new InventoryPurchaseRepository();
        $this->secondary_repository = new InventoryRepository();
        $this->attachment_repository = new AttachmentRepository();
    }

    public function create($inventory_id)
    {

        $inventory = $this->secondary_repository->show($inventory_id);

        if ($inventory == null || !isset($inventory['inventory_id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Inventory couldn't be found."
            ])->send();
            exit;
        }

        $data = $this->request->getPost();
        $data["inventory_id"] = $inventory_id;
        $response = $this->repository->create($data);


        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function show($inventory_id, $id)
    {

        $response = $this->repository->show($id);
        if ($response != null) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "Inventory purchase couldn't be found for this inventory."])->send();
        }
    }

    public function update($inventory_id, $id)
    {

        $data = $this->request->getPost();

        $response = $this->repository->update($id, $data);
        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function delete($inventory_id, $id)
    {

        try {
            $this->repository->delete($id);
            $this->response->setContentType('application/json')->setJSON(["success" => "Inventory Purchase has been deleted."])->send();
        } catch (\Exception $exception) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "There were some errors in deleting inventory purchase."])->send();
        }
    }

    public function index($inventory_id)
    {

        $inventory = $this->secondary_repository->show($inventory_id);

        if ($inventory == null || !isset($inventory['inventory_id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Inventory couldn't be found."
            ])->send();
            exit;
        }

        $response = $this->repository->findByInventoryId($inventory_id);
        if ($response != null) {
            if (!empty($response["id"])) {
                $attachment_lists = [
                    'url' => 'PURCHASE',
                    'purchase_receipt_url' => 'PURCHASE_RECEIPT',
                    'ownership_title_url' => 'OWNERSHIP_TITLE',
                    'title_transfer_proof_url' => 'TITLE_TRANSFER_PROOF',
                    'lien_paid_proof_url' => 'LIEN_PAID_PROOF'
                ];

                foreach ($attachment_lists as $response_attribute => $attachment_attribute) {
                    $attachment = $this->attachment_repository->findByEntityDetails("INVENTORY", $attachment_attribute, $response["id"]);
                    $attachment_url = !empty($attachment['url']) ? $attachment['url'] : "";
                    $response[$response_attribute] = $attachment_url;
                }
            }
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "Inventory purchase couldn't be found for this inventory."])->send();
        }
    }

    public function reportIndex()
    {
        $items = 100;
        $paginate = true;

        $column = 'created_at';
        $order = 'DESC';

        $query = null;

        $this->repository->setModel(new InventoryPurchaseReportModel());

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

        $data = $data->index(null, $header = null, null, $query, $items, $sort, $paginate, 'inventory_purchase_view_id', 'inventory_purchase_listing');

        $this->response->setContentType('application/json')->setJSON($data)->send();
    }

    public function generateReport($report_type)
    {
        if ($this->request->getPost('view')) {

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


            $reportFactory = (new ReportFactory())->getInstance(ScheduleConstant::INVENTORY_PURCHASE_REPORT);
            $reportFactory->setDataFromEntityRepository($view, $_GET, $search, $column, $order, $items, $paginate)
                ->output('php://output', strtoupper($report_type));

            $this->response->setHeader('Content-Type', $reportFactory->getContentTypeHeaderBasedOnReportType(strtoupper($report_type)));
            die;
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'View Parameter Missing.'])->send();
        }
    }
}
