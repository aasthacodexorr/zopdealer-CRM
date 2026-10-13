<?php

namespace Tenant\app\Controllers;

use Tenant\app\Libraries\Services\TestDriveService;
use Tenant\app\Repositories\TestDriveRepository;
use Tenant\app\Models\TestDriveEventModel;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\LeadResponseRepository;
use Tenant\app\Repositories\TestDriveCustomerRepository;
use Config\Services;

class TestDriveController extends \App\Controllers\BaseController
{
    protected $testDriveService;
    protected $repository;
    protected $configuration_repository;
    protected $eventModel;
    protected $leadResponseRepository;

    public function __construct()
    {
        $this->testDriveService = new TestDriveService();
        $this->eventModel = new TestDriveEventModel();
        $this->repository = new TestDriveRepository();
        $this->configuration_repository = new ConfigurationRepository();
        $this->leadResponseRepository = new LeadResponseRepository();
        $this->testDriveCustomerRepository = new TestDriveCustomerRepository();

    }

    /**
     * List all test drives with filtering and pagination
     */
    public function index()
    {
        $items = $this->request->getGet('items') ?? 100;
        //$page = $this->request->getGet('page_no') ?? 1;
        //  $offset = ($page - 1) * $items;
        $paginate = true;
        $column = 'created_at';
        $order = 'DESC';

        $query = null;
        if (count($_GET) > 0) {
            $query = $this->repository->prepareDataForFilter($_GET);
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
                $data = $this->repository->exactSearch($search);
            }
        }

        $sort = '{"' . $column . '" : "' . $order . '"}';
        $data = $data->index(null, $header = null, null, $query, $items, $sort, $paginate, 'test_drive_view_id', 'test_drive_listing');

        $this->response->setContentType('application/json')->setJSON($data)->send();
    }

    /**
     * Create a new test drive
     */
    public function create()
    {
        try {
            $data = $this->request->getPost();
            $userId = service('authPolicy')->id ?? null;

            // Checking available credits adn deducting
            $available_credits = (int)$this->repository->availableTestDriveCredits();

            if ($available_credits <= 0) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'Test Drive Credits reached. Please contact support to add more credits.'])->send();
                exit;
            }

            $response = $this->testDriveService->create($data, $userId);

            if ($response['success']) {
                if (empty($data['module_id'])) {
                    $lead_id = $this->createLead($response['test_drive_id']);
                    if (!empty($lead_id)) {
                        $this->repository->update($response['test_drive_id'], ['module_id' => $lead_id]);
                        $response['module_id'] = $lead_id;
                    }
                }

                // Updating credits
                $updated_available_credits = $available_credits > 0 ? --$available_credits : 0;
                $this->configuration_repository->setConfiguration(['testdrive_available_credit' => $updated_available_credits], false);

                // updating search index
                $this->repository->updateSearchIndex([$response['test_drive_id']]);
                $this->response->setContentType('application/json')->setJSON($response)->send();

            }
        } catch (\Throwable $e) {
            log_message('error', 'TestDriveController::create failed: ' . $e->getMessage());
            return $this->response->setContentType('application/json')->setStatusCode(500)->setJSON(['success' => false, 'errors' => 'Unable to create test drive at this time.', 'details' => $e->getMessage()])->send();
        }
    }

    private function createLead($id)
    {
        try {
            $testDriveData = $this->repository->show($id);
            $customerData = $this->testDriveCustomerRepository->show($testDriveData['test_drives_customers_id']);
            if (empty($customerData)) {
                throw new \RuntimeException('Customer data not found.');
            }
            $leadData = [
                'first_name' => $customerData['first_name'] ?? '',
                'last_name' => $customerData['last_name'] ?? '',
                'phone' => $customerData['phone'] ?? '',
                'email' => $customerData['email'] ?? '',
                'status' => 'New',
                'assignee' => null,
                'is_encrypted' => 0,
                'source' => 'Lead',
                //'other_data' => json_encode($testDriveData),
            ];
            $lead = $this->leadResponseRepository->create($leadData);
            if (empty($lead['lead_id'])) {
                throw new \RuntimeException('Lead could not be created.');
            }
            return $lead['lead_id'];
        } catch (\Throwable $e) {
            log_message('error', 'TestDriveController::createLead failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Show a single test drive
     */
    public function show($id)
    {
        $response = $this->repository->getTestDrivesWithAllDetails($id);
        if (!$response) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'Something wrong with data'])->send();
        }

        $this->response->setContentType('application/json')->setJSON(['success' => true, 'data' => $response])->send();
    }

    /**
     * Update a test drive
     */
    public function update($id)
    {
        // Check if test drive exists
        $testDrive = $this->repository->find($id);

        if (!$testDrive) {
            $this->response->setContentType('application/json')->setStatusCode(404)
                ->setJSON(['success' => false, 'errors' => ['test_drive' => 'Test drive not found']])->send();
            return;
        }

        $data = $this->request->getPost();
        $data['id'] = $id;
        $userId = service('authPolicy')->id ?? null;

        if ($this->repository->save($data)) {
            // Log event
            $this->eventModel->logEvent($id, 'updated', $data, $userId);
            $this->repository->updateSearchIndex([$id]);
            $response = [
                'success' => true,
                'message' => 'Test drive updated successfully'
            ];
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $response = [
                'success' => false,
                'errors' => $this->repository->errors()
            ];
            $this->response->setContentType('application/json')->setStatusCode(400)->setJSON($response)->send();
        }
    }

    /**
     * Delete a test drive
     */
    public function multipleDeletes()
    {
        $data = $this->request->getPost();
        if (service('authPolicy')->hasElevatedRoles() && !empty($data['test_drive_ids'])) {
            $data['test_drive_ids'] = explode(',', $data['test_drive_ids']);
            if (count($data['test_drive_ids']) > 0) {
                $this->repository->delete($data['test_drive_ids']);
                $this->response->setContentType('application/json')->setJSON(["success" => "Selected data has been deleted."])->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["error" => "There were some errors in deleting data."])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }

    }

    /**
     * Start a test drive
     */
    public function start($id)
    {
        $userId = service('authPolicy')->id ?? null;
        $response = $this->testDriveService->startTestDrive($id, $userId);

        if ($response['success']) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode(400)->setJSON($response)->send();
        }
    }

    /**
     * Complete a test drive
     */
    public function complete($id)
    {
        $data = $this->request->getPost();
        $userId = service('authPolicy')->id ?? null;

        $response = $this->testDriveService->completeTestDrive($id, $data, $userId);

        if ($response['success']) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode(400)->setJSON($response)->send();
        }
    }

    /**
     * Get test drive by session token (for mobile app)
     */
    public function getBySession($sessionToken)
    {
        $testDrive = $this->repository->getBySessionToken($sessionToken);

        if (!$testDrive) {
            $this->response->setContentType('application/json')->setStatusCode(404)
                ->setJSON(['success' => false, 'errors' => ['session' => 'Invalid session token']])->send();
            return;
        }

        $response = [
            'success' => true,
            'test_drive' => $testDrive
        ];

        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    /**
     * Get audit trail for a test drive
     */
    public function auditTrail($id)
    {
        $testDrive = $this->repository->find($id);

        if (!$testDrive) {
            $this->response->setContentType('application/json')->setStatusCode(404)
                ->setJSON(['success' => false, 'errors' => ['test_drive' => 'Test drive not found']])->send();
            return;
        }

        $auditTrail = $this->eventModel->getAuditTrail($id);

        $response = [
            'success' => true,
            'audit_trail' => $auditTrail
        ];

        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    /**
     * List all test drives with filtering and pagination
     */
    public function getNumberPlates()
    {
        $number_plates = (new ConfigurationRepository())->findConfigurationColumnsByKey('testdrive_number_plates', 'value');
        if (empty($number_plates->value)) {
            $this->response->setContentType('application/json')->setJSON(['success' => false, 'errors' => ['number_plates' => 'No number plates found']])->send();
            return;
        }
        $response = json_decode($number_plates->value, true);
        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    function updateSearchIndex()
    {
        $response = $this->repository->updateSearchIndex([]);

        if (!$response['success']) {
            $this->response->setContentType('application/json')->setJSON(['success' => false, 'errors' => 'Something went wrong'])->send();
        }
        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    public function filters()
    {
        $query = [];
        $result = [];
        $status = false;
        $query = null;
        if (count($_GET) > 0) {
            $query = $this->repository->prepareDataForFilter($_GET);
        }

        $items = 10;
        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }

        if ($this->request->getGet('status')) {
            $status = $this->request->getGet('status') == "true" ? true : false;
        }

        $result = $this->repository->allColumnAggregation($query, $status);

        $this->response->setContentType('application/json')->setJSON($result)->send();
    }

    function getGpsStats()
    {
        $result = $this->configuration_repository->findConfigurationColumnsByKeyAsArray('testdrive_gps_enabled');
        $this->response->setContentType('application/json')->setJSON(["status" => $result['value']])->send();
    }

    public function getLeadTestdrive(string $module, string $module_id)
    {
        $response = $this->repository->filterByModuleAndId($module, $module_id);
        $this->response->setContentType('application/json')->setJSON($response)->send();
    }
}

