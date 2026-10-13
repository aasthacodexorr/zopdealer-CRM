<?php

namespace Tenant\app\Controllers;

use App\Libraries\Schedule\ScheduleConstant;
use Tenant\app\Libraries\PdfFiller\PDFFillerConstant;
use Tenant\app\Libraries\Report\ReportFactory;
use Tenant\app\Libraries\PdfFiller\PDFFillerFactory;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\PaymentRepository;

class PaymentController extends \App\Controllers\BaseController
{


    private PaymentRepository   $repository;

    public function __construct()
    {
        $this->repository = new PaymentRepository();
    }

    public function index()
    {
        if (service('authPolicy')->hasElevatedRoles()) {

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


            $data = $this->repository;

            if ($this->request->getGet('search')) {
                $search = $this->request->getGet('search');
                if ($search != '') {
                    $data = $this->repository->exactSearch($search);
                }
            }

            $sort = '{"' . $column . '" : "' . $order  . '"}';

            $data = $data->index(null, $header = null, null, null, $items, $sort, $paginate, 'payment_view_id', 'payment_listing');

            $this->response->setContentType('application/json')->setJSON($data)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function create()
    {
        $validated = $this->validate([
            'name'  => [
                "label" => 'Name',
                "rules" => 'required'
            ],
            'email'  => [
                "label" => 'Email',
                "rules" => 'permit_empty|valid_email'
            ],
            'phone'  => [
                "label" => 'Phone Number',
                "rules" => 'permit_empty|is_natural|exact_length[10]'
            ],
            'amount'  => [
                "label" => 'Amount',
                "rules" => 'required|decimal'
            ],
            'status'  => [
                "label" => 'Status',
                "rules" => 'required|alpha_numeric_punct'
            ],
            'type'  => [
                "label" => 'Type',
                "rules" => 'required|alpha_numeric_punct'
            ],
            'mode'  => [
                "label" => 'Mode',
                "rules" => 'required|alpha_numeric_punct'
            ]
        ]);

        if ($validated) {
            $data = $this->request->getPost();
            $data['sub_status'] = 'succeeded';

            $response = $this->repository->create($data);

            if (isset($response['success'])) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    public function generatePDF($id)
    {
        $payment = $this->repository->show($id);

        if ($payment == null || !isset($payment['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Payment details couldn't be found."
            ])->send();
            exit;
        }

        try {
            $response = $payment;
            $configuration = new ConfigurationRepository();
            $payment_receipt_template = ($configuration->findConfigurationColumnsByKey('payment_receipt_template', ['value']))->value;
            $pdf = PDFFillerFactory::getInstance($payment_receipt_template, PDFFillerConstant::PAYMENT_RECEIPT);
            $this->response->setHeader('Content-Type', 'application/pdf');
            $pdf->dataMap($response)->output();
        } catch (\Error $error) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "Couldn't generate the PDF."])->send();
            exit;
        }
    }

    public function getModulePayment(string $module, string $module_id)
    {
        $response = $this->repository->filterPaymentByModuleAndId($module, $module_id);
        $this->response->setContentType('application/json')->setJSON($response)->send();
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
                    $data = $this->repository->exactSearch($search);
                }
            }

            $sort = '{"' . $column . '" : "' . $order  . '"}';

            $data = $data->index(null, $header = null, null, $query, $items, $sort, $paginate, 'payment_view_id', 'payment_listing');

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


            $reportFactory = (new ReportFactory())->getInstance(ScheduleConstant::PAYMENT_REPORT);
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

    public function delete($id)
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            $payment = $this->repository->show($id);

            if ($payment == null || !isset($payment['id'])) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                    "errors" => "Payment details couldn't be found."
                ])->send();
                return;
            }

            if (!empty($payment['event_id'])) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                    "errors" => "Payment cannot be deleted as it contains an event ID."
                ])->send();
                return;
            }

            $this->repository->delete($id);
            $this->response->setContentType('application/json')->setJSON(["success" => "Payment has been deleted."])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }
}
