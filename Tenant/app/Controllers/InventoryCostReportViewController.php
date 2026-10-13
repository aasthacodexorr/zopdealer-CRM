<?php

namespace Tenant\app\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Schedule\ScheduleConstant;
use Tenant\app\Libraries\Report\ReportFactory;
use Tenant\app\Repositories\InventoryCostReportViewRepository;

class InventoryCostReportViewController extends BaseController
{
    /**
     * repository This repository corresponds to the InventoryCostReportViewRepository for reporting filters.
     *
     * @var InventoryCostReportViewRepository
     */
    private $repository;


    public function __construct()
    {
        $this->repository = new InventoryCostReportViewRepository();
    }

    public function index()
    {
        $response = $this->repository->index();
        $this->response->setContentType('application/json')->setJSON(["inventory_cost_views" => $response])->send();
    }

    public function create(): void
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            $data = $this->request->getPost();

            $response = $this->repository->create($data);
            if (isset($response['success'])) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function update($id)
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            $data = $this->request->getPost();

            $response = $this->repository->update($data, $id);
            if (isset($response['success'])) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function result($id)
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            $response = null;

            $response = $this->repository->result($id);

            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function generateReport($report_type, $id)
    {
        if (service('authPolicy')->hasElevatedRoles()) {

            $reportFactory = (new ReportFactory())->getInstance(ScheduleConstant::INVENTORY_COST_REPORT);
            $reportFactory->setDataFromView($id)->output('php://output', strtoupper($report_type));

            $this->response->setHeader('Content-Type', $reportFactory->getContentTypeHeaderBasedOnReportType(strtoupper($report_type)));
            die;
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }
}
