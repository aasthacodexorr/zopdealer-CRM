<?php

namespace Tenant\app\Libraries\Services;

use Tenant\app\Models\TestDriveModel;
use Tenant\app\Models\TestDriveEventModel;
use Tenant\app\Models\TestDriveCustomerModel;
use Tenant\app\Models\InventoryModel;
use Tenant\app\Repositories\InventoryRepository;

class TestDriveService
{
    protected $testDriveModel;
    protected $eventModel;
    protected $testDriveCustomerModel;
    protected $inventoryModel;
    protected $inventoryRepository;

    public function __construct()
    {
        $this->testDriveModel = new TestDriveModel();
        $this->eventModel = new TestDriveEventModel();
        $this->testDriveCustomerModel = new TestDriveCustomerModel();
        $this->inventoryModel = new InventoryModel();
        $this->inventoryRepository = new InventoryRepository();
    }

    /**
     * Create a new test drive
     */
    public function create(array $data, ?string $userId = null): array
    {
        // Validate required fields
        if (empty($data['test_drives_customers_id'])) {
            return ['success' => false, 'errors' => ['test_drives_customers_id' => 'Customer ID is required']];
        }

        if (empty($data['inventory_id'])) {
            return ['success' => false, 'errors' => ['inventory_id' => 'Vehicle ID is required']];
        }

        if (empty($data['sales_rep_id'])) {
            return ['success' => false, 'errors' => ['sales_rep_id' => 'Sales Person ID is required']];
        }

        // Validate customer exists
        $customer = $this->testDriveCustomerModel->find($data['test_drives_customers_id']);
        if (!$customer) {
            return ['success' => false, 'errors' => ['test_drives_customers_id' => 'Customer not found']];
        }

        // Validate inventory exists
        $inventory = $this->inventoryRepository->findSingleInventoryByStockId($data['inventory_id']);
        if (!$inventory) {
            return ['success' => false, 'errors' => ['inventory_id' => 'Vehicle not found']];
        }

        // Set default status
        if (!isset($data['status'])) {
            $data['status'] = 'pending';
        }

        // Save test drive
        if ($this->testDriveModel->save($data)) {
            $testDriveId = $this->testDriveModel->getInsertID();

            // Log event
            $eventData = [
                'test_drives_customers_id' => $data['test_drives_customers_id'],
                'inventory_id' => $data['inventory_id']
            ];

            if (isset($data['sales_rep_id'])) {
                $eventData['sales_rep_id'] = $data['sales_rep_id'];
            }

            $this->eventModel->logEvent($testDriveId, 'created', $eventData, $userId);

            return [
               'success' => true,
                'test_drive_id' => $testDriveId,
                'message' => 'Test drive created successfully'
            ];
        }

        return ['success' => false, 'errors' => $this->testDriveModel->errors()];
    }

    /**
     * Start a test drive - generates session token and QR code
     */
    public function startTestDrive(int $testDriveId, ?string $userId = null): array
    {
        $testDrive = $this->testDriveModel->find($testDriveId);
        
        if (!$testDrive) {
            return ['success' => false, 'errors' => ['test_drive' => 'Test drive not found']];
        }

        if ($testDrive->status !== 'pending') {
            return ['success' => false, 'errors' => ['status' => 'Test drive must be in pending status to start']];
        }

        // Validate required fields
        if (empty($testDrive->customer_signature_url)) {
            return ['success' => false, 'errors' => ['signature' => 'Customer signature is required']];
        }

//        if (empty($testDrive->insurance_url)) {
//            return ['success' => false, 'errors' => ['insurance_url' => 'Insurance document is required']];
//        }

        if (empty($testDrive->odo_start)) {
            return ['success' => false, 'errors' => ['odo_start' => 'Starting odometer reading is required']];
        }

        // Generate session token
        $sessionToken = bin2hex(random_bytes(32));

        // Update test drive
        $updateData = [
            'id' => $testDriveId,
            'status' => 'in_progress',
            'session_token' => $sessionToken,
            'start_time' => date('Y-m-d H:i:s')
        ];

        if ($this->testDriveModel->save($updateData)) {
            // Log event
            $this->eventModel->logEvent($testDriveId, 'started', [
                'session_token' => $sessionToken,
                'start_time' => $updateData['start_time']
            ], $userId);

            return [
                'success' => true,
                'session_token' => $sessionToken,
                'qr_code_data' => $this->generateQRCodeData($sessionToken),
                'message' => 'Test drive started successfully'
            ];
        }

        return ['success' => false, 'errors' => $this->testDriveModel->errors()];
    }

    /**
     * Complete a test drive
     */
    public function completeTestDrive(int $testDriveId, array $data, ?string $userId = null): array
    {
        $testDrive = $this->testDriveModel->find($testDriveId);
        
        if (!$testDrive) {
            return ['success' => false, 'errors' => ['test_drive' => 'Test drive not found']];
        }

        if ($testDrive->status !== 'in_progress') {
            return ['success' => false, 'errors' => ['status' => 'Test drive must be in progress to complete']];
        }

        // Validate ending odometer
        if (empty($data['odo_end'])) {
            return ['success' => false, 'errors' => ['odo_end' => 'Ending odometer reading is required']];
        }

        if ($data['odo_end'] < $testDrive->odo_start) {
            return ['success' => false, 'errors' => ['odo_end' => 'Ending odometer must be greater than starting odometer']];
        }

        // Update test drive
        $updateData = [
            'id' => $testDriveId,
            'status' => 'completed',
            'odo_end' => $data['odo_end'],
            'end_time' => date('Y-m-d H:i:s'),
            'notes' => $data['notes'] ?? null
        ];

        if ($this->testDriveModel->save($updateData)) {
            // Log event
            $this->eventModel->logEvent($testDriveId, 'completed', [
                'odo_end' => $data['odo_end'],
                'end_time' => $updateData['end_time'],
                'distance' => $data['odo_end'] - $testDrive->odo_start
            ], $userId);

            return [
                'success' => true,
                'message' => 'Test drive completed successfully'
            ];
        }

        return ['success' => false, 'errors' => $this->testDriveModel->errors()];
    }

    /**
     * Generate QR code data for mobile app
     */
    protected function generateQRCodeData(string $sessionToken): string
    {
        // Get the current request to extract the actual subdomain
        $request = \Config\Services::request();
        $protocol = $request->isSecure() ? 'https' : 'http';
        $host = $request->getServer('HTTP_HOST');

        $baseUrl = $protocol . '://' . $host;

        return $baseUrl . '/api/business/test-drives/session/' . $sessionToken;
    }
}

