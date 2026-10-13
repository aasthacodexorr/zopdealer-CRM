<?php

namespace Tenant\app\Controllers;

use Tenant\app\Models\TestDriveModel;
use Tenant\app\Models\TestDrivePhotoModel;
use Tenant\app\Models\TestDriveDocumentModel;
use Tenant\app\Models\TestDriveGpsPointModel;
use Tenant\app\Models\TestDriveEventModel;
use Tenant\app\Models\TestDriveCustomerModel;
use Tenant\app\Models\InventoryModel;
use Tenant\app\Repositories\TestDriveRepository;
use Tenant\app\Repositories\ConfigurationRepository;
use Knp\Snappy\Pdf;

class TestDriveExportController extends \App\Controllers\BaseController
{
    protected $testDriveModel;
    protected $photoModel;
    protected $documentModel;
    protected $gpsPointModel;
    protected $eventModel;
    protected $testDriveCustomerModel;
    protected $inventoryModel;

    public function __construct()
    {
        $this->testDriveModel = new TestDriveModel();
        $this->photoModel = new TestDrivePhotoModel();
        $this->documentModel = new TestDriveDocumentModel();
        $this->gpsPointModel = new TestDriveGpsPointModel();
        $this->eventModel = new TestDriveEventModel();
        $this->testDriveCustomerModel = new TestDriveCustomerModel();
        $this->inventoryModel = new InventoryModel();

        $this->testDriveRepository = new TestDriveRepository();

    }

    /**
     * Export test drive as PDF.
     * Served to both Web and Mobile API callers.
     */
    public function exportPDF($testDriveId)
    {
        // Quick existence check before loading all related data
        $testDriveId = (int)$testDriveId;
        $testDrive = $this->testDriveModel->find($testDriveId);

        if (!$testDrive) {
            $this->response->setContentType('application/json')->setStatusCode(404)
                ->setJSON(['success' => false, 'errors' => ['test_drive' => 'Test drive not found']])->send();
            return;
        }

        // Load all related data through the repository (customer, inventory,
        // photos, documents, GPS points, events, distance, duration, sales rep)
        $details = $this->testDriveRepository->getTestDrivesWithAllDetails($testDriveId);

        if (!$details) {
            $this->response->setContentType('application/json')->setStatusCode(400)
                ->setJSON(['success' => false, 'errors' => ['test_drive' => 'Failed to load test drive details']])->send();
            return;
        }

        $testDrive = $details['test_drive'];
        $customer = $details['customer'];
        $inventory = $details['inventory'];
        $photos = $details['photos'];
        $documents = $details['documents'];
        $gpsPoints = $details['gps_points'];
        $events = $details['events'];
        $distance_km = $details['distance_km'];
        $duration_minutes = $details['duration_minutes'];
        $sales_person = $details['sales_person'];

        // Business logo
//        $blogo = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_logo', 'value');
//        $logo  = (!empty($blogo) && $blogo->value != '') ? ROOT_BUSINESS_LOGO . $blogo->value : '';

        // @todo : can be made dyanmic and use configuration table.
        $type = 'default';

        if (!is_file(APPPATH . 'Views/testdrive/' . $type . '.php')) {
            $this->response->setContentType('application/json')->setStatusCode(400)
                ->setJSON(['errors' => "Template couldn't be found."])->send();
            return;
        }

        $snappy = new Pdf();
        $snappy->setBinary(\h4cc\WKHTMLToPDF\WKHTMLToPDF::PATH);
        $snappy->setOptions([
            'encoding' => 'UTF-8',
            'page-size' => 'Letter',
            'margin-top' => '10mm',
            'margin-bottom' => '10mm',
            'margin-left' => '12mm',
            'margin-right' => '12mm',
        ]);

        $template = view(
            'Views/testdrive/' . $type,
            compact([
                'testDrive', 'customer', 'inventory',
                'photos', 'documents', 'gpsPoints',
                'events', 'distance_km', 'duration_minutes', 'sales_person',
            ])
        );

        $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="testdrive_' . $testDriveId . '.pdf"');

        echo $snappy->getOutputFromHtml($template);
    }

    /**
     * Export test drive as CSV
     */
    public function exportCSV($testDriveId)
    {
        $testDrive = $this->testDriveModel->find($testDriveId);

        if (!$testDrive) {
            $this->response->setContentType('application/json')->setStatusCode(404)
                ->setJSON(['success' => false, 'errors' => ['test_drive' => 'Test drive not found']])->send();
            return;
        }

        // Get related data
        $customer = $this->testDriveCustomerModel->find($testDrive->test_drives_customers_id);
        $inventory = $this->inventoryModel->find($testDrive->inventory_id);
        $sales_person = $this->testDriveRepository->getSalesPersonName($testDrive->sales_rep_id);

        // Prepare CSV data
        $csvData = [
            ['Field', 'Value'],
            ['Test Drive ID', $testDrive->id],
            ['Status', $testDrive->status],
            ['Customer Name', $customer ? $customer->getFullName() : 'N/A'],
            ['Customer Phone', $customer ? $customer->phone : 'N/A'],
            ['Customer Email', $customer ? $customer->email : 'N/A'],
            ['Customer Address', $customer ? $customer->getFullAddress() : 'N/A'],
            ['Driver License', $customer ? $customer->driver_license : 'N/A'],
            ['Driver License Expiry', $customer ? $customer->license_expiry : 'N/A'],

            ['Vehicle', $inventory->year . ' ' . $inventory->make . ' ' . $inventory->model . ' ' . $inventory->trim],
            ['Stock No', $inventory->stock_no],
            ['VIN', $inventory->vin],
            ['Sales Rep', $testDrive->sales_rep_id],
            ['Start Time', $testDrive->getStartTime()],
            ['End Time', $testDrive->getEndTime()],
            ['Duration (minutes)', $testDrive->getDurationMinutes()],
            ['Starting Odometer', $testDrive->odo_start],
            ['Ending Odometer', $testDrive->odo_end],
            ['Distance Traveled', $testDrive->getDistanceTraveled()],
            ['Notes', $testDrive->notes]
        ];

        // Generate CSV
        $filename = 'testdrive_' . $testDriveId . '_' . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        foreach ($csvData as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit;
    }

    /**
     * Export GPS route as JSON - (UNUSED)
     */
    public function exportGPSRoute($testDriveId)
    {
        $testDrive = $this->testDriveModel->find($testDriveId);

        if (!$testDrive) {
            $this->response->setContentType('application/json')->setStatusCode(404)
                ->setJSON(['success' => false, 'errors' => ['test_drive' => 'Test drive not found']])->send();
            return;
        }

        $route = $this->gpsPointModel->getRoute($testDriveId);

        $response = [
            'success' => true,
            'test_drive_id' => $testDriveId,
            'route' => $route,
            'total_points' => count($route)
        ];

        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    /**
     * Calculate distance from GPS points - (UNUSED)
     */
    protected function calculateDistance(array $points): float
    {
        // Implementation similar to GPSIngestService
        return 0.0; // Placeholder
    }
}

