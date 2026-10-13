<?php

namespace Tenant\app\Controllers;

use App\Helpers\Utility;
use Carbon\Carbon;
use Tenant\app\Libraries\PdfFiller\PDFFillerFactory;
use Tenant\app\Models\DealAccessoryModel;
use Tenant\app\Models\DealCustomerPivotModel;
use Tenant\app\Models\DealFinanceModel;
use Tenant\app\Models\DealGuaranteeModel;
use Tenant\app\Models\DealInsuranceModel;
use Tenant\app\Models\DealInventoryPivotModel;
use Tenant\app\Models\DealPaymentModel;
use Tenant\app\Models\DealReportModel;
use Tenant\app\Models\DealTradeInModel;
use Tenant\app\Models\DealWarrantyModel;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\CustomerCreditApplicationRepository;
use Tenant\app\Repositories\CustomerInsuranceRepository;
use Tenant\app\Repositories\CustomerRepository;
use Tenant\app\Repositories\DealCommonDependentRepository;
use Tenant\app\Repositories\DealRepository;
use Tenant\app\Repositories\DealTradeInRepository;
use Tenant\app\Repositories\InventoryRepository;
use Tenant\app\Repositories\LocationRepository;
use Tenant\app\Repositories\TenantUserMetaDataRepository;
use App\Libraries\CloudStorage\CloudStorageFactory;
use App\Libraries\Schedule\ScheduleConstant;
use App\Libraries\WorkFlow\WorkFlowConstant;
use CodeIgniter\Services;
use Knp\Snappy\Pdf;
use Tenant\app\Entities\DealWorksheet;
use Tenant\app\Libraries\AttachmentAggregator\AttachmentAggregatorConstant;
use Tenant\app\Libraries\AttachmentAggregator\DealAttachmentAggregator;
use Tenant\app\Libraries\AttachmentAggregator\InventoryPurchaseAggregator;
use Tenant\app\Libraries\Report\ReportFactory;
use Tenant\app\Libraries\RollADeal\AdjustorFactory;
use Tenant\app\Repositories\DealTradeInCommonRepository;
use Tenant\app\Repositories\InventoryCommonDependentRepository;
use Tenant\app\Repositories\InventoryCostRepository;
use Tenant\app\Repositories\InventoryImportRepository;
use Tenant\app\Repositories\InventoryPurchaseRepository;
use Tenant\app\Libraries\PdfFiller\PDFFillerConstant;

class DealController extends \App\Controllers\BaseController
{
    /**
     * repository This repository corresponds to the DealRepository which can be used by any method here.
     *
     * @var DealRepository
     */
    protected $repository;

    protected $common_repository;

    protected $configuration_repository;

    protected $tenant_user_repository;

    /**
     * CustomerRepository
     *
     * @var CustomerRepository
     */
    protected $customer_repository;

    /**
     * inventory_repository
     *
     * @var InventoryRepository
     */
    protected $inventory_repository;

    public function __construct()
    {
        $this->repository = new DealRepository();
        $this->common_repository = new DealCommonDependentRepository();
        $this->configuration_repository = new ConfigurationRepository();
        $this->tenant_user_repository = new TenantUserMetaDataRepository();
        $this->inventory_repository = new InventoryRepository();
        $this->customer_repository = new CustomerRepository();
    }

    public function index()
    {

        $query = [];
        $query = null;
        if (count($_GET) > 0) {
            $query = $this->repository->prepareDataForFilter($_GET);
        }

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

        if ($this->request->getGet('only_current_month')) {
            if ($this->request->getGet('only_current_month') == "true") {
                $data->filterOnCurrentMonthAndYear("date");
            }
        }

        if (!$this->isCurrentUserAuthorizedToViewAllDeals()) {
            $data->filterData("salesperson", service('authPolicy')->id);
        }

        $sort = '{"' . $column . '" : "' . $order . '"}';

        $data = $data->index(null, $header = null, null, $query, $items, $sort, $paginate, 'deal_view_id', 'deal_listing');

        $this->response->setContentType('application/json')->setJSON($data)->send();
    }

    public function create()
    {
        $data = $this->request->getPost();
        $response = $this->repository->create($data);

        if (isset($response['success'])) {

            if (isset($data['primary_customer']) && is_numeric($data['primary_customer'])) {
                $primary_customer_type = isset($data['primary_customer_type']) ? $data['primary_customer_type'] : 'primary_customer';
                $this->repository->setModel(new DealCustomerPivotModel())->create(['deal_id' => $response['deal_id'], 'customer_id' => $data['primary_customer'], 'customer_type' => $primary_customer_type]);
            }

            if (isset($data['secondary_customer']) && is_numeric($data['secondary_customer'])) {
                $secondary_customer_type = isset($data['secondary_customer_type']) ? $data['secondary_customer_type'] : 'co-buyer';
                $this->repository->setModel(new DealCustomerPivotModel())->create(['deal_id' => $response['deal_id'], 'customer_id' => $data['secondary_customer'], 'customer_type' => $secondary_customer_type]);
            }

            if (isset($data['inventory'])) {
                $this->repository->setModel(new DealInventoryPivotModel())->create(['deal_id' => $response['deal_id'], 'stock_no' => $data['inventory'], 'odometer' => isset($data['odometer']) && is_numeric($data['odometer']) ? $data['odometer'] : '',]);

                if (!empty($data['mark_inventory_as_sold'])) {
                    if ($data['mark_inventory_as_sold'] == "true") {
                        (new InventoryRepository())->findSingleInventoryByStockIdAndUpdate($data['inventory'], ['status' => 'Sold']);
                    }
                }

                if (!empty($data['inventory_sub_status'])) {
                    (new InventoryRepository())->findSingleInventoryByStockIdAndUpdate($data['inventory'], ['sub_status' => $data['inventory_sub_status']]);
                }
            }

            $this->repository->updateSearchIndex([$response['deal_id']]);

            service('workFlowService')->attach(WorkFlowConstant::CREATE, ['module' => WorkFlowConstant::DEAL, 'module_id' => $response['deal_id']]);

            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function show($id)
    {
        $response = $this->repository->show($id);

        $response["private_share_id"] = Utility::encryptString(md5(db_connect()->database) . "-" . $response['id'], getenv("password.encryption"));

        $this->existenceOrRightsCheckForDeal($response);

        if (isset($response['id'])) {
            $response['customers'] = $this->repository->setModel(new DealCustomerPivotModel())->findByDealId($response['id']);
            $response['inventory'] = $this->repository->setModel(new DealInventoryPivotModel())->findByDealId($response['id']);
        }

        if (isset($response)) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Deal couldn't be found."])->send();
        }
    }

    public function summary($id)
    {
        $response = $this->repository->show($id);

        $response["private_share_id"] = Utility::encryptString(md5(db_connect()->database) . "-" . $response['id'], getenv("password.encryption"));

        $this->existenceOrRightsCheckForDeal($response);

        if (isset($response['id'])) {
            $response['customers'] = $this->repository->setModel(new DealCustomerPivotModel())->findByDealId($response['id']);
            $response['inventory'] = $this->repository->setModel(new DealInventoryPivotModel())->findByDealId($response['id']);
        }

        $response['first_name'] = $response['last_name'] = $response['phone'] = $response['mobile'] = $response['email'] = '';


        if (!empty($response['customers'])) {
            foreach ($response['customers'] as $key => $customer) {
                $deal_customer_type = $customer['customer_type'];

                if ($deal_customer_type === 'primary_customer') {
                    $primary_customer = (new CustomerRepository())->show($customer['customer_id']);
                    if (!empty($primary_customer)) {
                        $response['first_name'] = $primary_customer['first_name'];
                        $response['last_name'] = $primary_customer['last_name'];
                        $response['phone'] = $primary_customer['phone'];
                        $response['mobile'] = $primary_customer['mobile'];
                        $response['email'] = $primary_customer['email'];
                    }
                }
            }

            unset($response['customers']);
        }

        $response['year'] = $response['make'] = $response['model'] = $response['stock_no'] = $response['vin'] = '';

        if (!empty($response['inventory'])) {
            foreach ($response['inventory'] as $key => $inventory) {
                $inventory = (new InventoryRepository())->findSingleInventoryByStockId($inventory['stock_no']);
                $response['year'] = $inventory['year'];
                $response['make'] = $inventory['make'];
                $response['model'] = $inventory['model'];
                $response['stock_no'] = $inventory['stock_no'];
                $response['vin'] = $inventory['vin'];
            }

            unset($response['inventory']);
        }

        if (isset($response)) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Deal couldn't be found."])->send();
        }
    }

    public function update($id)
    {
        $available_deal = $this->repository->show($id);

        $this->existenceOrRightsCheckForDeal($available_deal);

        $this->isLocked($available_deal);

        $data = $this->request->getPost();

        $response = $this->repository->update($id, $data);

        if (isset($response['success'])) {

            $this->repository->setModel(new DealCustomerPivotModel())->filterByDealId($id)->deleteWithoutKey();
            $this->repository->setModel(new DealInventoryPivotModel())->filterByDealId($id)->deleteWithoutKey();

            if (isset($data['primary_customer']) && is_numeric($data['primary_customer'])) {
                $primary_customer_type = isset($data['primary_customer_type']) ? $data['primary_customer_type'] : 'primary_customer';
                $this->repository->setModel(new DealCustomerPivotModel())->create(['deal_id' => $id, 'customer_id' => $data['primary_customer'], 'customer_type' => $primary_customer_type]);
            }

            if (isset($data['secondary_customer']) && is_numeric($data['secondary_customer']) && $data['retail_or_wholesale_flag'] != '1') {
                $secondary_customer_type = isset($data['secondary_customer_type']) ? $data['secondary_customer_type'] : 'co-buyer';
                $this->repository->setModel(new DealCustomerPivotModel())->create(['deal_id' => $id, 'customer_id' => $data['secondary_customer'], 'customer_type' => $secondary_customer_type]);
            }

            if ($data['retail_or_wholesale_flag'] == '1') {
                $this->repository->setModel(new DealAccessoryModel())->filterByDealId($id)->deleteWithoutKey();
                $this->repository->setModel(new DealWarrantyModel())->filterByDealId($id)->deleteWithoutKey();
                $this->repository->setModel(new DealInsuranceModel())->filterByDealId($id)->deleteWithoutKey();
                $this->repository->setModel(new DealGuaranteeModel())->filterByDealId($id)->deleteWithoutKey();
                $this->repository->setModel(new DealFinanceModel())->filterByDealId($id)->deleteWithoutKey();
                $this->repository->setModel(new DealTradeInModel())->filterByDealId($id)->deleteWithoutKey();

                if (!$this->isPaymentSectionEnabledForWholeSaleDeal()) {
                    $this->repository->setModel(new DealPaymentModel())->filterByDealId($id)->deleteWithoutKey();
                }
            }

            if ($data['type'] != 'Financed') {
                $this->repository->setModel(new DealFinanceModel())->filterByDealId($id)->deleteWithoutKey();
            }

            if (isset($data['inventory'])) {
                $this->repository->setModel(new DealInventoryPivotModel())->create(['deal_id' => $id, 'stock_no' => $data['inventory'], 'odometer' => isset($data['odometer']) && is_numeric($data['odometer']) ? $data['odometer'] : '',]);

                if (!empty($data['mark_inventory_as_sold'])) {
                    if ($data['mark_inventory_as_sold'] == "true") {
                        (new InventoryRepository())->findSingleInventoryByStockIdAndUpdate($data['inventory'], ['status' => 'Sold']);
                    }
                }

                if (!empty($data['inventory_sub_status'])) {
                    (new InventoryRepository())->findSingleInventoryByStockIdAndUpdate($data['inventory'], ['sub_status' => $data['inventory_sub_status']]);
                }
            }

            if (isset($data['import_inventory']) && $data['status'] == 'Delivered') {
                if ($data['import_inventory'] == "true") {
                    $tradein_inventories = (new DealTradeInRepository())->filterByDealIdAsArray($id);

                    if ($tradein_inventories != null && is_array($tradein_inventories)) {
                        foreach ($tradein_inventories as $tradein) {
                            $tradein['status'] = "Unpublished";
                            $tradein['stock_date'] = Carbon::now()->format('Y-m-d');
                            $tradein['sub_status'] = "Reconditioning";

                            $disclosures = (new DealTradeInCommonRepository())->switchDealTradeInCommonDependentState("disclosures")->filterByTradeInAsArray($tradein['id']);

                            $purchase_details = null;
                            if (isset($data['primary_customer']) && is_numeric($data['primary_customer'])) {
                                $purchase_details = (new CustomerRepository())->show($data['primary_customer']);
                            }

                            if (!empty($purchase_details)) {
                                $purchase_details['price'] = $tradein['trade_value'];
                                $purchase_details['discount'] = $purchase_details['tax_amount'] = $purchase_details['tax_percentage'] = $purchase_details['tax_amount_secondary'] = $purchase_details['tax_percentage_secondary'] = $purchase_details['tax_amount_total'] = 0;
                                $purchase_details['tax_type'] = $purchase_details['tax_type_secondary'] = 'Tax Exempt';
                                $purchase_details['purchase_date'] = Carbon::now()->format('m/d/Y');
                            }

                            $tradein['stock_no'] = $this->inventory_repository->getAvailableStockNumber($tradein);
                            $this->importInventory($tradein, $disclosures, $purchase_details);
                        }
                    }
                }
            }

            $this->repository->updateSearchIndex([$id]);

            service('workFlowService')->attach(WorkFlowConstant::UPDATE, ['module' => WorkFlowConstant::DEAL, 'module_id' => $id]);
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function lock($id)
    {
        $available_deal = $this->repository->show($id);

        $this->existenceOrRightsCheckForDeal($available_deal);

        if (!service('authPolicy')->hasElevatedRoles()) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => ERROR_RESOURCE_PERMISSION])->send();
            exit;
        }

        $this->repository->allowUpdateOfProtectedFields($id, ['lock' => 1]);
        $response = $this->repository->show($id);
        $this->response->setContentType('application/json')->setJSON(['success' => 'Deal has been locked from further changes.'])->send();
    }

    public function unlock($id)
    {

        $validated = $this->validate(['password' => ["label" => 'Password', "rules" => 'required']]);


        if ($validated) {

            $data = $this->request->getPost();

            $available_deal = $this->repository->show($id);

            $this->existenceOrRightsCheckForDeal($available_deal);

            if (!service('authPolicy')->hasElevatedRoles()) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => ERROR_RESOURCE_PERMISSION])->send();
                exit;
            }

            $current_database = db_connect()->getDatabase();

            if (service('authPolicy')->isSuperOwner()) {
                db_connect()->setDatabase(getenv('database.default.database'));
            }

            $valid_authentication = $this->tenant_user_repository->checkUserAuthentication(service('authPolicy')->currentUserID(), $data['password']);

            if (!$valid_authentication) {
                $this->response->setContentType('application/json')->setStatusCode(400)->setJSON(['error' => 'Password is incorrect.'])->send();
                exit;
            }

            db_connect()->setDatabase($current_database);


            $this->repository->allowUpdateOfProtectedFields($id, ['lock' => 0]);
            $response = $this->repository->show($id);
            $this->response->setContentType('application/json')->setJSON(['success' => 'Deal has been unlocked now.'])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    public function multipleDeletes()
    {
        $data = $this->request->getPost();

        $data['deal_ids'] = explode(',', $data['deal_ids']);

        if (count($data['deal_ids']) > 0) {
            $this->repository->delete($data['deal_ids']);
            $this->response->setContentType('application/json')->setJSON(["success" => "Selected data has been deleted."])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["error" => "There were some errors in deleting data."])->send();
        }
    }

    public function getDealCustomer($id, $customer_id)
    {
        $deal = $this->repository->show($id);
        $this->existenceOrRightsCheckForDeal($deal);

        if (isset($deal['id'])) {
            $customers = $this->repository->setModel(new DealCustomerPivotModel())->findByDealId($deal['id']);
            if (!empty($customers)) {
                foreach ($customers as $key => $customer) {
                    if ($customer_id === $customer['customer_id']) {
                        $deal_customer = (new CustomerRepository())->show($customer['customer_id']);
                        $this->response->setContentType('application/json')->setJSON($deal_customer)->send();
                        exit;
                    }
                }
            }
        }

        $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Customer couldn't be found."])->send();
    }

    public function generatePDF($bill_type, $id)
    {
        $private_pdf_mapper = ["private-alternate-billofsale" => "alternate-billofsale", "private-billofsale" => "billofsale", "private-test-drive" => "test-drive", "private-deposit-agreement" => "deposit-agreement", "private-credit-application" => "credit-application", "private-trade-in" => "trade-in", "private-trade-in-disclosures" => "trade-in-disclosures", "private-vehicle-history-report-acknowledgement" => "vehicle-history-report-acknowledgement", "private-customer-worksheet" => "customer-worksheet", "private-open-disclosure-agreement" => "open-disclosure-agreement", "private-exempt-motor-vehicle" => "exempt-motor-vehicle"];
        $pdf = null;

        if (array_key_exists($bill_type, $private_pdf_mapper)) {
            $id = Utility::decryptString($id, getenv("password.encryption"));
            $id = str_replace(md5(db_connect()->database) . "-", "", $id);
        }
        $response = $this->repository->show($id);
        if (array_key_exists($bill_type, $private_pdf_mapper)) {
            $bill_type = $private_pdf_mapper[$bill_type];

            if ($response == null || !isset($response['id'])) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Deal couldn't be found."])->send();
                exit;
            }
        } else {
            $this->existenceOrRightsCheckForDeal($response);
        }

        if ($bill_type == 'deal-recap' && !service('authPolicy')->hasElevatedRoles()) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => ERROR_RESOURCE_PERMISSION])->send();
            exit;
        }

        $response = $this->appendDealDependencyResponse($id, $response, $bill_type);
        $response['dealer_website'] = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_website', 'value')->value;

        $configuration = new ConfigurationRepository();

        $template_name = $this->getDefaultTemplateNameBasedOnDealType((int)$response["retail_or_wholesale_flag"]);
        $template_name_configuration_key = $this->getTemplateNameConfigurationKeyBasedOnBillType($bill_type);

        if (!empty($template_name_configuration_key)) {
            $template_name = ($configuration->findConfigurationColumnsByKey($template_name_configuration_key, ['value']))->value;
            $pdf = PDFFillerFactory::getInstance($template_name, $this->getPDFMapperTypeBasedOnBillType($bill_type));
        } else {
            if ($bill_type == 'alternate-billofsale') {
                $template_name = $this->getAlternateTemplateNameBasedOnDealType((int)$response["retail_or_wholesale_flag"]);
            }

            //Override approver to salesperson for clients based on configuration
            $override_approver_flag = ($configuration->findConfigurationColumnsByKey('override_salesperson_as_approver_of_wholesale_deal', ['value']))->value;
            $override_approver_flag = intval($override_approver_flag);

            if (!empty($override_approver_flag) && (int)$response["retail_or_wholesale_flag"] == 1) {
                if (!empty($response['salesperson']) && !empty($response['approved_by'])) {
                    $response['approved_by'] = $response['salesperson'];
                }
            }
            $pdf = PDFFillerFactory::getInstance($template_name, $response["retail_or_wholesale_flag"]);
        }

        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->dataMap($response)->output();
    }

    private function getAlternateTemplateNameBasedOnDealType($deal_type): string
    {
        $configuration = new ConfigurationRepository();
        $retail_bos = ($configuration->findConfigurationColumnsByKey('alternate_retail_bos', ['value']))->value;
        $wholesale_bos = ($configuration->findConfigurationColumnsByKey('alternate_wholesale_bos', ['value']))->value;
        return $deal_type == 0 ? $retail_bos : $wholesale_bos;
    }

    private function getDefaultTemplateNameBasedOnDealType($deal_type): string
    {
        $configuration = new ConfigurationRepository();
        $retail_bos = ($configuration->findConfigurationColumnsByKey('retail_bos', ['value']))->value;
        $wholesale_bos = ($configuration->findConfigurationColumnsByKey('wholesale_bos', ['value']))->value;
        return $deal_type == 0 ? $retail_bos : $wholesale_bos;
    }

    private function getTemplateNameConfigurationKeyBasedOnBillType(string $bill_type): string
    {
        switch ($bill_type) {
            case 'test-drive':
                return 'test_drive_template';

            case 'deposit-agreement':
                return 'deposit_agreement';

            case 'credit-application':
                return 'individual_credit_application';

            case 'trade-in':
                return 'trade_in_template';

            case 'trade-in-disclosures':
                return 'trade_in_disclosure_template';

            case 'vehicle-history-report-acknowledgement':
                return 'vehicle_history_report_acknowledgement_template';

            case 'customer-worksheet':
                return 'customer_worksheet_template';

            case 'open-disclosure-agreement':
                return 'open_disclosure_agreement';

            case 'vehicle-disclosures':
                return 'vehicle_disclosure_template';

            case 'internal-inspection':
                return 'vehicle_internal_inspection_template';

            case 'deal-recap':
                return 'deal_recap_template';

            case 'exempt-motor-vehicle':
                return 'exempt_motor_vehicle_template';
            default:
                return '';
        }
    }

    private function getPDFMapperTypeBasedOnBillType(string $bill_type): int
    {
        switch ($bill_type) {
            case 'test-drive':
                return PDFFillerConstant::TEST_DRIVE;

            case 'deposit-agreement':
                return PDFFillerConstant::TEST_DRIVE;

            case 'credit-application':
                return PDFFillerConstant::CREDIT_APPLICATION;

            case 'trade-in':
                return PDFFillerConstant::TRADEIN;

            case 'trade-in-disclosures':
                return PDFFillerConstant::TRADEIN;

            case 'vehicle-history-report-acknowledgement':
                return PDFFillerConstant::VEHICLE_HISTORY_REPORT_ACKNOWLEDGEMENT;

            case 'customer-worksheet':
                return PDFFillerConstant::CUSTOMER_WORKSHEET;

            case 'open-disclosure-agreement':
                return PDFFillerConstant::OPEN_DISCLOSURE_AGREEMENT;

            case 'vehicle-disclosures':
                return PDFFillerConstant::VEHICLE_DISCLOSURES;

            case 'internal-inspection':
                return PDFFillerConstant::INTERNAL_INSPECTION_CHECKLIST;

            case 'deal-recap':
                return PDFFillerConstant::DEAL_RECAP;

            case 'exempt-motor-vehicle':
                return PDFFillerConstant::BOS_RETAIL;
            default:
                return -1;
        }
    }

    public function completeDealPayloadForExtension($id)
    {
        $url_params = $this->request->getGet();
        $extension_crawler_token = (new ConfigurationRepository())->findConfigurationColumnsByKeyAsArray('extension_crawler_token', 'value');

        if (!empty($url_params['token']) && $url_params['token'] === $extension_crawler_token['value']) {
            try {
                $id = Utility::decryptString($id, getenv("password.encryption"));
                $id = str_replace(md5(db_connect()->database) . "-", "", $id);

                $response = $this->repository->show($id);

                if ($response == null || !isset($response['id'])) {
                    $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Deal couldn't be found."])->send();
                    exit;
                }


                $response = $this->appendDealDependencyResponse($id, $response);
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } catch (\Exception $ex) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Deal couldn't be found."])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('401')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function completeDealCalculationBreakdown($id)
    {
        $response = $this->repository->show($id);

        $this->existenceOrRightsCheckForDeal($response);

        if (!service('authPolicy')->hasElevatedRoles()) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => ERROR_RESOURCE_PERMISSION])->send();
            exit;
        }

        $response = $this->appendDealDependencyResponse($id, $response);

        $response['dealer_website'] = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_website', 'value')->value;

        $pdf = PDFFillerFactory::getInstance("", PDFFillerConstant::DEAL_RECAP);
        $pdf->dataMap($response);

        $this->response->setContentType('application/json')->setJSON($pdf->getData())->send();
    }

    public function recalculateCalculatedSalesCommission($id)
    {
        $response = $this->repository->show($id);

        $this->existenceOrRightsCheckForDeal($response);

        $this->isLocked($response);

        if (!service('authPolicy')->hasElevatedRoles()) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => ERROR_RESOURCE_PERMISSION])->send();
            exit;
        }

        $calculated_deal_breakdown = $this->appendDealDependencyResponse($id, $response);

        $pdf = PDFFillerFactory::getInstance("", PDFFillerConstant::DEAL_RECAP);
        $pdf->dataMap($calculated_deal_breakdown);

        $calculated_deal_breakdown = $pdf->getData();

        $deal_sale_commissions = $this->common_repository->switchDealCommonDependentState('sale-commissions')->filterByDealIdAsArray($id);

        foreach ($deal_sale_commissions as $sale_commission) {
            if ($sale_commission['type'] !== 'Flat') {
                $calculated_amount = $this->setCalculatedValue($calculated_deal_breakdown, $sale_commission);
                $this->common_repository->switchDealCommonDependentState('sale-commissions')->update($sale_commission['id'], ['calculated_amount' => $calculated_amount]);
            }
        }

        $this->response->setContentType('application/json')->setJSON(['success' => 'Sales Commission`s Calculated Amount has been updated.'])->send();
    }

    public function printDeliveryChecklist($id)
    {

        $available_deal = $this->repository->show($id);

        $this->existenceOrRightsCheckForDeal($available_deal);

        $delivery_checklist_list = (new DealCommonDependentRepository())->switchDealCommonDependentState('delivery-checklist')->filterByDealIdAsArray($id);

        if (!empty($delivery_checklist_list[0])) {
            $delivery_checklist = json_decode($delivery_checklist_list[0]['detail'], true);
            $delivery_checklist['created_at'] = $delivery_checklist_list[0]['created_at'];
            $delivery_checklist['updated_at'] = $delivery_checklist_list[0]['updated_at'];

            if (is_file(APPPATH . 'Views/templates/delivery-checklist.php')) {
                $snappy = new Pdf();
                $path = \h4cc\WKHTMLToPDF\WKHTMLToPDF::PATH;
                $snappy->setBinary($path);
                $template = view('Views/templates/delivery-checklist', $delivery_checklist);
                $this->response->setHeader('Content-Type', 'application/pdf');
                echo $snappy->getOutputFromHtml($template);
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => `Deal Checklist template couldn't be found.`])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => `Deal Checklist couldn't be found.`])->send();
        }
    }


    public function reportIndex()
    {

        if (service('authPolicy')->hasElevatedRoles()) {

            $items = 100;
            $paginate = true;

            $column = 'created_at';
            $order = 'DESC';

            $this->repository->setModel(new DealReportModel());

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

            $data = $data->index(null, $header = null, null, $query, $items, $sort, $paginate, 'deal_view_id', 'deal_listing');

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


            $reportFactory = (new ReportFactory())->getInstance(ScheduleConstant::DEAL_REPORT);
            $reportFactory->setDataFromEntityRepository($view, $_GET, $search, $column, $order, $items, $paginate)->output('php://output', strtoupper($report_type));

            $this->response->setHeader('Content-Type', $reportFactory->getContentTypeHeaderBasedOnReportType(strtoupper($report_type)));
            die;
        } elseif (!$this->request->getPost('view')) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'View Parameter Missing.'])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function downloadAttachmentZip()
    {
        $throttler = Services::throttler(false);

        if ($throttler->check('DEAL_DOWNLOAD_ZIP_' . md5(db_connect()->database), 10, DAY, 1) || service('authPolicy')->hasElevatedRoles()) {
            $data = $this->request->getPost();
            $validated = $this->validate(['current_module_id' => 'is_natural', 'selected_attachments' => 'required']);

            if ($validated) {

                $available_deal = $this->repository->show($data['current_module_id']);
                $this->existenceOrRightsCheckForDeal($available_deal);

                $complete_deal_details = $this->appendDealDependencyResponse($data['current_module_id'], $available_deal);


                $deal_attachment_aggregator = new DealAttachmentAggregator();
                $all_attachment_links = $deal_attachment_aggregator->getAllLinks([], AttachmentAggregatorConstant::DEAL, $data['current_module_id'], $data['selected_attachments'], []);

                if (!empty($complete_deal_details['inventory'])) {
                    if (is_array($complete_deal_details['inventory'])) {

                        foreach ($complete_deal_details['inventory'] as $key => $inventory) {
                            if (!empty($inventory['inventory_id'])) {
                                $inventory_purchase_aggregator = new InventoryPurchaseAggregator();
                                $all_attachment_links = $inventory_purchase_aggregator->getAllLinks(['Tenant\app\Libraries\AttachmentAggregator\InventoryCostAggregator', 'Tenant\app\Libraries\AttachmentAggregator\InventoryAttachmentAggregator'], AttachmentAggregatorConstant::INVENTORY, $inventory['inventory_id'], $data['selected_attachments'], $all_attachment_links);
                            }
                        }
                    }
                }

                $deal_attachment_aggregator->process($all_attachment_links);
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['error' => "Exceeded Daily Limit"])->send();
        }
    }

    /**
     * noteCreate This creates a new note corresponding to the deal along with an attachment.
     *
     * @param int $id This is the Deal Id for which the note is supposed to be created.
     * @return void
     */
    public function noteCreate($id)
    {

        $available_deal = $this->repository->show($id);

        $this->existenceOrRightsCheckForDeal($available_deal);

        $this->isLocked($available_deal);

        $data = $this->request->getPost();
        $validated = true;
        $response = [];

        if (!array_key_exists('attachment', $this->request->getFiles())) {
            //save details
            $response = $this->repository->saveNote($id, $data);
        } else {
            $validated = $this->validate(['attachment' => ['uploaded[attachment]', 'mime_in[attachment,application/pdf,image/jpg,image/jpeg,image/gif,image/png]', 'max_size[attachment,32000]',],]);

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
     * showNote This method accepts two paramters deal id as well as id of the note.
     * Deal Id is simply used for routing purpose.
     *
     * @param mixed $deal_id
     * @param mixed $id
     * @return void
     */
    public function showNote($deal_id, $id)
    {
        $available_deal = $this->repository->show($deal_id);

        $this->existenceOrRightsCheckForDeal($available_deal);

        $response = $this->repository->showNote($id);
        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    /**
     * allNotes This method shows the list of all the notes related to a particular customer.
     *
     * @param mixed $deal_id Deal Id.
     * @return void
     */
    public function allNotes($deal_id)
    {
        $available_deal = $this->repository->show($deal_id);

        $this->existenceOrRightsCheckForDeal($available_deal);

        $response = $this->repository->allNotes($deal_id);
        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    /**
     * updateNote This method allows the user to update the note, if he is note owner or tenant owner or superowner.
     *
     * @param int $deal_id Deal Id.
     * @param int $id Note Id to be updated.
     * @return void
     */
    public function updateNote($deal_id, $id)
    {

        $available_deal = $this->repository->show($deal_id);

        $this->existenceOrRightsCheckForDeal($available_deal);

        $this->isLocked($available_deal);

        $note = $this->repository->showNote($id);
        if (!empty($note)) {
            if (!(service('authPolicy')->hasOwnerRole() || service('authPolicy')->isSuperOwner() || service('authPolicy')->isResourceOwner($note['user_id']))) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'You do not have permission to update note.'])->send();
                exit;
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'Note could not be found.'])->send();
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
     * @param int $deal_id Deal Id.
     * @param int $id Note Id to be updated.
     * @return void
     */
    public function deleteNote($deal_id, $id)
    {
        $available_deal = $this->repository->show($deal_id);

        $this->existenceOrRightsCheckForDeal($available_deal);

        $this->isLocked($available_deal);

        $note = $this->repository->showNote($id);
        if (!empty($note)) {
            if (!(service('authPolicy')->hasOwnerRole() || service('authPolicy')->isSuperOwner() || service('authPolicy')->isResourceOwner($note['user_id']))) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'You do not have permission to delete note.'])->send();
                exit;
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'Note could not be found.'])->send();
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
     * adjustDeal This Rolls A New Deal
     *
     * @return void
     */
    public function adjustDeal()
    {
        $data = $this->request->getPost();
        $validated = $this->validate(['adjust_column' => 'required', 'request_column' => 'required',]);

        if ($validated) {
            $worksheet = new DealWorksheet($data);

            $adjustor_factory = (new AdjustorFactory())->getInstance($data['request_column'], $data['adjust_column']);
            $request_amount = $adjustor_factory->adjust($worksheet);

            $this->response->setContentType('application/json')->setJSON(['request_column' => $data['request_column'], 'adjust_column' => $data['adjust_column'], 'request_amount' => $request_amount,])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    public function _appendDealDependencyResponse(string $deal_id, array $response, string $bill_type = "credit-application"): array
    {
        return $this->appendDealDependencyResponse($deal_id, $response, $bill_type);
    }


    /**
     * existenceOrRightsCheckForDeal This function checks if the deal is present or created by
     *
     * @return void
     */
    protected function existenceOrRightsCheckForDeal($deal)
    {
        if ($deal == null || !isset($deal['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Deal couldn't be found."])->send();
            exit;
        }

        if (!$this->isCurrentUserAuthorizedToViewCurrentDeal($deal)) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "You do not have enough privelages to see the deal."])->send();
            exit;
        }
    }

    protected function isCurrentUserAuthorizedToViewCurrentDeal($deal)
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            return true;
        }

        $authorize_staff_to_view_all_deals = $this->configuration_repository->findConfigurationColumnsByKey('authorize_staff_to_view_all_deals', 'value')->value;
        $authorize_staff_to_view_all_deals = !empty($authorize_staff_to_view_all_deals);

        if ($authorize_staff_to_view_all_deals) {
            return true;
        }

        return $deal['salesperson'] == service('authPolicy')->id;
    }

    protected function importInventory($inventory, $disclosures = [], $purchase_details = [])
    {
        $import_inventory_repository = new InventoryImportRepository();
        $inventory_disclosure_repository = (new InventoryCommonDependentRepository())->switchInventoryCommonDependentState('disclosures');
        $saved_inventory = $import_inventory_repository->create($inventory);
        if (!empty($saved_inventory['inventory_id']) && !empty($disclosures)) {
            foreach ($disclosures as $key => $entity) {
                unset($entity['id']);
                $entity['inventory_id'] = $saved_inventory['inventory_id'];
                $inventory_disclosure_repository->create($entity);
            }
        }

        if (!empty($saved_inventory['inventory_id']) && !empty($purchase_details)) {
            unset($purchase_details['id']);
            $purchase_details['inventory_id'] = $saved_inventory['inventory_id'];
            (new InventoryPurchaseRepository())->create($purchase_details);
        }

        if (!empty($saved_inventory['inventory_id'])) {
            (new InventoryRepository())->updateTypesenseIndexService([$saved_inventory['inventory_id']]);
            (new InventoryRepository())->purgeWebsiteCache([$saved_inventory['inventory_id']]);
            (new InventoryRepository())->updateSearchIndex([$saved_inventory['inventory_id']]);
        }
    }

    protected function isPaymentSectionEnabledForWholeSaleDeal()
    {
        $enable_payment_section_for_wholesale_deal = $this->configuration_repository->findConfigurationColumnsByKey('enable_payment_section_for_wholesale_deal', 'value')->value;
        return !empty($enable_payment_section_for_wholesale_deal);
    }

    protected function appendDealDependencyResponse(string $deal_id, array $response, string $bill_type = "")
    {
        if (isset($response['id'])) {
            $response['customers'] = $this->repository->setModel(new DealCustomerPivotModel())->findByDealId($response['id']);
            $response['inventory'] = $this->repository->setModel(new DealInventoryPivotModel())->findByDealId($response['id']);

            $location_name = "";
            if (!empty($response['inventory'])) {
                foreach ($response['inventory'] as $key => $inventory) {
                    $updated_odometer = $inventory['odometer'];
                    $response['inventory'][$key] = (new InventoryRepository())->findSingleInventoryByStockId($inventory['stock_no']);

                    $inventory_exist = !empty($response['inventory'][$key]) ? true : false;

                    if ($inventory_exist) {
                        $response['disclosures'] = (new InventoryCommonDependentRepository())->switchInventoryCommonDependentState('disclosures')->filterByInventoryIdAsArray($response['inventory'][$key]['inventory_id']);

                        $response['inventory_inspection'] = (new InventoryCommonDependentRepository())->switchInventoryCommonDependentState('internal-inspection')->filterByInventoryIdAsArray($response['inventory'][$key]['inventory_id']);

                        if (service('authPolicy')->hasElevatedRoles()) {
                            $response['expenses'] = (new InventoryCostRepository())->findByInventoryId($response['inventory'][$key]['inventory_id']);

                            $response['purchase'] = (new InventoryPurchaseRepository())->findByInventoryId($response['inventory'][$key]['inventory_id']);
                        }
                    }

                    if ($inventory_exist) {
                        if (!empty($response['inventory'][$key]['location'])) {
                            $location_name = $response['inventory'][$key]['location'];
                        }
                    }

                    if ($inventory_exist) {
                        $response['inventory'][$key]['odometer'] = is_numeric($updated_odometer) && !empty($updated_odometer) ? $updated_odometer : $response['inventory'][$key]['odometer'];
                    }
                }
            }

            if (!empty($response['customers'])) {
                foreach ($response['customers'] as $key => $customer) {
                    $deal_customer_type = $customer['customer_type'];
                    $response['customers'][$key] = (new CustomerRepository())->show($customer['customer_id']);
                    if (isset($response['customers'][$key]['id'])) {
                        $response['customers'][$key]['customer_insurance'] = (new CustomerInsuranceRepository())->findByCustomerId($response['customers'][$key]['id']);
                    }
                    $response['customers'][$key]['deal_customer_type'] = $deal_customer_type;

                    if ($bill_type == 'credit-application' && $response['type'] == 'Financed') {
                        $customer_type = (int)$response['customers'][$key]["customer_type"];
                        $response['customers'][$key]['credit_application'] = (new CustomerCreditApplicationRepository())->switchCustomerState($customer_type)->findByCustomerId($customer['customer_id']);
                    }
                }
            }

            $response['tradein'] = (new DealTradeInRepository())->filterByDealIdAsArray($deal_id);
            if (!empty($response['tradein'])) {
                foreach ($response['tradein'] as $key => $tradein) {
                    $response['deal_tradein_disclosures'] = (new DealTradeInCommonRepository())->switchDealTradeInCommonDependentState('disclosures')->filterByTradeInAsArray($tradein['id']);
                }
            }

            $response['fees'] = $this->common_repository->switchDealCommonDependentState('fees')->filterByDealIdAsArray($deal_id);
            $response['accessories'] = $this->common_repository->switchDealCommonDependentState('accessories')->filterByDealIdAsArray($deal_id);
            $response['insurances'] = $this->common_repository->switchDealCommonDependentState('insurances')->filterByDealIdAsArray($deal_id);
            $response['payments'] = $this->common_repository->switchDealCommonDependentState('payments')->filterByDealIdAsArray($deal_id);
            $response['warranties'] = $this->common_repository->switchDealCommonDependentState('warranties')->filterByDealIdAsArray($deal_id);
            $response['guarantee'] = $this->common_repository->switchDealCommonDependentState('guarantee')->filterByDealIdAsArray($deal_id);
            $response['finance'] = $this->common_repository->switchDealCommonDependentState('finance')->filterByDealIdAsArray($deal_id);
            $response['dealbreakdown'] = $this->common_repository->switchDealCommonDependentState('dealbreakdown')->filterByDealIdAsArray($deal_id);
            $response['sale_commissions'] = $this->common_repository->switchDealCommonDependentState('sale-commissions')->filterByDealIdAsArray($deal_id);


            if (!empty($response['salesperson']) && is_numeric($response['salesperson'])) {
                $response['salesperson'] = (new TenantUserMetaDataRepository())->getUserMetaDataById($response['salesperson']);
            }

            if (!empty($response['approved_by']) && is_numeric($response['approved_by'])) {
                $response['approved_by'] = (new TenantUserMetaDataRepository())->getUserMetaDataById($response['approved_by']);
            }

            $response['location'] = (new LocationRepository())->getLocationByName($location_name);
        }

        return $response;
    }

    protected function setCalculatedValue(array $calculated_deal_breakdown, array $sale_commission): float
    {
        $amount = 0;
        $percentage = floatval($sale_commission['percentage']);
        $percentage *= 0.01;
        switch ($sale_commission['type']) {
            case "Selling Price Percentage":
                $amount = $calculated_deal_breakdown['deal_selling_price'] * $percentage;
                break;

            case "Vehicle Profit Percentage":
                $amount = $calculated_deal_breakdown['vehicle_profit'] * $percentage;
                break;

            case "Deal Profit Percentage":
                $amount = $calculated_deal_breakdown['deal_profit_cost_exclude_sale_commission'] * $percentage;
                break;

            case "Additional Income Percentage":
                $amount = $calculated_deal_breakdown['additional_income_difference'] * $percentage;
                break;

            case "Backend Income Percentage":
                $amount = $calculated_deal_breakdown['backend_income_difference'] * $percentage;
                break;
            default:
                # code...
                break;
        }

        return $amount;
    }

    /**
     * isLocked This function checks if the deal is locked
     *
     * @return void
     */
    protected function isLocked($deal)
    {
        if ($deal == null || !isset($deal['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Deal couldn't be found."])->send();
            exit;
        }

        if (intval($deal['lock']) === 1) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Deal is locked."])->send();
            exit;
        }
    }

    protected function isCurrentUserAuthorizedToViewAllDeals()
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            return true;
        }

        $authorize_staff_to_view_all_deals = $this->configuration_repository->findConfigurationColumnsByKey('authorize_staff_to_view_all_deals', 'value')->value;
        return !empty($authorize_staff_to_view_all_deals);
    }
}
