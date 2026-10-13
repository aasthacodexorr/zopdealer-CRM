<?php

namespace Tenant\app\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Schedule\ScheduleConstant;
use Tenant\app\Libraries\Report\ReportFactory;
use Tenant\app\Repositories\CustomerReportViewRepository;

class CustomerReportViewController extends BaseController
{
    /**
     * repository This repository corresponds to the CustomerReportViewRepository for reporting filters.
     *
     * @var CustomerReportViewRepository
     */
    private $repository;


    public function __construct()
    {
        $this->repository = new CustomerReportViewRepository();
    }

    public function index()
    {
        $response = $this->repository->index();
        $this->response->setContentType('application/json')->setJSON(["customer_views" => $response])->send();
    }

    public function create(): void
    {
        $data = $this->request->getPost();

        $response = $this->repository->create($data);
        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function update($id)
    {
        $data = $this->request->getPost();

        $response = $this->repository->update($data, $id);
        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function result($id)
    {
        $response = null;
        $response = $this->repository->result($id);
        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    public function generateReport($report_type, $id)
    {
        $reportFactory = (new ReportFactory())->getInstance(ScheduleConstant::CUSTOMER_REPORT);
        $reportFactory->setDataFromView($id)->output('php://output', strtoupper($report_type));

        $this->response->setHeader('Content-Type', $reportFactory->getContentTypeHeaderBasedOnReportType(strtoupper($report_type)));
        die;
    }
}
