<?php

namespace Tenant\app\Controllers;

use App\Libraries\DealerTrack\DealerTrackExport;
use App\Libraries\DealerTrack\DealerTrackImport;
use App\Repositories\DealerTrackConfigurationRepository;
use Carbon\Carbon;
use Tenant\app\Repositories\CustomerCreditApplicationRepository;
use Tenant\app\Repositories\InventoryImportRepository;
use Tenant\app\Libraries\WebExtensionTransformer\DealerTrack as DealerTrackTransformer;
use Tenant\app\Repositories\ConfigurationRepository;
use Error;
use Exception;

class DealerTrackController extends DealController
{
    public function send($id): void
    {
        $response = $this->repository->show($id);
        $this->existenceOrRightsCheckForDeal($response);
        $this->checkIfDealIsSentToDT($id);
        $this->checkIfDealIdIsNonNumeric($id);

        $dealertrack_dealer_id =  $this->configuration_repository->findConfigurationColumnsByKey('dealertrack_dealer_id', 'value')->value;
        $dealertrack_dealer_confirm_id =  $this->configuration_repository->findConfigurationColumnsByKey('dealertrack_dealer_confirm_id', 'value')->value;

        $response['DMSReference'] = 'DEAL_' . $id;
        $response = $this->appendDealDependencyResponse($id, $response, 'credit-application');

        $dealertrack = new DealerTrackExport();
        $dealertrack->setDealer_id($dealertrack_dealer_id);
        $dealertrack->setDealer_confirm_id($dealertrack_dealer_confirm_id);
        $dealertrack->transform($response);
        $response = $dealertrack->send();

        $dealertrack_response = [];
        $dealertrack_response['deal_id'] = $id;
        $dealertrack_response['complete_response'] = json_encode($response);
        $dealertrack_response['application_profile_id'] = '';
        $dealertrack_response['direction'] = 'SENT';
        $dealertrack_response['status'] = 'FAILED';

        if (isset($response['errors'])) {
            $this->saveDealCommonDependency('dealertrack', $dealertrack_response);
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        } else {

            if (!empty($response['success'])) {
                $dealertrack_response['status'] = 'SUCCESS';
            }

            if (!empty($response['applicationProfileId'])) {
                $dealertrack_response['application_profile_id'] = $response['applicationProfileId'];
            }

            $this->saveDealCommonDependency('dealertrack', $dealertrack_response);
            $this->response->setContentType('application/json')->setJSON($response)->send();
        }
    }

    public function receive(): void
    {

        try {

            $encrypted_data = file_get_contents('php://input');


            $decryptedMessage = $this->decryptMessage($encrypted_data);

            var_dump($decryptedMessage);

            if (gettype($decryptedMessage) === false) {
                throw new \Exception("Inbound DealerTrack Message could not be decrypted.", 1);
            }

            $data = $decryptedMessage;

            $payload = json_decode($data, true);

            $deal_id = null;

            $dealertrack_response = [];

            $dealertrack_response['complete_response'] = json_encode($payload);
            $dealertrack_response['application_profile_id'] = '';
            $dealertrack_response['direction'] = 'RECEIVED';
            $dealertrack_response['status'] = 'SUCCESS';

            if (is_array($payload)) {

                if (array_key_exists('Header', $payload)) {

                    $dealer_id = isset($payload['Header']['DealerId']) ? $payload['Header']['DealerId'] : "";
                    $dealer_confirm_id = isset($payload['Header']['DealerConfirmId']) ? $payload['Header']['DealerConfirmId'] : "";

                    if (empty($dealer_id) || empty($dealer_confirm_id)) {
                        throw new \Exception("No Dealer ID", 1);
                    }

                    $database_config =  (new DealerTrackConfigurationRepository())->getByDealerID($dealer_id);

                    if (empty($database_config)) {
                        throw new \Exception("No Database Found");
                    }

                    $db = \Config\Database::connect();
                    $db->setDatabase($database_config['database']);

                    if (str_contains($payload['Header']['DMSReference'], 'DEAL')) {
                        $deal_id = str_replace('DEAL_', '', $payload['Header']['DMSReference']);
                        $deal_id = is_numeric($deal_id) ? $deal_id : null;
                    }

                    $application_profile_id = isset($payload['Header']['ProfileID']) ? $payload['Header']['ProfileID'] : "";
                    $dealertrack_response['application_profile_id'] = $application_profile_id;
                    $delivery_date = NULL;

                    if (isset($payload['Worksheet']['PurchaseDetails']['DeliveryDate'])) {
                        if (strtotime($payload['Worksheet']['PurchaseDetails']['DeliveryDate']) !== false) {
                            $delivery_date_object = Carbon::parse($payload['Worksheet']['PurchaseDetails']['DeliveryDate']);
                            $delivery_date = $delivery_date_object->format('m/d/Y');
                        }
                    }


                    if (empty($deal_id)) {
                        $deal_entity = [
                            'type' => 'Financed',
                            'retail_or_wholesale_flag' => 0,
                            'status' => 'Quote',
                            'date' => Carbon::now()->format('m/d/Y'),
                            'disclosure' => '',
                            'brand' => '',
                            'certified' => '',
                            'salesperson' => ''
                        ];

                        if (!empty($delivery_date)) {
                            $deal_entity['delivery_date_time'] = $delivery_date;
                        }

                        $deal = $this->repository->create($deal_entity);

                        if (!empty($deal['deal_id'])) {
                            $deal_id = $deal['deal_id'];
                        }
                    }

                    if (!empty($deal_id)) {

                        $payload['deal_id'] = $deal_id;

                        $dealertrack = new DealerTrackImport($payload);
                        $dealertrack->transform();

                        $transformed_data = $dealertrack->getTransformedData();

                        // var_dump($transformed_data);
                        // exit;

                        $stock_no = !empty($transformed_data['inventory']['stock_no']) ? $transformed_data['inventory']['stock_no'] : '';
                        $this->importDTInventory($deal_id, $stock_no, $transformed_data['inventory'], $transformed_data['has_inventory']);

                        $transformed_data['primary_customer'] = isset($transformed_data['primary_customer']) ? $transformed_data['primary_customer'] : [];
                        $transformed_data['primary_customer_credit_application'] =
                            isset($transformed_data['primary_customer_credit_application']) ? $transformed_data['primary_customer_credit_application'] : [];
                        $transformed_data['co_applicant_customer'] = isset($transformed_data['co_applicant_customer']) ? $transformed_data['co_applicant_customer'] : [];
                        $transformed_data['co_applicant_customer_credit_application'] =
                            isset($transformed_data['co_applicant_customer_credit_application']) ? $transformed_data['co_applicant_customer_credit_application'] : [];


                        $this->importDTCustomers(
                            $deal_id,
                            $transformed_data['has_primary_customer'],
                            $transformed_data['has_co_applicant_customer'],
                            $transformed_data['primary_customer'],
                            $transformed_data['primary_customer_credit_application'],
                            $transformed_data['co_applicant_customer'],
                            $transformed_data['co_applicant_customer_credit_application']
                        );


                        $this->purgeDealDependencies($deal_id);

                        $this->saveUpdatedDealDependencies($transformed_data);
                    }
                }
            }

            if (!empty($deal_id)) {
                $dealertrack_response['deal_id'] = $deal_id;
                $this->saveDealCommonDependency('dealertrack', $dealertrack_response);

                $this->notifyDealChange($deal_id);
            }
        } catch (\Exception $ex) {
            log_message('info', $ex->getMessage());
            var_dump($ex->getMessage());
        }
    }

    public function configurationUpdate(): void
    {

        $validated = $this->validate([
            'dealertrack_dealer_id'  => [
                "label" => 'Dealer ID',
                "rules" => 'permit_empty|alpha_numeric_punct'
            ],
            'dealertrack_dealer_confirm_id'  => [
                "label" => 'Dealer Confirm ID',
                "rules" => 'permit_empty|alpha_numeric_punct'
            ]

        ]);

        if ($validated) {

            $data = $this->request->getPost();
            $database = db_connect()->database;

            $response = $this->configuration_repository->setConfiguration($data);

            $db = \Config\Database::connect();
            $db->setDatabase(getenv('database.default.database'));
            (new DealerTrackConfigurationRepository())
                ->createOrUpdate([
                    'database' => $database,
                    'dealer_id' => $data['dealertrack_dealer_id'],
                    'dealer_confirm_id' => $data['dealertrack_dealer_confirm_id']
                ]);

            if (isset($response['success'])) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }


    public function webExtension($source)
    {
        $url_params  = $this->request->getGet();
        $extension_crawler_token = (new ConfigurationRepository())->findConfigurationColumnsByKeyAsArray('extension_crawler_token', 'value');
        $data = $this->request->getBody();

        if (!empty($url_params['token']) && $url_params['token'] === $extension_crawler_token['value']) {
            try {
                $payload  = json_decode($data, true);

                if (empty($payload)) {
                    throw new Exception('Unable to parse the data.');
                }

                if (empty($payload['application_id'])) {
                    throw new Exception('Application ID not found.');
                }

                $deal_id = null;

                $dealertrack_response = [];

                $dealertrack_response['complete_response'] = json_encode($payload);
                $dealertrack_response['application_profile_id'] = '';
                $dealertrack_response['direction'] = 'RECEIVED';
                $dealertrack_response['status'] = 'SUCCESS';

                if (is_array($payload)) {
                    $application_profile_id = isset($payload['application_id']) ? $payload['application_id'] : "";
                    $dealertrack_response['application_profile_id'] = $application_profile_id;

                    $deal_id = $this->findDealForApplicationID($application_profile_id);
                    $delivery_date = NULL;

                    if (!empty($payload['worksheet'])) {

                        if (!empty($payload['worksheet']['purchase_details'])) {
                            $purchase_details  = $payload['worksheet']['purchase_details'];
                            if (
                                !empty($purchase_details['delivery_date_mm']) &&
                                !empty($purchase_details['delivery_date_dd'])
                                && !empty($purchase_details['delivery_date_yyyy'])
                            ) {

                                if (
                                    is_numeric($purchase_details['delivery_date_mm']) &&
                                    is_numeric($purchase_details['delivery_date_dd'])
                                    && is_numeric($purchase_details['delivery_date_yyyy'])
                                ) {
                                    $delivery_date_object = Carbon::create(
                                        $purchase_details['delivery_date_yyyy'],
                                        $purchase_details['delivery_date_mm'],
                                        $purchase_details['delivery_date_dd']
                                    );

                                    $delivery_date = $delivery_date_object->format('m/d/Y');
                                }
                            }
                        }
                    }

                    if (empty($deal_id)) {

                        $deal_entity = [
                            'type' => 'Financed',
                            'retail_or_wholesale_flag' => 0,
                            'status' => 'Quote',
                            'date' => Carbon::now()->format('m/d/Y'),
                            'disclosure' => '',
                            'brand' => '',
                            'certified' => '',
                            'salesperson' => ''
                        ];

                        if (!empty($delivery_date)) {
                            $deal_entity['delivery_date_time'] = $delivery_date;
                        }


                        $deal = $this->repository->create($deal_entity);

                        $deal_id = $deal['deal_id'];
                    }

                    if (!empty($deal_id)) {
                        $payload['deal_id'] = $deal_id;

                        $dealertrack = new DealerTrackTransformer($payload);
                        $dealertrack->transform();

                        $transformed_data = $dealertrack->getTransformedData();
                        $stock_no = !empty($transformed_data['inventory']['stock_no']) ? $transformed_data['inventory']['stock_no'] : '';
                        $this->importDTInventory($deal_id, $stock_no, $transformed_data['inventory'], $transformed_data['has_inventory']);

                        $transformed_data['primary_customer'] = isset($transformed_data['primary_customer']) ? $transformed_data['primary_customer'] : [];
                        $transformed_data['primary_customer_credit_application'] =
                            isset($transformed_data['primary_customer_credit_application']) ? $transformed_data['primary_customer_credit_application'] : [];
                        $transformed_data['co_applicant_customer'] = isset($transformed_data['co_applicant_customer']) ? $transformed_data['co_applicant_customer'] : [];
                        $transformed_data['co_applicant_customer_credit_application'] =
                            isset($transformed_data['co_applicant_customer_credit_application']) ? $transformed_data['co_applicant_customer_credit_application'] : [];


                        $this->importDTCustomers(
                            $deal_id,
                            $transformed_data['has_primary_customer'],
                            $transformed_data['has_co_applicant_customer'],
                            $transformed_data['primary_customer'],
                            $transformed_data['primary_customer_credit_application'],
                            $transformed_data['co_applicant_customer'],
                            $transformed_data['co_applicant_customer_credit_application']
                        );

                        $this->purgeDealDependencies($deal_id);
                        $this->saveUpdatedDealDependencies($transformed_data);
                    }
                }

                if (!empty($deal_id)) {
                    $dealertrack_response['deal_id'] = $deal_id;
                    $this->saveDealCommonDependency('dealertrack', $dealertrack_response);
                    $this->notifyDealChange($deal_id);
                }

                if (isset($dealertrack_response['complete_response'])) {
                    unset($dealertrack_response['complete_response']);
                }

                $this->response->setContentType('application/json')->setJSON($dealertrack_response)->send();
            } catch (\Exception $ex) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => $ex->getMessage()])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('401')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    private function saveUpdatedDealDependencies($updated_payload): void
    {
        if (array_key_exists('payments', $updated_payload)) {
            $this->saveDealCommonDependency('payments', $updated_payload['payments']);
        }

        if (array_key_exists('fees', $updated_payload)) {
            foreach ($updated_payload['fees'] as $fee) {
                $this->saveDealCommonDependency('fees', $fee);
            }
        }

        if (array_key_exists('finance', $updated_payload)) {
            $this->saveDealCommonDependency('finance', $updated_payload['finance']);
        }

        if (array_key_exists('dealbreakdown', $updated_payload)) {
            $this->saveDealCommonDependency('dealbreakdown', $updated_payload['dealbreakdown']);
        }

        if (array_key_exists('tradein', $updated_payload)) {
            $this->saveDealCommonDependency('tradein', $updated_payload['tradein']);
        }

        if (array_key_exists('warranties', $updated_payload)) {
            foreach ($updated_payload['warranties'] as $warranty) {
                $this->saveDealCommonDependency('warranties', $warranty);
            }
        }

        if (array_key_exists('insurances', $updated_payload)) {
            foreach ($updated_payload['insurances'] as $insurance) {
                $this->saveDealCommonDependency('insurances', $insurance);
            }
        }
    }

    private function purgeDealDependencies($deal_id): void
    {
        $this->common_repository->switchDealCommonDependentStateAndPurge('payments', $deal_id);
        $this->common_repository->switchDealCommonDependentStateAndPurge('fees', $deal_id);
        $this->common_repository->switchDealCommonDependentStateAndPurge('accessories', $deal_id);
        $this->common_repository->switchDealCommonDependentStateAndPurge('finance', $deal_id);
        $this->common_repository->switchDealCommonDependentStateAndPurge('dealbreakdown', $deal_id);
        $this->common_repository->switchDealCommonDependentStateAndPurge('warranties', $deal_id);
        $this->common_repository->switchDealCommonDependentStateAndPurge('insurances', $deal_id);
        $this->common_repository->switchDealCommonDependentStateAndPurge('guarantee', $deal_id);
        $this->common_repository->switchDealCommonDependentStateAndPurge('tradein', $deal_id);
    }

    private function importDTCustomers(
        $deal_id,
        $has_primary_customer,
        $has_co_applicant_customer,
        $primary_customer = [],
        $primary_customer_credit_application = [],
        $co_applicant_customer = [],
        $co_applicant_customer_credit_application = []
    ): void {
        $is_customer_attached = $this->doesDealDependencyExist('deal-customer', $deal_id);

        if (!$is_customer_attached && $has_primary_customer) {
            $primary_customer_id = $this->customer_repository->saveWithOutValidation($primary_customer);
            if (!empty($primary_customer_id)) {
                $primary_customer_credit_application['customer_id'] = $primary_customer_id;
                (new CustomerCreditApplicationRepository())->switchCustomerState(0)->create($primary_customer_credit_application);
                $this->saveDealCommonDependency('deal-customer', ['deal_id' => $deal_id, 'customer_id' => $primary_customer_id, 'customer_type' => 'primary_customer']);
            }
        }

        if (!$is_customer_attached && $has_co_applicant_customer) {
            $co_applicant_customer_id = $this->customer_repository->saveWithOutValidation($co_applicant_customer);
            if (!empty($co_applicant_customer_id)) {
                $co_applicant_customer_credit_application['customer_id'] = $co_applicant_customer_id;
                (new CustomerCreditApplicationRepository())->switchCustomerState(0)->create($co_applicant_customer_credit_application);
                $this->saveDealCommonDependency('deal-customer', ['deal_id' => $deal_id, 'customer_id' => $co_applicant_customer_id, 'customer_type' => 'co-buyer']);
            }
        }
    }

    private function importDTInventory($deal_id, $stock_no = '', $inventory_entity = [], $has_inventory = false): void
    {
        $inventory_exist = true;
        $inventory = null;

        $is_inventory_attached = $this->doesDealDependencyExist('deal-inventory', $deal_id);
        if (!$is_inventory_attached && $has_inventory) {
            $inventory_exist = $this->checkIfInventoryExistForStockNumber($stock_no);

            if ($inventory_exist) {
                $inventory = $this->inventory_repository->findSingleInventoryByStockId($stock_no);
            } else {
                $inventory_response = (new InventoryImportRepository())->create($inventory_entity);
                if (!empty($inventory_response['inventory_id'])) {
                    $inventory = (new InventoryImportRepository())->show($inventory_response['inventory_id']);
                }
            }

            if (!empty($inventory['stock_no'])) {
                $this->common_repository->switchDealCommonDependentStateAndPurge('deal-inventory', $deal_id);
                $this->saveDealCommonDependency('deal-inventory', ['deal_id' => $deal_id, 'stock_no' => $inventory['stock_no'], 'odometer' => $inventory['odometer']]);
            }
        }
    }

    private function saveDealCommonDependency(string $deal_common_type, array $data): void
    {
        $this->common_repository->switchDealCommonDependentState($deal_common_type)->create($data);
    }

    private function checkIfInventoryExistForStockNumber(String $stock_no = ''): bool
    {
        if (empty($stock_no)) {
            return false;
        }

        $inventory = $this->inventory_repository->findSingleInventoryByStockId($stock_no);

        if (!empty($inventory)) {
            return true;
        }

        return false;
    }

    private function doesDealDependencyExist(String $module, String $deal_id)
    {
        $module_dependencies = $this->common_repository->switchDealCommonDependentState($module)->filterByDealIdAsArray($deal_id);
        return empty($module_dependencies) ? false : true;
    }

    private function notifyDealChange($id)
    {
        $pushFactory = service('channelService')->getInstance("internal")['push'];
        $pushFactory->setSubject("DEAL:UPDATE:" . $id);
        $pushFactory->setContent('');

        $db = \Config\Database::connect();
        $app_builder = $db->table('app')->get()->getRowArray();
        $app_name = $app_builder['name'];
        $pushFactory->setRecipient($app_name, "*");
        $pushFactory->send();
    }

    private function checkIfDealIsSentToDT($deal_id)
    {
        $dealertrack_logs = $this->common_repository->switchDealCommonDependentState('dealertrack')->filterByDealIdAsArray($deal_id);
        $sent_dealertrack_logs = array_filter($dealertrack_logs, function ($log) {
            return $log['direction'] === 'SENT';
        });

        if (count($sent_dealertrack_logs) > 0) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'Deal has already been sent to dealertrack.'])->send();
            exit;
        }
    }

    private function checkIfDealIdIsNonNumeric($deal_id)
    {
        $dealertrack_logs = $this->common_repository->switchDealCommonDependentState('dealertrack')->filterByDealIdAsArray($deal_id);

        $web_dealertrack_logs = array_filter($dealertrack_logs, function ($log) {
            return !is_numeric($log['application_profile_id']);
        });

        if (count($web_dealertrack_logs) > 0) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'We are unable to send this deal to Dealertrack.'])->send();
            exit;
        }
    }

    private function findDealForApplicationID($application_profile_id)
    {
        $dealertrack_logs = $this->common_repository->switchDealCommonDependentState('dealertrack')->filterData("application_profile_id", $application_profile_id)->index();
        if (!empty($dealertrack_logs['data_list'])) {
            if (!empty($dealertrack_logs['data_list'][0])) {
                return $dealertrack_logs['data_list'][0]['deal_id'];
            }
        }

        return null;
    }

    private function decryptMessage(String $encrypted_data)
    {
        try {
            $gpg = new \gnupg();
            $gpg->seterrormode(\gnupg::ERROR_EXCEPTION);
            $privateKeyFingerprint = getenv('dealertrack.dms.transfer.fingerprint');
            $gpg->adddecryptkey($privateKeyFingerprint, '');
            $decryptedMessage = $gpg->decrypt($encrypted_data);

            return $decryptedMessage;
        } catch (\Exception $ex) {
            log_message('info', $encrypted_data);
            log_message('info', $ex->getMessage());
            var_dump($ex->getMessage());
            return false;
        }
    }
}
