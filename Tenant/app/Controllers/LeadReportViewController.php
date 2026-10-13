<?php

namespace Tenant\app\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Schedule\ScheduleConstant;
use Tenant\app\Libraries\Report\ReportFactory;
use Tenant\app\Repositories\LeadReportViewRepository;

class LeadReportViewController extends BaseController
{
    /**
     * repository This repository corresponds to the LeadReportViewRepository for reporting filters.
     *
     * @var LeadReportViewRepository
     */
    private $repository;


    public function __construct()
    {
        $this->repository = new LeadReportViewRepository();
    }


    public function index()
    {
        $response = $this->repository->index();
        $this->response->setContentType('application/json')->setJSON(["lead_views" => $response])->send();
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

    public function show($id)
    {
        $response = $this->repository->show($id);
        if (isset($response)) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Payment Report View couldn't be found."])->send();
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

            $reportFactory = (new ReportFactory())->getInstance(ScheduleConstant::LEAD_REPORT);
            $reportFactory->setDataFromView($id)->output('php://output', strtoupper($report_type));

            $this->response->setHeader('Content-Type', $reportFactory->getContentTypeHeaderBasedOnReportType(strtoupper($report_type)));
            die;
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }
}
