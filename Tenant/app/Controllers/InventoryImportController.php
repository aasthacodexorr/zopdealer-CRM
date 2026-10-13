<?php

namespace Tenant\app\Controllers;

use CodeIgniter\CLI\CLI;
use Tenant\app\Libraries\Audit\AuditConstant;
use Tenant\app\Libraries\Import\InventoryImport;
use Tenant\app\Libraries\ThirdPartyImportAPI\PBS;
use Tenant\app\Libraries\ThirdPartyInventory\OpenLaneCSVAdapter;
use Tenant\app\Libraries\ThirdPartyInventory\OtolaneCSVAdapter;
use Tenant\app\Libraries\ThirdPartyInventory\ThirdPartyAdapterInterface;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\FacebookMarketPlaceRepository;
use Tenant\app\Repositories\InventoryRepository;
use Tenant\app\Repositories\LocationRepository;

class InventoryImportController extends \App\Controllers\BaseController
{

    /**
     * repository This repository corresponds to the InventoryRepository which can be used by any method here.
     *
     * @var InventoryRepository
     */
    private $inventory_repository;

    /**
     * configuration_repository
     *
     * @var ConfigurationRepository
     */
    protected $configuration_repository;

    public function __construct()
    {
        $this->inventory_repository = new InventoryRepository();
        $this->configuration_repository = new ConfigurationRepository();

        service('auditContext')
            ->setSource(AuditConstant::IMPORT_FILE);
    }

    public function importInventory()
    {
        if (service('authPolicy')->isSuperOwner()) {
            $namespaces = [
                'Tenant\app\Libraries\Import\InventoryImageImport',
                'Tenant\app\Libraries\Import\InventoryFileImport'
            ];
            $validated = $this->validate([
                'file' => [
                    'uploaded[file]',
                ],
            ]);

            $import_enable = (new ConfigurationRepository())->findConfigurationColumnsByKey('enable_import', 'value')->value;
            $import_enable = intval($import_enable);
            if (empty($import_enable)) {
                $this->validator->setError('configuration', 'Import Functionality not enabled for this tenant.');
            }

            if ($validated && $import_enable) {

                $img = $this->request->getFile('file');
                $newName = $img->getRandomName();
                $img->move(WRITEPATH . 'uploads', $newName);

                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load(WRITEPATH . 'uploads/' . $newName);
                $spreadsheet->getSheet(0);

                $content = $spreadsheet->getActiveSheet()->toArray('');
                $errors = [];

                if (isset($content)) {

                    $column = $content[0];
                    $row_index = 0;
                    foreach ($content as $row) {
                        if (($row_index++) === 0) continue;

                        $inventoryImport = new InventoryImport();
                        $single_inventory = array_combine($column, $row);
                        $error_response = $inventoryImport->validate($single_inventory, $namespaces, []);
                        if (!empty($error_response)) {
                            $errors[$single_inventory['stock_no']] = $error_response;
                        }
                    }
                }


                $responses = [];

                $valid_filename = service('authPolicy')->getCurrentOrganization() . '.' . $img->getClientExtension();
                $current_filename = $img->getClientName();

                if ($valid_filename != $current_filename) {
                    $errors['File Error'] = ['filename' => 'Filename does not match with tenant name. Please recheck.'];
                }

                if (count($errors) == 0) {

                    if (isset($content)) {

                        $column = $content[0];
                        $row_index = 0;
                        foreach ($content as $row) {
                            if (($row_index++) === 0) continue;

                            $inventoryImport = new InventoryImport();
                            $single_inventory = array_combine($column, $row);
                            $responses[$single_inventory['stock_no']] = $inventoryImport->process($single_inventory, $namespaces, []);
                        }

                        $this->postProcessing();
                        $this->response->setContentType('application/json')->setJSON($responses)->send();
                    }
                } else {
                    $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($errors)->send();
                }
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function completeInventoryEntity()
    {
        $namespaces = [
            'Tenant\app\Libraries\Import\InventoryFileImport',
            'Tenant\app\Libraries\Import\InventoryPurchaseImport',
            'Tenant\app\Libraries\Import\InventoryCostImport'
        ];

        $data = $this->request->getPost();
        $data['stock_no'] = !empty($data['stock_no']) ? $data['stock_no'] : $this->inventory_repository->getAvailableStockNumber($data);


        $inventoryImport = new InventoryImport();
        $errors = $inventoryImport->validate($data, $namespaces, []);

        if (empty($errors)) {
            $response = $inventoryImport->process($data, $namespaces, []);

            if (!empty($response['success'])) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($errors)->send();
        }
    }

    public function thirdPartyInventoryImport($source)
    {
        $namespaces = [
            'Tenant\app\Libraries\Import\InventoryFileImport',
            'Tenant\app\Libraries\Import\InventoryPurchaseImport',
            'Tenant\app\Libraries\Import\InventoryCostImport'
        ];

        $import_third_party_adapter = new OpenLaneCSVAdapter();

        switch ($source) {
            case 'openlane':
                $import_third_party_adapter = new OpenLaneCSVAdapter();
                break;

            case 'otolane':
                $import_third_party_adapter = new OtolaneCSVAdapter();
                break;
        }

        $validated = $this->validate([
            'file' => [
                'uploaded[file]',
            ],
        ]);

        $optional_data = $this->setUpThirdPartyOptionalData();

        if ($validated) {
            try {
                $img = $this->request->getFile('file');
                $newName = $img->getRandomName();
                $img->move(WRITEPATH . 'uploads', $newName);

                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load(WRITEPATH . 'uploads/' . $newName);
                $spreadsheet->getSheet(0);

                $content = $spreadsheet->getActiveSheet()->toArray('');
                $errors = [];

                if (isset($content)) {

                    $column = $content[0];
                    $row_index = 0;
                    foreach ($content as $row) {
                        if (($row_index++) === 0) continue;

                        $inventoryImport = new InventoryImport();
                        $single_inventory = array_combine($column, $row);
                        $single_inventory_payload = $import_third_party_adapter->preprocess($single_inventory, $optional_data);
                        $single_inventory_payload['stock_no'] = empty($single_inventory_payload['stock_no']) ? $this->inventory_repository->getAvailableStockNumber($single_inventory_payload) : $single_inventory_payload['stock_no'];
                        $error_response = $inventoryImport->validate($single_inventory_payload, $namespaces, []);
                        if (!empty($error_response)) {
                            $errors[$single_inventory_payload['vin']] = $error_response;
                        }
                    }
                }

                $responses = [];

                if (count($errors) == 0) {

                    if (isset($content)) {

                        $column = $content[0];
                        $row_index = 0;
                        foreach ($content as $row) {
                            if (($row_index++) === 0) continue;

                            $inventoryImport = new InventoryImport();
                            $single_inventory = array_combine($column, $row);
                            $single_inventory['stock_no'] = rand();
                            $single_inventory_payload = $import_third_party_adapter->preprocess($single_inventory, $optional_data);
                            $single_inventory_payload['stock_no'] = empty($single_inventory_payload['stock_no']) ? $this->inventory_repository->getAvailableStockNumber($single_inventory_payload) : $single_inventory_payload['stock_no'];
                            $responses[$single_inventory_payload['vin']] = $inventoryImport->process($single_inventory_payload, $namespaces, []);
                        }

                        $this->postProcessing();
                        $this->response->setContentType('application/json')->setJSON($responses)->send();
                    }
                } else {
                    $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($errors)->send();
                }
            } catch (\Exception $ex) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "We couldn't process this " . $source . " file. Please contact our support staff for assistance."])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    public function thirdPartyAPIBasedInventoryImport()
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            $pbs = new PBS();
            if ($pbs->checkAuthorization()) {
                $db = db_connect();
                $app_builder = $db->table('app')->get()->getRowArray();
                $app_name = $app_builder['name'];

                exec("php index.php thirdPartyAPIBasedInventoryImportQueue resetSchedule $app_name");
                $this->response->setContentType('application/json')->setJSON(['success' => 'Your request is queued and will be processed shortly.'])->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('401')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function thirdPartyPreprocessAdapter(array $data, ThirdPartyAdapterInterface $import_third_party_adapter): ?array
    {
        $namespaces = [
            'Tenant\app\Libraries\Import\InventoryFileImport',
            'Tenant\app\Libraries\Import\InventoryPurchaseImport',
            'Tenant\app\Libraries\Import\InventoryCostImport'
        ];

        $optional_data = $this->setUpThirdPartyOptionalData();
        $optional_data['status'] = "Instock";

        $single_inventory_payload = $import_third_party_adapter->preprocess($data, $optional_data);
        $single_inventory_payload['stock_no'] = !empty($single_inventory_payload['stock_no']) ? $single_inventory_payload['stock_no'] : $this->inventory_repository->getAvailableStockNumber($single_inventory_payload);


        $inventoryImport = new InventoryImport();
        $errors = $inventoryImport->validate($single_inventory_payload, $namespaces, []);

        if (empty($errors)) {
            $response = $inventoryImport->process($single_inventory_payload, $namespaces, []);
            return $response;
        } else {
            return $errors;
        }
    }

    public function postProcessing(): void
    {
        $ci_env = getEnv('CI_ENVIRONMENT');

        $db = db_connect();
        $app_builder = $db->table('app')->get()->getRowArray();
        $app_name = $app_builder['name'];

        $is_development_env = $ci_env == 'development';

        if (!$is_development_env) {
            (new InventoryRepository())->updateTypesenseIndexService([]);
            (new InventoryRepository())->purgeWebsiteCache([], 10, true);
            (new FacebookMarketPlaceRepository())->changeStatusFromToBeUpdatedToUpdated();

            exec("php index.php downloadThirdPartyImageQueue resetSchedule $app_name");
        }

        db_connect()->close();
        return;
    }

    private function setUpThirdPartyOptionalData(): array
    {
        $business = (new LocationRepository())->getLocationByName("");
        $optional_data = [];

        $optional_data['status'] = "Unpublished";
        $optional_data['sub_status'] = "Reconditioning";
        $optional_data['feature_listing'] = "0";
        $optional_data['vehicle_type'] = "Used";
        $optional_data['odometer'] = 0;
        $optional_data['special_price'] = $optional_data['price'] = 0;
        $optional_data['location'] = !empty($business['name']) ? $business['name'] : "";
        $optional_data['title'] = $this->configuration_repository->findConfigurationColumnsByKey('editor_default_title', 'value')->value;
        $optional_data['vehicle_description'] = $this->configuration_repository->findConfigurationColumnsByKey('editor_default_vehicle_description', 'value')->value;

        $optional_data['financing_on'] = $this->configuration_repository->findConfigurationColumnsByKey('vehicle_default_financing', 'value')->value;
        $optional_data['downpayment'] = $this->configuration_repository->findConfigurationColumnsByKey('vehicle_default_downpayment', 'value')->value;
        $optional_data['interest_rate'] = $this->configuration_repository->findConfigurationColumnsByKey('vehicle_default_interest_rate', 'value')->value;
        $optional_data['term'] = $this->configuration_repository->findConfigurationColumnsByKey('vehicle_default_term', 'value')->value;
        $optional_data['payment_frequency'] = $this->configuration_repository->findConfigurationColumnsByKey('vehicle_default_payment_frequency', 'value')->value;

        return $optional_data;
    }
}
