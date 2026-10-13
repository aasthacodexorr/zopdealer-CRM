<?php

namespace Tenant\app\Controllers;

use App\Libraries\CloudStorage\CloudStorageFactory;
use App\Libraries\Schedule\ScheduleConstant;
use Tenant\app\Repositories\CustomerRepository;
use App\Policies\AuthPolicy;
use Tenant\app\Libraries\PdfFiller\PDFFillerFactory;
use Tenant\app\Libraries\Report\ReportFactory;
use Tenant\app\Models\DealCustomerPivotModel;
use Tenant\app\Models\DealInventoryPivotModel;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\CustomerCreditApplicationRepository;
use Tenant\app\Repositories\CustomerInsuranceRepository;
use Tenant\app\Repositories\DealRepository;
use Tenant\app\Repositories\InventoryRepository;
use Tenant\app\Repositories\LocationRepository;
use Tenant\app\Libraries\PdfFiller\PDFFillerConstant;

class CustomerController extends \App\Controllers\BaseController
{
    /**
     * repository This repository corresponds to the CustomerRepository which can be used by any method here.
     *
     * @var CustomerRepository
     */
    private $repository;

    /**
     * secondary_repository
     *
     * @var DealRepository
     */
    private $secondary_repository;

    private $configuration_repository;

    public function __construct()
    {
        $this->repository = new CustomerRepository();
        $this->secondary_repository = new DealRepository();
        $this->configuration_repository = new ConfigurationRepository();
    }

    public function index()
    {

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

        if (!$this->isCurrentUserAuthorizedToViewAllCustomers()) {
            $data->filterData("assignee", service('authPolicy')->id);
        }

        $sort = '{"' . $column . '" : "' . $order  . '"}';

        $data = $data->index(null, $header = null, null, null, $items, $sort, $paginate, 'customer_view_id', 'customer_listing');

        $this->response->setContentType('application/json')->setJSON($data)->send();
    }

    public function create()
    {
        $data = $this->request->getPost();
        $response = $this->repository->create($data);

        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function show($id)
    {
        $response = $this->repository->show($id);
        $this->existenceOrRightsCheckForCustomer($response);

        if (isset($response)) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Customer couldn't be found."])->send();
        }
    }

    public function update($id)
    {
        $customer = $this->repository->show($id);
        $this->existenceOrRightsCheckForCustomer($customer);

        $data = $this->request->getPost();

        $response = $this->repository->update($id, $data);
        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function multipleDeletes()
    {
        $data = $this->request->getPost();
        $data['customer_ids'] = explode(',', $data['customer_ids']);

        if (count($data['customer_ids']) > 0) {
            $this->repository->delete($data['customer_ids']);
            $this->response->setContentType('application/json')->setJSON(["success" => "Selected data has been deleted."])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["error" => "There were some errors in deleting data."])->send();
        }
    }

    /**
     * noteCreate This creates a new note corresponding to the customer along with an attachment.
     *
     * @param  int $id This is the Customer Id for which the note is supposed to be created.
     * @return void
     */
    public function noteCreate($id)
    {

        $customer = $this->repository->show($id);

        $this->existenceOrRightsCheckForCustomer($customer);

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
     * showNote This method accepts two paramters customer id as well as id of the note.
     * Customer Id is simply used for routing purpose.
     *
     * @param  mixed $customer_id
     * @param  mixed $id
     * @return void
     */
    public function showNote($customer_id, $id)
    {
        $customer = $this->repository->show($customer_id);
        $this->existenceOrRightsCheckForCustomer($customer);

        $response = $this->repository->showNote($id);
        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    /**
     * allNotes This method shows the list of all the notes related to a particular customer.
     *
     * @param  mixed $customer_id Customer Id.
     * @return void
     */
    public function allNotes($customer_id)
    {
        $customer = $this->repository->show($customer_id);
        $this->existenceOrRightsCheckForCustomer($customer);

        $response = $this->repository->allNotes($customer_id);
        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    /**
     * updateNote This method allows the user to update the note, if he is note owner or tenant owner or superowner.
     *
     * @param  int $customer_id Customer Id.
     * @param  int $id Note Id to be updated.
     * @return void
     */
    public function updateNote($customer_id, $id)
    {

        $customer = $this->repository->show($customer_id);
        $this->existenceOrRightsCheckForCustomer($customer);

        $note = $this->repository->showNote($id);
        if (!empty($note)) {
            if (!(service('authPolicy')->hasOwnerRole()
                || service('authPolicy')->isSuperOwner()
                || service('authPolicy')->isResourceOwner($note['user_id']))) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'You do not have permission to update customer note.'])->send();
                exit;
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'Customer note could not be found.'])->send();
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
     * @param  int $customer_id Customer Id.
     * @param  int $id Note Id to be updated.
     * @return void
     */
    public function deleteNote($customer_id, $id)
    {
        $customer = $this->repository->show($customer_id);
        $this->existenceOrRightsCheckForCustomer($customer);

        $note = $this->repository->showNote($id);
        if (!empty($note)) {
            if (!(service('authPolicy')->hasOwnerRole()
                || service('authPolicy')->isSuperOwner()
                || service('authPolicy')->isResourceOwner($note['user_id']))) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'You do not have permission to delete customer note.'])->send();
                exit;
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'Customer note could not be found.'])->send();
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
     * getCustomerDeals
     *
     * @param  mixed $id
     * @return void
     */
    public function getCustomerDeals($id)
    {
        $customer = $this->repository->show($id);
        $this->existenceOrRightsCheckForCustomer($customer);

        $response = $this->repository
            ->setModel(new DealCustomerPivotModel())
            ->filterData('customer_id', $id)
            ->index(null, null, null, null, 20, null, false, 'view_id', 'deals');

        foreach ($response['deals'] as $key => $deal) {
            $response['deals'][$key] = $this->secondary_repository->show($deal['deal_id']);

            if (isset($response['deals'][$key])) {
                $inventories  = $this->repository
                    ->setModel(new DealInventoryPivotModel())
                    ->filterData('deal_id', $deal['deal_id'])
                    ->index(null, null, null, null, 20, null, false, 'view_id', 'inventories');

                if (isset($inventories['inventories'])) {
                    if (!empty($inventories['inventories'])) {

                        foreach ($inventories['inventories'] as $sub_key => $inventory) {
                            $inventory = (new InventoryRepository())->findSingleInventoryByStockId($inventory['stock_no']);
                            $inventories['inventories'][$sub_key]['details'] = $inventory;
                        }

                        if (!empty($response['deals'][$key])) {
                            $response['deals'][$key]['inventories'] = $inventories['inventories'];
                        }
                    }
                }
            }
        }
        $response['deals'] = array_values(array_filter($response['deals']));

        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    /**
     * generatePDF This generates the test drive form.
     *
     * @param  mixed $id
     * @return void
     */
    public function generatePDF($bill_type, $id)
    {
        $customer = $this->repository->show($id);
        $this->existenceOrRightsCheckForCustomer($customer);

        try {

            $response['customers'][0] = $customer;
            $response['customers'][0]['deal_customer_type'] = 'primary_customer';
            $response['customers'][0]['customer_insurance'] = (new CustomerInsuranceRepository())->findByCustomerId($response['customers'][0]['id']);

            if ($bill_type == 'credit-application') {
                $customer_type = (int) $response['customers'][0]["customer_type"];
                $response['customers'][0]['credit_application'] =
                    (new CustomerCreditApplicationRepository())->switchCustomerState($customer_type)->findByCustomerId($id);
            }

            $response['location'] = (new LocationRepository())->getLocationByName("");

            $configuration = new ConfigurationRepository();

            $pdf = null;

            if ($bill_type == 'test-drive') {
                $test_drive_template = ($configuration->findConfigurationColumnsByKey('test_drive_template', ['value']))->value;
                $pdf = PDFFillerFactory::getInstance($test_drive_template, PDFFillerConstant::TEST_DRIVE);
            } else if ($bill_type == 'credit-application') {
                $credit_application = ($configuration->findConfigurationColumnsByKey('individual_credit_application', ['value']))->value;
                $pdf = PDFFillerFactory::getInstance($credit_application, PDFFillerConstant::CREDIT_APPLICATION);
            }

            $this->response->setHeader('Content-Type', 'application/pdf');
            $pdf->dataMap($response)->output();
        } catch (\Error $error) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "Couldn't generate the Test Driving form."])->send();
            exit;
        }
    }

    public function reportIndex()
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

        $data = $data->index(null, $header = null, null, $query, $items, $sort, $paginate, 'customer_view_id', 'customer_listing');

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


            $reportFactory = (new ReportFactory())->getInstance(ScheduleConstant::CUSTOMER_REPORT);
            $reportFactory->setDataFromEntityRepository($view, $_GET, $search, $column, $order, $items, $paginate)
                ->output('php://output', strtoupper($report_type));

            $this->response->setHeader('Content-Type', $reportFactory->getContentTypeHeaderBasedOnReportType(strtoupper($report_type)));
            die;
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'View Parameter Missing.'])->send();
        }
    }

    /**
     * existenceOrRightsCheckForCustomer This function checks if the deal is present or created by 
     *
     * @return void
     */
    protected function existenceOrRightsCheckForCustomer($customer)
    {
        if ($customer == null || !isset($customer['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Customer couldn't be found."
            ])->send();
            exit;
        }

        if (!$this->isCurrentUserAuthorizedToViewCurrentCustomer($customer)) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "You do not have enough privelages to see the customer."])->send();
            exit;
        }
    }

    private function isCurrentUserAuthorizedToViewCurrentCustomer($customer)
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            return true;
        }

        $authorize_staff_to_view_all_customers =  $this->configuration_repository->findConfigurationColumnsByKey('authorize_staff_to_view_all_customers', 'value')->value;
        $authorize_staff_to_view_all_customers =  !empty($authorize_staff_to_view_all_customers);

        if ($authorize_staff_to_view_all_customers) {
            return true;
        }

        return $customer['assignee'] == service('authPolicy')->id;
    }

    private function isCurrentUserAuthorizedToViewAllCustomers()
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            return true;
        }

        $authorize_staff_to_view_all_customers =  $this->configuration_repository->findConfigurationColumnsByKey('authorize_staff_to_view_all_customers', 'value')->value;
        return !empty($authorize_staff_to_view_all_customers);
    }
}
