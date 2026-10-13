<?php

namespace Tenant\app\Controllers;

use App\Composites\LeadTemplateFormComposite;
use App\Helpers\Utility;
use Carbon\Carbon;
use CodeIgniter\Cache\CacheFactory;
use Config\NonPersistantCache;
use Tenant\app\Libraries\Calculator\WorksheetCalculator;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\CostSettingRepository;
use Tenant\app\Repositories\CostVariationMatrixRepository;
use Tenant\app\Repositories\CustomerCreditApplicationRepository;
use Tenant\app\Repositories\CustomerRepository;
use Tenant\app\Repositories\DealCommonDependentRepository;
use Tenant\app\Repositories\DealRepository;
use Tenant\app\Repositories\InventoryCommonDependentRepository;
use Tenant\app\Repositories\InventoryRepository;
use Tenant\app\Repositories\TenantUserMetaDataRepository;

class DigitalRetailingController extends \App\Controllers\BaseController
{


    /**
     * inventory_repository
     *
     * @var InventoryRepository
     */
    private $inventory_repository;

    /**
     * configuration_repository
     *
     * @var ConfigurationRepository
     */
    private $configuration_repository;

    /**
     * inventory_dependency_repository
     *
     * @var InventoryCommonDependentRepository
     */
    private $inventory_dependency_repository;

    /**
     * cost_settings_repository
     *
     * @var CostSettingRepository
     */
    private $cost_settings_repository;

    /**
     * cost_variation_matrix_repository
     *
     * @var CostVariationMatrixRepository
     */
    private $cost_variation_matrix_repository;

    /**
     * worksheet_calculator
     *
     * @var WorksheetCalculator
     */
    private $worksheet_calculator;

    /**
     * customer_repository
     *
     * @var CustomerRepository
     */
    private $customer_repository;

    private $deal_repository;

    /**
     * deal_common_repository
     *
     * @var DealCommonDependentRepository
     */
    private $deal_common_dependent_repository;

    /**
     * inventory_common_repository
     *
     * @var InventoryCommonDependentRepository
     */
    private $inventory_common_repository;

    /**
     * customer_credit_application_repository
     *
     * @var CustomerCreditApplicationRepository
     */
    private $customer_credit_application_repository;

    public function __construct()
    {
        $this->inventory_repository = new InventoryRepository();
        $this->configuration_repository = new ConfigurationRepository();
        $this->inventory_dependency_repository = new InventoryCommonDependentRepository();
        $this->cost_settings_repository = new CostSettingRepository();
        $this->cost_variation_matrix_repository = new CostVariationMatrixRepository();
        $this->worksheet_calculator = new WorksheetCalculator();
        $this->customer_repository = new CustomerRepository();
        $this->deal_repository = new DealRepository();
        $this->deal_common_dependent_repository = new DealCommonDependentRepository();
        $this->inventory_common_repository = new InventoryCommonDependentRepository();
        $this->customer_credit_application_repository = new CustomerCreditApplicationRepository();
    }
    public function template(string $inventory_stock_no)
    {
        $inventory = $this->inventory_repository->findSingleInventoryByStockId($inventory_stock_no);
        $default_finance_terms = $this->configuration_repository->findConfigurationColumnsByKey('digital_retailing_default_finance_terms')->value;
        $default_finance_terms = json_decode($default_finance_terms, true);

        $response = $inventory;

        if (empty($inventory)) {
            $this->response->setContentType('application/json')->setStatusCode('400')
                ->setJSON(['errors' => 'Inventory could not be found for stock no ' . $inventory_stock_no])->send()->setHeader('Access-Control-Allow-Origin', '*');
        } else {
            if (!$this->checkIfInventoryIsInStock($inventory)) {
                $this->response->setContentType('application/json')->setStatusCode('400')
                    ->setJSON(['errors' => 'Stock no ' . $inventory_stock_no . ' is not instock.'])->send()->setHeader('Access-Control-Allow-Origin', '*');
            } else {
                $response = $this->inventory_repository->replacePlaceholders($inventory);
                $inventory_default_finance_details = Utility::getCompleteFinancingDetails($inventory, 0, 0);
                $response = array_merge($response, $inventory_default_finance_details);
                $response['gallery'] = $this->inventory_repository->onlyActiveImagesWithStaticImages($inventory['inventory_id']);
                $response['finance_terms'] = $default_finance_terms;

                $price = (int) $inventory["price"];
                $special_price =  (int) $inventory["special_price"];
                $inventory['selling_price'] = $inventory["special_price_on"] == "1" ? $special_price : $price;

                $inventory_custom_digitial_retail_settings = $this->inventory_dependency_repository->switchInventoryCommonDependentState('digital-retailing')->filterByInventoryIdAsArray($inventory['inventory_id']);
                $response['finance_terms'] = $this->setInventoryFinanceTerms($default_finance_terms, $inventory_custom_digitial_retail_settings);
                // $response['fees'] = $this->fetchCostSettings($inventory, 'fees', 'FEE_SETTING');
                $response['warranties'] = $this->fetchCostSettings($inventory, 'warranty', 'WARRANTY_SETTING');
                $response['guarantees'] = $this->fetchCostSettings($inventory, 'guarantee', '');
                $response['insurances'] = $this->fetchCostSettings($inventory, 'insurances', 'INSURANCE_SETTING');
                $response['fees'] = [];

                $response['salespersons'] = (new TenantUserMetaDataRepository())->findAllUsers(["user_metadata.user_id", "user_metadata.name"]);

                $this->response->setContentType('application/json')->setJSON($response)->send();
            }
        }
    }

    public function getTransient()
    {

        $data = $this->request->getPost();

        $validated = $this->validate([
            'stock_no' => "required",
            'uuid' => "required"
        ]);

        if ($validated) {
            $data = $this->getNonPersistantData($data['stock_no'], $data['uuid']);

            $response =  $this->prepareUpdatedTemplateUsingTransientData($data);

            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send()->setHeader('Access-Control-Allow-Origin', '*');
        }
    }

    public function saveTransient()
    {
        //Validations
        $validated = $this->validate([
            'stock_no' => "required",
            'uuid' => "required",
            'downpayment' => 'required|decimal',
            'deal_type' => 'required',
            'selected_insurances' => 'required|valid_json',
            'selected_warranty' => 'required|valid_json',
            'finance' => 'permit_empty|is_natural',
            'salesperson' => 'permit_empty|is_natural',
        ]);

        if ($validated) {

            $data = $this->request->getPost();

            $response =  $this->prepareUpdatedTemplateUsingTransientData($data);

            $this->prepareAndSaveNonPersistantData($data['stock_no'], $data['uuid'], $data);

            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send()->setHeader('Access-Control-Allow-Origin', '*');
        }
    }

    public function createQuote()
    {

        $data = $this->request->getPost();

        $validated = $this->validate([
            'stock_no' => "required",
            'uuid' => "required",
            'first_name'  => [
                "label" => 'First Name',
                "rules" => 'required|alpha_numeric_punct'
            ],
            'last_name'  => [
                "label" => 'Last Name',
                "rules" => 'required|alpha_numeric_punct'
            ],
            'phone'  => [
                "label" => 'Phone',
                "rules" => 'required|is_natural|exact_length[10]'
            ],
            'email'  => [
                "label" => 'Email',
                "rules" => 'required|valid_email'
            ]
        ]);

        if ($validated) {

            $response = $this->customer_repository->create($data);

            if (isset($response['success'])) {
                $deal = $this->saveQuoteBasedOnTransientData($data, $response['customer_id']);
                $response_object = ['customer_id' => $response['customer_id'], 'deal_id' => $deal['deal_id'], 'database' =>  db_connect()->database];

                $response["credit_application_hash"] = Utility::encryptString(json_encode($response_object), getenv("password.encryption"));
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setHeader('Access-Control-Allow-Origin', '*')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setHeader('Access-Control-Allow-Origin', '*')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    public function renderCreditApplicationTemplate()
    {
        $template_id = 5;
        $default_template_id = $this->configuration_repository->findConfigurationColumnsByKey('default_digital_retailing_credit_application_template')->value;
        if (!empty($default_template_id)) {
            $template_id = $default_template_id;
        }

        $leadTemplateFormComposite = new LeadTemplateFormComposite();
        $form_data = $leadTemplateFormComposite->getElements($template_id);

        if (!empty($form_data['dom_elements'])) {
            $this->response->setContentType('application/json')->setJSON($form_data['dom_elements'])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => 'Error in rendering the credit application page.'])->send()->setHeader('Access-Control-Allow-Origin', '*');
        }
    }


    public function saveCreditApplication(string $encrypted_sting)
    {
        try {
            $template_id = 5;
            $default_template_id = $this->configuration_repository->findConfigurationColumnsByKey('default_digital_retailing_credit_application_template')->value;
            if (!empty($default_template_id)) {
                $template_id = $default_template_id;
            }

            $stringified_object = Utility::decryptString($encrypted_sting, getenv("password.encryption"));
            $entity_object = json_decode($stringified_object, true);

            $customer_id = $entity_object['customer_id'];
            $deal_id = $entity_object['deal_id'];

            $data = $this->request->getPost();

            $leadTemplateFormComposite = new LeadTemplateFormComposite();
            $form_data = $leadTemplateFormComposite->getElements($template_id);

            $validation = \Config\Services::validation();
            if ($validation->setRules($leadTemplateFormComposite->validateElements())->run($data)) {

                //Match fields in the customer table.

                $data['customer_id'] = $customer_id;

                $this->updateCustomerInformation($data);
                $this->savePrimaryIndividualCreditApplication($data);
                $has_co_applicant = false;

                if (isset($data['coapplicant'])) {
                    if ($data['coapplicant'] == "Yes") {
                        $has_co_applicant = true;
                    }
                }

                if ($has_co_applicant) {
                    $this->saveCoApplicantDetails($data, $deal_id);
                }



                //Save anything else as a json in the additional_notes.

                $this->response->setContentType('application/json')->setJSON(['success' => 'Credit Application saved.'])->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($validation->getErrors())->send()->setHeader('Access-Control-Allow-Origin', '*');
            }
        } catch (\Exception $ex) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => $ex->getMessage()])->send()->setHeader('Access-Control-Allow-Origin', '*');
        }
    }

    private function saveQuoteBasedOnTransientData(array $data, $customer_id)
    {
        $data = $this->getNonPersistantData($data['stock_no'], $data['uuid']);
        $inventory = $this->inventory_repository->findSingleInventoryByStockId($data['stock_no']);
        $data['disclosure'] = $data['brand'] = $data['certified'] = '';

        if (!empty($inventory)) {
            $disclosures = $this->inventory_common_repository->filterByInventoryIdAsArray($inventory['inventory_id']);

            foreach ($disclosures as $disclosure) {
                switch ($disclosure['question']) {
                    case 'Complete Disclosure':
                        $data['disclosure'] = $disclosure['answer'];
                        break;

                    case 'Brand':
                        $data['brand'] = $disclosure['answer'];
                        break;

                    case 'Certified':
                        $data['certified'] = $disclosure['answer'];
                        break;
                    default:
                        # code...
                        break;
                }
            }
        }

        $quote_entity =  $this->prepareUpdatedTemplateUsingTransientData($data, false);
        $deal = $this->deal_repository->create([
            'type' => $quote_entity['deal_type'],
            'retail_or_wholesale_flag' => 0,
            'status' => 'Quote',
            'date' => Carbon::now()->format('m/d/Y'),
            'disclosure' => $data['disclosure'],
            'brand' => $data['brand'],
            'certified' => $data['certified'],
            'salesperson' => $data['salesperson']

        ]);

        if (!empty($deal['deal_id'])) {

            $this->saveDealCommonDependency('deal-customer', ['deal_id' => $deal['deal_id'], 'customer_id' => $customer_id, 'customer_type' => 'primary_customer']);
            $this->saveDealCommonDependency('deal-inventory', ['deal_id' => $deal['deal_id'], 'stock_no' => $data['stock_no']]);

            foreach ($quote_entity['fees'] as $fee) {
                unset($fee['id']);
                $fee['deal_id'] = $deal['deal_id'];
                $this->saveDealCommonDependency('fees', $fee);
            }

            $deal_breakdown_entity = [];
            $deal_breakdown_entity['deal_id'] = $deal['deal_id'];
            $deal_breakdown_entity['purchase_tax_type'] = $quote_entity['purchase_tax_type'];
            $deal_breakdown_entity['purchase_tax_percentage'] = $quote_entity['purchase_tax_percentage'];
            $deal_breakdown_entity['tax_on_purchase_price'] = $quote_entity['tax_on_purchase_price'];
            $deal_breakdown_entity['purchase_tax_type_secondary'] = $quote_entity['purchase_tax_type_secondary'];
            $deal_breakdown_entity['tax_on_purchase_price_secondary'] = $quote_entity['tax_on_purchase_price_secondary'];
            $deal_breakdown_entity['purchase_tax_percentage_secondary'] = $quote_entity['purchase_tax_percentage_secondary'];
            $deal_breakdown_entity['purchase_price'] = $quote_entity['selling_price_numeric'];
            $deal_breakdown_entity['subtotal'] = $quote_entity['subtotal'];
            $deal_breakdown_entity['lien_payout'] = $quote_entity['lien_payout'];
            $deal_breakdown_entity['insurance_tax'] = $quote_entity['insurance_tax_amount_total'];
            $deal_breakdown_entity['total_balance_due'] = $quote_entity['total_balance_due'];
            $deal_breakdown_entity['trade_equity'] = $quote_entity['trade_equity'];
            $deal_breakdown_entity['trade_value'] = $quote_entity['trade_value'];
            $deal_breakdown_entity['tax_on_purchase_total'] = $quote_entity['tax_on_purchase_total'];
            $deal_breakdown_entity['total_tax_amount'] = $quote_entity['total_taxes'];
            $deal_breakdown_entity['net_difference'] = $quote_entity['net_difference'];
            $deal_breakdown_entity['secondary_subtotal'] = $quote_entity['secondary_subtotal'];

            $this->saveDealCommonDependency('dealbreakdown', $deal_breakdown_entity);


            if ($quote_entity['deal_type'] === "Financed") {
                $finance_entity = [];
                $finance_entity['deal_id'] = $deal['deal_id'];
                $finance_entity['total_amount'] = $quote_entity['finance_amount'];
                $finance_entity['interest_rate'] = $quote_entity['interest_rate'];
                $finance_entity['term'] = $quote_entity['term'];
                $finance_entity['payment_frequency'] = $quote_entity['payment_frequency'];
                $finance_entity['interest_amount'] = $quote_entity['total_interest'];
                $finance_entity['emi_amount'] = $quote_entity['emi_amount'];
                $finance_entity['interest_rate_type'] = 'Fixed';

                $this->saveDealCommonDependency('finance', $finance_entity);
            }

            $this->includeMandatoryEntityAndSaveInQuote(json_encode($quote_entity['selected_guarantee']), $quote_entity['guarantees'], 'guarantee', $deal['deal_id']);
            $this->includeMandatoryEntityAndSaveInQuote(json_encode($quote_entity['selected_insurances']), $quote_entity['insurances'], 'insurances', $deal['deal_id']);
            $this->includeMandatoryEntityAndSaveInQuote(json_encode($quote_entity['selected_warranty']), $quote_entity['warranties'], 'warranties', $deal['deal_id']);
        }

        $downpayment = floatval($quote_entity['downpayment']);

        if (!empty($downpayment)) {
            $save = $this->saveDealCommonDependency('payments', ['description' => 'Digital Quote', 'is_downpayment_or_deposit' => 0, 'amount' => $downpayment, 'deal_id' => $deal['deal_id']]);
        }

        return $deal;
    }

    private function includeMandatoryEntityAndSaveInQuote(string $entity_json, $entities, $deal_common_type, $deal_id)
    {
        try {
            $selected_entities = json_decode($entity_json, true);

            foreach ($entities as $entity) {
                if (in_array(intval($entity['id']), $selected_entities) || $entity['is_mandatory'] == "1") {
                    unset($entity['id']);
                    $entity['deal_id'] = $deal_id;
                    $this->saveDealCommonDependency($deal_common_type, $entity);
                }
            }
        } catch (\Exception $ex) {
        }
    }

    private function saveDealCommonDependency(string $deal_common_type, array $data)
    {
        $this->deal_common_dependent_repository->switchDealCommonDependentState($deal_common_type)->create($data);
    }

    private function prepareUpdatedTemplateUsingTransientData($data, bool $unset_cost = true)
    {
        $does_balance_cost_includes_interest = $this->configuration_repository->findConfigurationColumnsByKey('total_balance_cost_includes_interest')->value;
        $does_balance_cost_includes_interest = $does_balance_cost_includes_interest == "1" ? true : false;

        $default_finance_terms = $this->configuration_repository->findConfigurationColumnsByKey('digital_retailing_default_finance_terms')->value;
        $default_finance_terms = json_decode($default_finance_terms, true);

        $response = $data;

        $inventory = $this->inventory_repository->findSingleInventoryByStockId($data['stock_no']);

        unset($inventory['downpayment']);
        unset($inventory['interest_rate']);
        unset($inventory['term']);
        unset($inventory['payment_frequency']);

        $is_financed = $data['deal_type'] === "Cash" ? false : true;

        $finance_term_index = empty($data['finance']) ? 0 : intval($data['finance']) - 1;

        $data['term'] = $data['interest_rate'] = 0;
        $data['payment_frequency'] = 'Monthly';

        if (!empty($default_finance_terms[$finance_term_index])) {
            $term = is_numeric($default_finance_terms[$finance_term_index]['term']) ? intval($default_finance_terms[$finance_term_index]['term']) : 0;
            $interest_rate = is_numeric($default_finance_terms[$finance_term_index]['interest_rate']) ? floatval($default_finance_terms[$finance_term_index]['interest_rate']) : 0;
            $payment_frequency = empty($default_finance_terms[$finance_term_index]['frequency']) ? 'Monthly' : $default_finance_terms[$finance_term_index]['frequency'];
        }

        $downpayment = is_numeric($data['downpayment']) ? floatval($data['downpayment']) : 0;

        $response = array_merge($response, $inventory);

        $response = $this->prepareResponseUsingTransientData($inventory, $data, $response, $is_financed, $term, $interest_rate, $payment_frequency, $unset_cost);

        $price = (int) $inventory["price"];
        $special_price =  (int) $inventory["special_price"];
        $selling_price = $inventory["special_price_on"] == "1" ? $special_price : $price;
        $selling_price = floatval($selling_price);

        $warranty_calculation = $this->convertJSONAndCalculateAmountAndTaxes($data['selected_warranty'], $response['warranties']);
        $insurance_calculation = $this->convertJSONAndCalculateAmountAndTaxes($data['selected_insurances'], $response['insurances']);

        $worksheet = $this->buildWorksheet(
            $selling_price,
            $is_financed,
            $response['fees'],
            $warranty_calculation['total_amount'],
            $warranty_calculation['tax_amount_total'],
            $insurance_calculation['total_amount'],
            $insurance_calculation['tax_amount_total'],
            $term,
            $interest_rate,
            $downpayment,
            $payment_frequency,
            $does_balance_cost_includes_interest
        );

        $response = array_merge($response, $worksheet);

        return $response;
    }

    private function prepareResponseUsingTransientData(array $inventory, array $data, array $response, $is_financed, $term, $interest_rate, $payment_frequency, bool $unset_cost = true): array
    {
        $exclude_only_finance = $data['deal_type'] === "Cash" ? true : false;
        $response['fees'] = $this->fetchCostSettings($inventory, 'fees', 'FEE_SETTING', true, $exclude_only_finance, $is_financed, $term, $interest_rate, $payment_frequency, $unset_cost);
        $response['guarantees'] = $this->fetchCostSettings($inventory, 'guarantee', '', true, $exclude_only_finance, $is_financed, $term, $interest_rate, $payment_frequency, $unset_cost);
        $response['warranties'] = $this->fetchCostSettings($inventory, 'warranty', 'WARRANTY_SETTING', false, $exclude_only_finance, $is_financed, $term, $interest_rate, $payment_frequency, $unset_cost);
        $response['insurances'] = $this->fetchCostSettings($inventory, 'insurances', 'INSURANCE_SETTING', false, $exclude_only_finance, $is_financed, $term, $interest_rate, $payment_frequency, $unset_cost);

        if (count($response['guarantees']) > 1) {
            $response['guarantees'] = $response['guarantees'][0];
        }

        $response['selected_insurances'] = $this->convertJSONAndIncludeMandatoryEntity($data['selected_insurances'], $response['insurances']);
        $response['selected_warranty'] = $this->convertJSONAndIncludeMandatoryEntity($data['selected_warranty'], $response['warranties']);
        $response['selected_guarantee'] = $this->convertJSONAndIncludeMandatoryEntity("[]", $response['guarantees']);

        return $response;
    }

    private function checkIfInventoryIsInStock($inventory): bool
    {
        return $inventory['status'] === "Instock" && $inventory['visibility'] === "1";
    }

    private function setInventoryFinanceTerms(array $default_finance_terms, array $inventory_custom_digitial_retail_settings): array
    {
        $inventory_custom_digitial_retail_settings = !empty($inventory_custom_digitial_retail_settings) ? $inventory_custom_digitial_retail_settings[0] : [];

        $override_terms = empty($inventory_custom_digitial_retail_settings['override_default_finance_terms']) ? 0
            : intval($inventory_custom_digitial_retail_settings['override_default_finance_terms']);

        if ($override_terms) {
            $default_finance_terms = json_decode($inventory_custom_digitial_retail_settings['finance_terms'], true);
        }

        return $default_finance_terms;
    }

    private function fetchCostSettings(
        array $inventory,
        string $entity_type,
        string $cost_variation_entity_type,
        bool $only_mandatory = false,
        bool $exclude_only_finance = false,
        bool $is_financed = false,
        int $term = 0,
        float $interest_rate = 0,
        string $payment_frequency = "Monthly",
        bool $unset_cost = true
    ) {
        $entity_repository = $this->cost_settings_repository->switchCostType($entity_type);

        if ($only_mandatory) {
            $entity_repository = $entity_repository->onlyMandatory();
        }

        if ($exclude_only_finance) {
            $entity_repository = $entity_repository->excludeOnlyFinance();
        }

        $entities = $entity_repository->index(null, null, null, null, null, null, false);
        $entities = !empty($entities['data_list']) ?  $entities['data_list'] : [];

        foreach ($entities as $key => $entity) {
            $entities[$key]['variation_id'] = null;

            $is_variable = empty($entity['is_variable']) ? 0 : intval($entity['is_variable']);

            if ($is_variable) {
                $cost_variations = $this->cost_variation_matrix_repository
                    ->filterData('entity', $cost_variation_entity_type)
                    ->filterData('entity_id', $entity['id'])
                    ->index(null, null, null, null, null, null, false);

                $cost_variations = !empty($cost_variations['data_list']) ?  $cost_variations['data_list'] : [];

                foreach ($cost_variations as $cost_key => $cost_variation) {
                    $has_matching_cost_variation = $this->checkIfInventoryConditionsPass($inventory['stock_no'], $cost_variation['conditions']);

                    if ($has_matching_cost_variation) {
                        $entities[$key]['variation_id'] = $cost_variation['id'];
                        unset($cost_variation['id']);
                        unset($cost_variation['entity']);
                        unset($cost_variation['conditions']);
                        unset($cost_variation['entity_id']);
                        $entities[$key] = array_replace($entities[$key], $cost_variation);
                    }
                }
            }

            if ($unset_cost) {
                unset($entities[$key]['cost']);
                unset($entities[$key]['cost_tax_type']);
                unset($entities[$key]['cost_tax_percentage']);
                unset($entities[$key]['cost_tax_amount']);
                unset($entities[$key]['cost_tax_type_secondary']);
                unset($entities[$key]['cost_tax_amount_secondary']);
                unset($entities[$key]['cost_tax_percentage_secondary']);
                unset($entities[$key]['cost_tax_amount_total']);
            }


            $entities[$key]['emi_amount'] = 0.00;
            if ($is_financed) {
                $entity_worksheet = $this->worksheet_calculator->buildWorksheet(array_merge([
                    'purchase_price' => floatval($entities[$key]['amount'])
                ], compact(
                    'is_financed',
                    'term',
                    'interest_rate',
                    'payment_frequency',
                )));

                $entities[$key]['emi_amount'] = $entity_worksheet['emi_amount'];
            }
        }
        return $entities;


        return;
    }

    private function checkIfInventoryConditionsPass(string $inventory_stock_no, string $conditions): bool
    {
        $inventories = $this->inventory_repository->filterData('stock_no', $inventory_stock_no)->index(false, false, false, $conditions, false, false, false);
        return count($inventories['data_list']) > 0 ? true : false;
    }

    private function convertJSONAndIncludeMandatoryEntity(string $entity_json, $entities)
    {
        try {
            $selected_entities = json_decode($entity_json, true);

            $valid_entities = [];

            foreach ($entities as $entity) {
                if (in_array(intval($entity['id']), $selected_entities) || $entity['is_mandatory'] == "1") {
                    array_push($valid_entities, intval($entity['id']));
                }
            }

            return $valid_entities;
        } catch (\Exception $ex) {
            return [];
        }
    }

    private function buildWorksheet(
        float $selling_price,
        bool $is_financed = false,
        array $fees = [],
        float $warranty_total_amount = 0,
        float $warranty_tax_amount_total = 0,
        float $insurance_total_amount = 0,
        float $insurance_tax_amount_total = 0,
        float $term = 0,
        float $interest_rate = 0,
        float $downpayment = 0,
        string $payment_frequency = "Monthly",
        bool $does_total_balance_cost_includes_interest = false
    ) {
        $fees_total = 0;
        $fees_tax_total = 0;

        foreach ($fees as $fee) {
            $fees_total += floatval($fee['amount']);
            $fees_tax_total += floatval($fee['tax_amount_total']);
        }

        $worksheet = $this->worksheet_calculator->buildWorksheet(array_merge([
            'purchase_price' => $selling_price
        ], compact(
            'fees_total',
            'fees_tax_total',
            'warranty_total_amount',
            'warranty_tax_amount_total',
            'insurance_total_amount',
            'insurance_tax_amount_total',
            'is_financed',
            'term',
            'interest_rate',
            'downpayment',
            'payment_frequency',
            'does_total_balance_cost_includes_interest'
        )));

        return $worksheet;
    }

    private function convertJSONAndCalculateAmountAndTaxes(string $entity_json, $entities): array
    {
        $total_amount = 0;
        $tax_amount_total = 0;

        try {
            $selected_entities = json_decode($entity_json, true);

            foreach ($entities as $entity) {
                if (in_array(intval($entity['id']), $selected_entities) || $entity['is_mandatory'] == "1") {
                    $total_amount += floatval($entity['amount']);
                    $tax_amount_total += floatval($entity['tax_amount_total']);
                }
            }
        } catch (\Exception $ex) {
        }

        return compact("total_amount", "tax_amount_total");
    }

    private function prepareAndSaveNonPersistantData(string $stock_no, string $uuid, array $data)
    {
        $key = 'DR_' . db_connect()->database . Utility::slugify($stock_no, '_') . Utility::slugify($uuid, '_');
        $key = md5($key);

        $cache_factory = CacheFactory::getHandler(new NonPersistantCache());
        $cache_factory->save($key, json_encode($data), 3600);
    }

    private function updateCustomerInformation(array $data): void
    {
        $map_customer_to_lead = [
            'driver_licence' => 'drivers_license_number',
            'street_address' => 'home_address',
            'mobile' => 'mobile_phone'
        ];

        foreach ($map_customer_to_lead as $key => $value) {
            if (isset($data[$value])) {
                $data[$key] = $data[$value];
                unset($data[$value]);
            }
        }

        if (!empty($data['customer_id'])) {
            $current_entity = $this->customer_repository->show($data['customer_id']);
            $customer_entity = array_merge($current_entity, $data);

            if (isset($customer_entity['date_of_birth']) && strtotime($customer_entity['date_of_birth']) == true) {
                $date_of_birth = Carbon::parse($customer_entity['date_of_birth']);
                $customer_entity['date_of_birth'] = $date_of_birth->format('m/d/Y');
            }

            if (isset($customer_entity['drivers_license_expiry_date']) && strtotime($customer_entity['drivers_license_expiry_date']) == true) {
                $drivers_license_expiry_date = Carbon::parse($customer_entity['drivers_license_expiry_date']);
                $customer_entity['driver_licence_expiry_date'] = $drivers_license_expiry_date->format('m/d/Y');
            }

            if (isset($customer_entity['driver_licence_expiry_date']) && strtotime($customer_entity['driver_licence_expiry_date']) == true) {
                $driver_licence_expiry_date = Carbon::parse($customer_entity['driver_licence_expiry_date']);
                $customer_entity['driver_licence_expiry_date'] = $driver_licence_expiry_date->format('m/d/Y');
            }

            $customer_entity['other_data'] = json_encode($data);

            $this->customer_repository->update($data['customer_id'], $customer_entity);
        }
    }


    private function savePrimaryIndividualCreditApplication(array $data): void
    {
        if (!empty($data['customer_id'])) {
            $credit_application_mapper = $this->customer_credit_application_repository->getCreditApplicationMapper();

            foreach ($credit_application_mapper as $credit_application_key => $current_data_key) {
                if (isset($data[$current_data_key])) {
                    $data[$credit_application_key] = preg_replace('/[^ \w-]/', '', $data[$current_data_key]);
                    $data[$credit_application_key] = preg_replace('/\s+/S', " ", $data[$current_data_key]);
                }
            }

            if (isset($data['residence_status'])) {
                $data['residence_status'] == 'Own' ||
                    $data['residence_status'] == 'Own Free Clear' ||
                    $data['residence_status'] == 'With Family' ? $data['residence_ownership'] = 'Own' : null;

                $data['residence_status'] == 'Rent' ? $data['residence_ownership'] = 'Rent' : null;
            }

            if (isset($data['previous_bankruptcy']) && $data['previous_bankruptcy'] == "Yes") {
                $data['bankruptcy_declared'] = "1";
            }

            $current_entity = $this->customer_credit_application_repository->findByCustomerId($data['customer_id']);

            if (!empty($current_entity['id'])) {
                $this->customer_credit_application_repository->update($current_entity['id'], $data);
            } else {
                $this->customer_credit_application_repository->create($data);
            }
        }
    }

    private function saveCoApplicantDetails(array $data, $deal_id): void
    {
        $co_applicant_lead = [];

        $mapping_co_applicant_lead = $this->customer_credit_application_repository->getCoApplicantMappingWithInCreditApplication();

        if (isset($data['coapplicant_residence_status'])) {
            $data['coapplicant_residence_status'] == 'Own' ||
                $data['coapplicant_residence_status'] == 'Own Free Clear' ||
                $data['coapplicant_residence_status'] == 'With Family' ? $co_applicant_lead['residence_ownership'] = 'Own' : null;

            $data['coapplicant_residence_status'] == 'Rent' ? $co_applicant_lead['residence_ownership'] = 'Rent' : null;
        }

        if (isset($data['coapplicant_date_of_birth']) && strtotime($data['coapplicant_date_of_birth']) == true) {
            $date_of_birth = Carbon::parse($data['coapplicant_date_of_birth']);
            $co_applicant_lead['date_of_birth'] = $date_of_birth->format('m/d/Y');
        }

        if (isset($data['coapplicant_previous_bankruptcy']) && $data['coapplicant_previous_bankruptcy'] == "Yes") {
            $co_applicant_lead['bankruptcy_declared'] = "1";
        }

        foreach ($mapping_co_applicant_lead as $key => $value) {
            if (isset($data[$value])) {
                $co_applicant_lead[$key] = preg_replace('/[^ \w-]/', '', $data[$value]);
                $co_applicant_lead[$key] = preg_replace('/\s+/S', " ", $data[$value]);
                $co_applicant_lead[$key] = $data[$value];
            }
        }

        $co_applicant_customer = $this->customer_repository->create($co_applicant_lead);
        if (isset($co_applicant_customer['customer_id'])) {
            $co_applicant_lead['customer_id'] = $co_applicant_customer['customer_id'];
            $this->customer_credit_application_repository->create($co_applicant_lead);
            $this->saveDealCommonDependency('deal-customer', ['deal_id' => $deal_id, 'customer_id' => $co_applicant_customer['customer_id'], 'customer_type' => 'co-buyer']);
        }
    }

    private function getNonPersistantData(string $stock_no, string $uuid): array
    {
        $default_data = compact('stock_no', 'uuid');
        $default_data['deal_type'] = 'Cash';
        $default_data['downpayment'] = 0;
        $default_data['salesperson'] = '';
        $default_data['selected_guarantee'] = $default_data['selected_insurances'] = $default_data['selected_warranty'] = "";

        $key = 'DR_' . db_connect()->database . Utility::slugify($stock_no, '_') . Utility::slugify($uuid, '_');
        $key = md5($key);

        $cache_factory = CacheFactory::getHandler(new NonPersistantCache());
        $cache_data = $cache_factory->get($key);
        if ($cache_data) {
            return json_decode($cache_data, true);
        }

        return $default_data;
    }
}
