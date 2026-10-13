<?php

namespace Tenant\app\Libraries\WebExtensionTransformer;

use App\Helpers\Utility;
use App\Interfaces\DealerTrackLenderInterface;
use App\Libraries\DealerTrack\DealerTrackImportInterface;
use App\Libraries\DealerTrack\Import\Lender\LenderFactory;
use App\Libraries\DealerTrack\Import\Region\RegionFactory;
use Carbon\Carbon;
use Tenant\app\Libraries\Calculator\LineCalculator;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\InventoryRepository;

final class DealerTrack implements DealerTrackImportInterface
{
    private array $transformed_data = [
        'has_inventory' => false,
        'inventory' => [],
        'has_primary_customer' => false,
        'has_co_applicant_customer' => false,
        'primary_customer' => [],
        'primary_customer_credit_application' => [],
        'co_applicant_customer' => [],
        'co_applicant_customer_credit_application' => [],


    ];

    private array $original_data = [];


    private array $tax_mapping = [];

    private String $tax_type_primary = '';

    private String $tax_type_secondary = '';


    private float $primary_tax_rate = 0;

    private float $secondary_tax_rate = 0;

    protected String $warranty_tax_type_primary = '';

    protected String $warranty_tax_type_secondary = '';

    protected float $warranty_primary_tax_rate = 0;

    protected float $warranty_secondary_tax_rate = 0;

    private String $insurance_tax_type_primary = '';

    private String $insurance_tax_type_secondary = '';

    private float $insurance_primary_tax_rate = 0;

    private float $insurance_secondary_tax_rate = 0;

    private bool $does_balance_cost_includes_interest = false;

    private int $warranty_count = 0;

    private LineCalculator $lineCalculator;

    private DealerTrackLenderInterface $lenderInstance;

    /**
     * configuration_repository
     *
     * @var ConfigurationRepository
     */
    private $configuration_repository;

    /**
     * inventory_repository
     *
     * @var InventoryRepository
     */
    private $inventory_repository;

    public function __construct(array $data)
    {
        $this->original_data = $data;
        $this->inventory_repository = new InventoryRepository();
        $this->configuration_repository = new ConfigurationRepository();

        $business_tax_settings = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_tax_settings', 'value');
        $business_tax_settings = $business_tax_settings->value;
        $business_tax_settings = json_decode($business_tax_settings, true);

        foreach ($business_tax_settings as $tax_setting) {
            $this->tax_mapping[$tax_setting['name']] = $tax_setting['value'];
        }

        $does_balance_cost_includes_interest = (new ConfigurationRepository())->findConfigurationColumnsByKey('total_balance_cost_includes_interest')->value;
        $this->does_balance_cost_includes_interest = $does_balance_cost_includes_interest == "1" ? true : false;

        $this->lineCalculator = new LineCalculator();

        $lender_identifier = '';

        if (key_exists('worksheet', $this->original_data)) {
            if (key_exists('lender', $this->original_data['worksheet'])) {
                $lender_identifier =
                    !empty($this->original_data['worksheet']['lender']['lender'])
                    ? $this->original_data['worksheet']['lender']['lender'] : '';
            }
        }

        $this->lenderInstance = LenderFactory::getInstance($lender_identifier);
    }

    public function transform(): self
    {
        if (key_exists("worksheet", $this->original_data)) {
            if (key_exists("vehicle_selection", $this->original_data['worksheet'])) {
                if (!empty($this->original_data['worksheet']['vehicle_selection'])) {
                    $this->processInventoryMapping();
                }
            }
        }

        if (key_exists("applicant", $this->original_data)) {
            $this->processPrimaryCustomerMapping();
        }

        if (array_key_exists('co_applicants', $this->original_data)) {
            $this->processCoApplicantCustomerMapping();
        }

        if (key_exists("worksheet", $this->original_data)) {
            $this->processDealWorksheet();
        }

        return $this;
    }

    /**
     * Get the value of transformedData
     */
    public function getTransformedData(): array
    {
        return $this->transformed_data;
    }

    public function getPrimaryTaxRate(): float
    {
        return $this->primary_tax_rate;
    }

    public function getSecondaryTaxRate(): float
    {
        return $this->secondary_tax_rate;
    }

    private function processDealWorksheet(): void
    {
        $deal_break_down = [];

        if (key_exists("worksheet", $this->original_data)) {
            if (key_exists("taxes", $this->original_data['worksheet'])) {
                $region_identifier =  $this->getProvinceMapper($this->original_data['worksheet']['taxes']['province']);
                $this->primary_tax_rate = (float) $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['taxes']['gst_hst_rate']);
                $this->secondary_tax_rate = (float) $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['taxes']['pst_rate']);

                $regionalInstance = RegionFactory::getInstance($region_identifier);
                $tax_rates = $regionalInstance->setTaxRates($this);
                foreach ($tax_rates as $key => $tax_rate) {
                    $this->{$key} = $tax_rate;
                }

                $deal_break_down = $this->dealBaseBreakDown();
            }

            if (key_exists("fees", $this->original_data['worksheet'])) {
                $this->processDownPayment();
                $this->processFees();
            }

            if (key_exists("aftermarket_service", $this->original_data['worksheet'])) {
                $this->processAftermarketServices();
            }

            if (key_exists("trade_in", $this->original_data['worksheet'])) {
                $this->processTradeIn();

                if (
                    !empty($this->original_data['worksheet']['trade_in']['trade_year'])
                    && !empty($this->original_data['worksheet']['trade_in']['trade_make'])
                    && !empty($this->original_data['worksheet']['trade_in']['trade_model'])

                ) {
                    if (isset($this->transformed_data['tradein']['trade_value'])) {
                        $deal_break_down['trade_value'] = $this->transformed_data['tradein']['trade_value'];
                    }

                    if (isset($this->transformed_data['tradein']['lien_value'])) {
                        $deal_break_down['lien_payout'] = $this->transformed_data['tradein']['lien_value'];
                    }

                    if (isset($this->transformed_data['tradein']['trade_equity'])) {
                        $deal_break_down['trade_equity'] = $this->transformed_data['tradein']['trade_equity'];
                    }
                }
            }

            $deal_break_down = $this->updateDealWorksheetWithSubTotalCalculations($deal_break_down);

            if (key_exists("financing_terms", $this->original_data['worksheet']) || key_exists("payment", $this->original_data['worksheet'])) {
                if (!empty($this->original_data['worksheet']['financing_terms']) || !empty($this->original_data['worksheet']['payment'])) {

                    $this->processFinancingTerms($deal_break_down);

                    if ($this->does_balance_cost_includes_interest) {
                        $deal_break_down['total_balance_due']  += (float) $this->cleanAndFormatNumericOrSetZero($this->transformed_data['finance']['interest_amount']);
                    }
                }
            }

            $this->transformed_data['dealbreakdown'] = $deal_break_down;
        }
    }

    private function processFinancingTerms($deal_break_down): void
    {
        $finance_entity = [];
        $finance_entity['total_amount'] = !empty($deal_break_down['total_balance_due']) ? $deal_break_down['total_balance_due'] : 0;
        $finance_entity['deal_id'] = $this->original_data['deal_id'];
        $finance_entity['override_finance_calculation'] = 1;
        $finance_entity['interest_rate_type'] = 'Fixed';
        $finance_entity['lien_holder_name'] = empty($this->original_data['worksheet']['lender']['lender']) ? '' : $this->original_data['worksheet']['lender']['lender'];

        if (!empty($this->original_data['worksheet']['deferral_and_payment_date_options'])) {
            if (
                !empty($this->original_data['worksheet']['deferral_and_payment_date_options']['monthly_first_payment_mm'])
                &&  !empty($this->original_data['worksheet']['deferral_and_payment_date_options']['monthly_first_payment_dd'])
                && !empty($this->original_data['worksheet']['deferral_and_payment_date_options']['monthly_first_payment_yyyy'])
            ) {
                if (
                    is_numeric($this->original_data['worksheet']['deferral_and_payment_date_options']['monthly_first_payment_mm'])
                    && is_numeric($this->original_data['worksheet']['deferral_and_payment_date_options']['monthly_first_payment_dd'])
                    && is_numeric($this->original_data['worksheet']['deferral_and_payment_date_options']['monthly_first_payment_yyyy'])
                ) {
                    $first_payment_date = Carbon::create(
                        $this->original_data['worksheet']['deferral_and_payment_date_options']['monthly_first_payment_yyyy'],
                        $this->original_data['worksheet']['deferral_and_payment_date_options']['monthly_first_payment_mm'],
                        $this->original_data['worksheet']['deferral_and_payment_date_options']['monthly_first_payment_dd']
                    );
                    $finance_entity['first_payment_date'] = $first_payment_date->format('m/d/Y');
                }
            }
        }

        if (key_exists("worksheet", $this->original_data)) {
            if (key_exists("financing_terms", $this->original_data['worksheet'])) {
                if (!empty($this->original_data['worksheet']['financing_terms'])) {
                    $finance_entity['interest_rate'] = $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['financing_terms']['actual_interest_rate']);
                    $finance_entity['term'] = $this->cleanAndFormatNumeric($this->original_data['worksheet']['financing_terms']['term']);
                    $finance_entity['payment_frequency'] = $this->setPaymentFrequency($this->original_data['worksheet']['financing_terms']['payment_frequency']);
                    $finance_entity['interest_amount'] = (float) $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['financing_terms']['cost_of_borrowing']);
                    $finance_entity['emi_amount'] = $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['financing_terms']['payment_frequency_amount']);
                }
            }
        }

        if (key_exists("worksheet", $this->original_data)) {
            if (key_exists("payment", $this->original_data['worksheet'])) {
                if (!empty($this->original_data['worksheet']['payment'])) {

                    if (empty($finance_entity['interest_rate'])) {
                        $finance_entity['interest_rate'] = $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['payment']['actual_interest_rate']);
                    }

                    if (empty($finance_entity['term'])) {
                        $finance_entity['term'] = $this->cleanAndFormatNumeric($this->original_data['worksheet']['payment']['term']);
                    }

                    if (empty($finance_entity['payment_frequency'])) {
                        $finance_entity['payment_frequency'] = $this->setPaymentFrequency($this->original_data['worksheet']['payment']['payment_frequency']);
                    }

                    if (empty($finance_entity['interest_amount'])) {
                        $finance_entity['interest_amount'] = (float) $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['payment']['cost_of_borrowing']);
                    }

                    if (empty($finance_entity['emi_amount'])) {
                        $finance_entity['emi_amount'] = $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['payment']['payment_frequency_amount']);
                    }
                }
            }
        }

        $this->transformed_data['finance'] = $finance_entity;
    }

    protected function setPaymentFrequency(String $numeric_frequency): String
    {
        switch ($numeric_frequency) {
            case '12':
                return "Monthly";
                break;

            case '26':
                return "Bi-Weekly";
                break;

            case '52':
                return "Weekly";
                break;

            default:
                return "";
                break;
        }
    }

    private function updateDealWorksheetWithSubTotalCalculations(array $deal_break_down)
    {
        if (key_exists("worksheet", $this->original_data)) {
            $subtotal = 0;
            $total_tax_amount = 0;
            $insurance_tax = 0;
            $net_difference = 0;
            $secondary_subtotal = 0;
            $total_balance_due = 0;

            if (!empty($deal_break_down['purchase_price'])) {
                $subtotal += $deal_break_down['purchase_price'];
                $total_tax_amount += $deal_break_down['tax_on_purchase_total'];
            }

            if (!empty($this->transformed_data['fees'])) {
                foreach ($this->transformed_data['fees'] as $fee) {
                    $subtotal += $fee['amount'];
                    $total_tax_amount += $fee['tax_amount_total'];
                }
            }

            if (!empty($this->transformed_data['warranties'])) {
                foreach ($this->transformed_data['warranties'] as $warranty) {
                    $subtotal += $warranty['amount'];
                    $total_tax_amount += $warranty['tax_amount_total'];
                }
            }

            if (!empty($this->transformed_data['insurances'])) {
                foreach ($this->transformed_data['insurances'] as $insurance) {
                    $insurance_tax += $insurance['tax_amount_total'];
                }
            }

            $net_difference = $subtotal;
            $total_balance_due = $subtotal;

            if (!empty($deal_break_down['trade_value'])) {
                $net_difference -= (float) $deal_break_down['trade_value'];
            }

            if (!empty($deal_break_down['trade_equity'])) {
                $total_balance_due -= (float) $deal_break_down['trade_equity'];
            }

            $total_balance_due += $total_tax_amount;
            $total_balance_due += $insurance_tax;
            $total_balance_due += (float) $deal_break_down['licence_fee'];
            $total_balance_due += (float) $deal_break_down['lien_registration_fee'];

            if (array_key_exists('payments', $this->transformed_data)) {
                if (!empty($this->transformed_data['payments']['amount'])) {
                    $total_balance_due -= (float) $this->transformed_data['payments']['amount'];
                }
            }


            if (!empty($this->transformed_data['insurances'])) {
                foreach ($this->transformed_data['insurances'] as $insurance) {
                    $total_balance_due += (float)$insurance['amount'];
                }
            }



            $secondary_subtotal += $net_difference;
            $secondary_subtotal += $total_tax_amount;
            $secondary_subtotal += (float) $deal_break_down['licence_fee'];


            $deal_break_down['subtotal'] = $subtotal;
            $deal_break_down['total_tax_amount'] = $total_tax_amount;
            $deal_break_down['insurance_tax'] = $insurance_tax;
            $deal_break_down['net_difference'] = $net_difference;
            $deal_break_down['secondary_subtotal'] = $secondary_subtotal;
            $deal_break_down['total_balance_due'] = $total_balance_due;
        }

        return $deal_break_down;
    }

    private function processTradeIn(): void
    {

        if (key_exists("worksheet", $this->original_data)) {
            if (key_exists("trade_in", $this->original_data['worksheet']) && key_exists("lien", $this->original_data['worksheet'])) {
                if (
                    !empty($this->original_data['worksheet']['trade_in']['trade_year'])
                    && !empty($this->original_data['worksheet']['trade_in']['trade_make'])
                    && !empty($this->original_data['worksheet']['trade_in']['trade_model'])

                ) {
                    $trade_in = [];
                    $trade_in['deal_id'] = $this->original_data['deal_id'];
                    $trade_in['trade_value'] = (float) $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['trade_in']['trade_allowance']);
                    $trade_in['lien_value'] = (float) $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['lien']['lien_amount']);
                    $trade_in['trade_equity'] = (float) $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['lien']['net_trade_in_allowance']);
                    $trade_in['lien_holder'] = !empty($this->original_data['worksheet']['lien']['balance_owed_to']) ? $this->original_data['worksheet']['lien']['balance_owed_to'] : '';
                    $trade_in['vin'] = !empty($this->original_data['worksheet']['trade_in']['trade_vin']) ? $this->original_data['worksheet']['trade_in']['trade_vin'] : strtoupper(Utility::randomString(6));
                    $trade_in['year'] = $this->original_data['worksheet']['trade_in']['trade_year'];
                    $trade_in['make'] = $this->original_data['worksheet']['trade_in']['trade_make'];
                    $trade_in['model'] = $this->original_data['worksheet']['trade_in']['trade_model'];
                    $trade_in['trim'] = $this->original_data['worksheet']['trade_in']['trade_body_style'];
                    $trade_in['status'] = "Unpublished";
                    $trade_in['sub_status'] = "Reconditioning";
                    $trade_in['title'] = $this->configuration_repository->findConfigurationColumnsByKey('editor_default_title', 'value')->value;
                    $trade_in['km_miles'] = 'KM';
                    $trade_in['vehicle_description'] = $this->configuration_repository->findConfigurationColumnsByKey('editor_default_vehicle_description', 'value')->value;
                    $trade_in['feature_listing'] = "0";
                    $trade_in['vehicle_type'] = "Used";
                    $trade_in['visibility'] = "1";
                    $trade_in['vehicle_tags'] = "[]";
                    $trade_in['odometer'] = $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['trade_in']['trade_odometer']);
                    $trade_in['stock_no'] =  $this->inventory_repository->getAvailableStockNumber($trade_in);
                    $trade_in['special_price'] = $trade_in['price'] = 0;
                    $this->transformed_data['tradein'] = $trade_in;
                }
            }
        }
    }

    private function processAftermarketServices(): void
    {

        if (key_exists("worksheet", $this->original_data)) {
            if (key_exists("aftermarket_service", $this->original_data['worksheet'])) {
                $this->transformed_data['warranties'] = $this->transformed_data['insurances'] = [];

                if ($this->original_data['worksheet']['aftermarket_service']['gap_protection_type'] == "3") {
                    $this->lenderInstance->addTaxableInsurance('gap_protection');
                } else {
                    $this->lenderInstance->addTaxableWarranties('gap_protection');
                }

                if ($this->original_data['worksheet']['aftermarket_service']['anti_theft_type'] == "") {
                    $this->lenderInstance->addNonTaxableWarranties('anti_theft');
                } else if ($this->original_data['worksheet']['aftermarket_service']['anti_theft_type'] == "Insurance") {
                    $this->lenderInstance->addTaxableInsurance('anti_theft');
                } else if ($this->original_data['worksheet']['aftermarket_service']['anti_theft_type'] == "Warranty") {
                    $this->lenderInstance->addTaxableWarranties('anti_theft');
                } else if ($this->original_data['worksheet']['aftermarket_service']['anti_theft_type'] == "3") {
                    $this->lenderInstance->addTaxableInsurance('anti_theft');
                } else if ($this->original_data['worksheet']['aftermarket_service']['anti_theft_type'] == "4") {
                    $this->lenderInstance->addTaxableWarranties('anti_theft');
                }

                $this->processWarranties();
                $this->processInsurances();
            }
        }
    }

    private function processInsurances(): void
    {
        if (key_exists("worksheet", $this->original_data)) {
            if (key_exists("aftermarket_service", $this->original_data['worksheet'])) {
                $insurance_attributes = $this->lenderInstance->getTaxableInsurance();

                foreach ($insurance_attributes as $insurance_attribute) {
                    if (!empty($this->original_data['worksheet']['aftermarket_service'][$insurance_attribute])) {
                        $insurance_amount = $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['aftermarket_service'][$insurance_attribute]);

                        $insurance_type =  $this->getInsuranceType($insurance_attribute);
                        $insurance_label = $insurance_attribute;
                        $insurance_label = str_replace('_', ' ', $insurance_label);
                        $insurance_label = strtoupper($insurance_label);



                        array_push(
                            $this->transformed_data['insurances'],
                            $this->currentLineCalculation(
                                (float) $insurance_amount,
                                $this->insurance_tax_type_primary,
                                $this->insurance_tax_type_secondary,
                                ['type' => $insurance_type, 'description' => $insurance_label, 'deal_id' => $this->original_data['deal_id']]
                            )
                        );
                    }
                }

                if (key_exists("other_insurances", $this->original_data['worksheet']['aftermarket_service'])) {

                    if (!empty($this->original_data['worksheet']['aftermarket_service']['other_insurances'])) {
                        $other_insurances = $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['aftermarket_service']['other_insurances']);
                        $insurance_description = !empty($this->original_data['worksheet']['aftermarket_service']['other_insurances_description']) ?
                            $this->original_data['worksheet']['aftermarket_service']['other_insurances_description'] : '';

                        if (!empty($other_insurances)) {
                            array_push(
                                $this->transformed_data["insurances"],
                                $this->currentLineCalculation(
                                    (float)  $other_insurances,
                                    $this->insurance_tax_type_primary,
                                    $this->insurance_tax_type_secondary,
                                    ['type' => 'Other', 'description' => $insurance_description, 'deal_id' => $this->original_data['deal_id']]
                                )
                            );
                        }
                    }
                }
            }
        }
    }

    protected function getInsuranceType(String $insurance_name): String
    {
        switch ($insurance_name) {
            case 'life_insurance':
                return 'Life';
                break;
            case 'critical_illness':
                return 'Sickness';
                break;
            case 'gap_protection':
                return 'GAP';
                break;
            default:
                return 'Other';
                break;
        }
    }

    private function processWarranties(): void
    {
        if (key_exists("worksheet", $this->original_data)) {
            if (key_exists("aftermarket_service", $this->original_data['worksheet'])) {
                $warranty_attributes = $this->lenderInstance->getTaxableWarranties();
                $non_taxable_warranty_attributes = $this->lenderInstance->getNonTaxableWarranties();
                $warranty_as_fee_attributes = $this->lenderInstance->getWarrantiesAsFees();

                foreach ($warranty_attributes as $taxable_warranty_attribute) {
                    $this->processAWarranty(
                        $this->replaceWarrantyAttributeWithFeeAttribute(),
                        $taxable_warranty_attribute,
                        $this->warranty_tax_type_primary,
                        $this->warranty_tax_type_secondary
                    );
                }

                foreach ($warranty_as_fee_attributes as $warranty_attribute) {
                    $this->processAWarranty(
                        "fees",
                        $warranty_attribute,
                        $this->tax_type_primary,
                        $this->tax_type_secondary
                    );
                }

                foreach ($non_taxable_warranty_attributes as $non_taxable_warranty_attribute) {
                    $this->processAWarranty(
                        $this->replaceWarrantyAttributeWithFeeAttribute(),
                        $non_taxable_warranty_attribute,
                        'Tax Exempt',
                        'Tax Exempt',
                    );
                }

                if ($this->lenderInstance->hasOtherAfterMarketFee()) {
                    if (!empty($this->original_data['worksheet']['aftermarket_service']['other_non_taxable_am_products'])) {
                        $other_non_taxable_am_products = $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['aftermarket_service']['other_non_taxable_am_products']);
                        if (!empty($other_non_taxable_am_products)) {
                            array_push(
                                $this->transformed_data[$this->replaceWarrantyAttributeWithFeeAttribute()],
                                $this->currentLineCalculation(
                                    (float) $other_non_taxable_am_products,
                                    'Tax Exempt',
                                    'Tax Exempt',
                                    [
                                        'company_name' => $this->original_data['worksheet']['aftermarket_service']['other_non_taxable_am_description'],
                                        'name' => $this->original_data['worksheet']['aftermarket_service']['other_non_taxable_am_description'],
                                        'deal_id' => $this->original_data['deal_id']
                                    ]
                                )
                            );
                        }
                    }

                    if (!empty($this->original_data['worksheet']['aftermarket_service']['other_taxable_am_products'])) {
                        $other_taxable_am_products = $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['aftermarket_service']['other_taxable_am_products']);

                        if (!empty($other_taxable_am_products)) {
                            array_push(
                                $this->transformed_data[$this->replaceWarrantyAttributeWithFeeAttribute()],
                                $this->currentLineCalculation(
                                    (float)  $other_taxable_am_products,
                                    $this->tax_type_primary,
                                    $this->tax_type_secondary,
                                    [
                                        'company_name' => $this->original_data['worksheet']['aftermarket_service']['other_taxable_am_description'],
                                        'name' => $this->original_data['worksheet']['aftermarket_service']['other_taxable_am_description'],
                                        'deal_id' => $this->original_data['deal_id']
                                    ]
                                )
                            );
                        }
                    }
                }
            }
        }
    }

    private function processAWarranty(String $transformed_data_attribute, String $warranty_attribute, $tax_1, $tax_2): void
    {
        if (!empty($this->original_data['worksheet']['aftermarket_service'][$warranty_attribute])) {
            $warranty_amount = $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['aftermarket_service'][$warranty_attribute]);
            if (!empty($warranty_amount)) {

                if ($transformed_data_attribute === "warranties") {
                    $this->incrementWarrantyCount();
                }
                $warranty_label = $warranty_attribute;
                $warranty_label = str_replace('_', ' ', $warranty_label);
                $warranty_label = strtoupper($warranty_label);

                array_push(
                    $this->transformed_data[$transformed_data_attribute],
                    $this->currentLineCalculation(
                        (float) $warranty_amount,
                        $tax_1,
                        $tax_2,
                        ['company_name' => $warranty_label, 'name' => $warranty_label, 'deal_id' => $this->original_data['deal_id']]
                    )
                );
            }
        }
    }

    protected function replaceWarrantyAttributeWithFeeAttribute(): String
    {
        return $this->warranty_count >= 1 ? 'fees' : 'warranties';
    }

    private function incrementWarrantyCount(): void
    {
        ++$this->warranty_count;
    }

    private function processDownPayment(): void
    {
        if (key_exists("worksheet", $this->original_data)) {
            if (key_exists("fees", $this->original_data['worksheet'])) {
                if (!empty($this->original_data['worksheet']['fees']['cash_down_payment'])) {
                    $downpayment = $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['fees']['cash_down_payment']);
                    $this->transformed_data['payments'] =
                        [
                            'description' => '',
                            'is_downpayment_or_deposit' => 0,
                            'amount' => (float) $downpayment,
                            'deal_id' => $this->original_data['deal_id']
                        ];
                }
            }
        }
    }

    private function processFees(): void
    {
        $this->transformed_data['fees'] = [];

        if (key_exists("worksheet", $this->original_data)) {
            if (key_exists("fees", $this->original_data['worksheet'])) {

                $taxable_fees_attributes = $this->lenderInstance->getTaxableFees();

                foreach ($taxable_fees_attributes as $taxable_fee_attribute) {
                    if (!empty($this->original_data['worksheet']['fees'][$taxable_fee_attribute])) {
                        $taxable_amount = $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['fees'][$taxable_fee_attribute]);
                        if (!empty($taxable_amount)) {
                            $fee_label = $taxable_fee_attribute;
                            $fee_label = str_replace('_', ' ', $fee_label);
                            $fee_label = strtoupper($fee_label);

                            array_push(
                                $this->transformed_data['fees'],
                                $this->currentLineCalculation(
                                    (float) $taxable_amount,
                                    $this->tax_type_primary,
                                    $this->tax_type_secondary,
                                    ['name' => $fee_label, 'deal_id' => $this->original_data['deal_id']]
                                )
                            );
                        }
                    }
                }


                $non_taxable_fees_attributes = $this->lenderInstance->getNonTaxableFees();

                foreach ($non_taxable_fees_attributes as $non_taxable_fee_attribute) {
                    if (!empty($this->original_data['worksheet']['fees'][$non_taxable_fee_attribute])) {
                        $non_taxable_amount = $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['fees'][$non_taxable_fee_attribute]);
                        if (!empty($non_taxable_amount)) {
                            $fee_label = $non_taxable_fee_attribute;
                            $fee_label = str_replace('_', ' ', $fee_label);
                            $fee_label = strtoupper($fee_label);

                            array_push(
                                $this->transformed_data['fees'],
                                $this->currentLineCalculation(
                                    (float) $non_taxable_amount,
                                    'Tax Exempt',
                                    'Tax Exempt',
                                    ['name' => $fee_label, 'deal_id' => $this->original_data['deal_id']]
                                )
                            );
                        }
                    }
                }
            }
        }

        $this->processOtherFees();
    }

    private function processOtherFees(): void
    {
        if (key_exists("worksheet", $this->original_data)) {
            if (key_exists("fees", $this->original_data['worksheet'])) {
                $other_taxable_amount = $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['fees']['other_taxable']);
                if (!empty($other_taxable_amount)) {
                    array_push(
                        $this->transformed_data['fees'],
                        $this->currentLineCalculation(
                            (float) $other_taxable_amount,
                            $this->tax_type_primary,
                            $this->tax_type_secondary,
                            ['name' => $this->original_data['worksheet']['fees']['other_taxable_description'], 'deal_id' => $this->original_data['deal_id']]
                        )
                    );
                }

                $other_non_taxable_amount = $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['fees']['other_non_taxable']);

                if (!empty($other_non_taxable_amount)) {
                    array_push(
                        $this->transformed_data['fees'],
                        $this->currentLineCalculation(
                            (float) $other_non_taxable_amount,
                            'Tax Exempt',
                            'Tax Exempt',
                            ['name' => $this->original_data['worksheet']['fees']['other_non_taxable_description'], 'deal_id' => $this->original_data['deal_id']]
                        )
                    );
                }

                $other_non_taxable_amount_items = $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['fees']['non_taxable_items']);

                if (!empty($other_non_taxable_amount_items)) {
                    array_push(
                        $this->transformed_data['fees'],
                        $this->currentLineCalculation(
                            (float) $other_non_taxable_amount_items,
                            'Tax Exempt',
                            'Tax Exempt',
                            ['name' => $this->original_data['worksheet']['fees']['non_taxable_items_description'], 'deal_id' => $this->original_data['deal_id']]
                        )
                    );
                }
            }
        }
    }


    private function dealBaseBreakDown(): array
    {
        $deal_base_break_down = [];
        $purchase_price = 0;

        if (array_key_exists('worksheet', $this->original_data)) {

            if (key_exists('purchase_details', $this->original_data['worksheet'])) {
                $purchase_price = (float) $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['purchase_details']['cash_price']);
            }

            $taxable_purchase_price = $purchase_price;

            if (key_exists('trade_in', $this->original_data['worksheet'])) {
                $taxable_purchase_price -= (float) $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['trade_in']['trade_allowance']);
            }

            if (key_exists('fees', $this->original_data['worksheet'])) {
                $purchase_price -= (float) $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['fees']['rebate']);
            }

            $purchase_details = $this->currentLineCalculation(
                (float) $taxable_purchase_price,
                $this->tax_type_primary,
                $this->tax_type_secondary,
                ['deal_id' => $this->original_data['deal_id']]
            );

            if (key_exists('fees', $this->original_data['worksheet'])) {
                if ($this->lenderInstance->hasLicenceFee()) {
                    $deal_base_break_down['licence_fee'] = (float) $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['fees']['license_fee']);
                } else {
                    $deal_base_break_down['licence_fee'] =  0;
                }

                $deal_base_break_down['lien_registration_fee'] = (float) $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['fees']['ppsa_fee']);

                if (empty($deal_base_break_down['lien_registration_fee'])) {
                    if (isset($this->original_data['worksheet']['fees']['ppsa'])) {
                        $deal_base_break_down['lien_registration_fee'] = (float) $this->cleanAndFormatNumericOrSetZero($this->original_data['worksheet']['fees']['ppsa']);
                    }
                }
            }

            $deal_base_break_down['deal_id'] =  $purchase_details['deal_id'];
            $deal_base_break_down['purchase_price'] =  $purchase_price;
            $deal_base_break_down['purchase_tax_type'] =  $purchase_details['tax_type'];
            $deal_base_break_down['purchase_tax_percentage'] =  $purchase_details['tax_percentage'];
            $deal_base_break_down['tax_on_purchase_price'] =  $purchase_details['tax_amount'];
            $deal_base_break_down['purchase_tax_type_secondary'] =  $purchase_details['tax_type_secondary'];
            $deal_base_break_down['tax_on_purchase_price_secondary'] =  $purchase_details['tax_amount_secondary'];
            $deal_base_break_down['purchase_tax_percentage_secondary'] =  $purchase_details['tax_percentage_secondary'];
            $deal_base_break_down['tax_on_purchase_total'] =  $purchase_details['tax_amount_total'];
        }

        return $deal_base_break_down;
    }


    protected function currentLineCalculation(float $amount, String $tax_type_primary = '', String $tax_type_secondary = '', array $other_information = []): array
    {
        $current_line_calculation = $this->lineCalculator->calculate(
            $amount,
            0,
            1,
            $tax_type_primary,
            $tax_type_secondary
        );

        $description = array_key_exists('description', $other_information) ? $other_information['description'] : '';

        return array_merge($current_line_calculation, $other_information, ['amount' => $amount, 'description' => $description]);
    }

    private function processInventoryMapping(): void
    {
        $inventory = [];

        $vehicle_information = $this->original_data['worksheet']['vehicle_selection'];
        if (key_exists('vin', $vehicle_information) && !empty($vehicle_information['vin'])) {
            $inventory['vin'] = !empty($vehicle_information['vin']) ? $vehicle_information['vin'] : '';
            $inventory['year'] = !empty($vehicle_information['year']) ? $vehicle_information['year'] : $vehicle_information['ff_year'];
            $inventory['make'] = !empty($vehicle_information['make']) ? $vehicle_information['make'] : $vehicle_information['ff_make'];
            $inventory['model'] = !empty($vehicle_information['model']) ? $vehicle_information['model'] : $vehicle_information['ff_model'];
            $inventory['trim'] = !empty($vehicle_information['series']) ? $vehicle_information['series'] : $vehicle_information['ff_series'];
            $inventory['body_type'] = !empty($vehicle_information['body_style']) ? $vehicle_information['body_style'] : $vehicle_information['ff_body_style'];
            $inventory['status'] = 'Unpublished';
            $inventory['sub_status'] = 'Deal Pending';
            $inventory['vehicle_type'] = 'Used';
            $inventory['odometer'] = !empty($vehicle_information['current_kms']) ? $this->cleanAndFormatNumeric($vehicle_information['current_kms']) : '';
            $inventory['title'] = $this->configuration_repository->findConfigurationColumnsByKey('editor_default_title', 'value')->value;
            $inventory['km_miles'] = 'KM';
            $inventory['vehicle_description'] = $this->configuration_repository->findConfigurationColumnsByKey('editor_default_vehicle_description', 'value')->value;
            $inventory['price'] = 0;
            $inventory['special_price'] = 0;
            $inventory['visibility'] = "1";
            $inventory['stock_no'] = !empty($vehicle_information['stock_number']) ? $vehicle_information['stock_number'] : $this->inventory_repository->getAvailableStockNumber($vehicle_information);
            $inventory['stock_date'] = Carbon::now()->format('Y-m-d');

            $this->transformed_data['inventory'] = $inventory;
            $this->transformed_data['has_inventory'] = true;
        }
    }

    private function processPrimaryCustomerMapping(): void
    {

        $customer = $this->original_data['applicant'];
        if (!empty($customer['personal_information'])) {
            if (!empty($customer['personal_information']['first_name'])) {
                $this->transformed_data['has_primary_customer'] = true;
                $this->transformed_data['primary_customer']  = $this->mapCustomer($customer);
                $this->transformed_data['primary_customer_credit_application'] = $this->mapCustomerCreditApplication($this->original_data['applicant']);
            }
        }
    }

    private function processCoApplicantCustomerMapping(): void
    {
        $has_co_applicants = !empty($this->original_data['co_applicants']) && is_array($this->original_data['co_applicants']);
        if ($has_co_applicants) {
            $this->transformed_data['has_co_applicant_customer'] = true;
            $this->transformed_data['co_applicant_customer'] = $this->mapCustomer($this->original_data['co_applicants'][0]);
            $this->transformed_data['co_applicant_customer_credit_application'] = $this->mapCustomerCreditApplication($this->original_data['co_applicants'][0]);
        }
    }

    private function mapCustomerCreditApplication($payload): array
    {
        $credit_application = [];

        if (array_key_exists('personal_information', $payload)) {
            $personal_information = $payload['personal_information'];
            $credit_application['salutation'] = $this->getSalutation($personal_information['salutation']);
            $credit_application['gender'] = $this->getGender($personal_information['gender']);
            $credit_application['marital_status'] = $this->getMaritalStatus($personal_information['marital_status']);
        }

        if (array_key_exists('home_mortgage', $payload)) {
            $home_mortgage = $payload['home_mortgage'];
            $credit_application['residence_ownership'] = $this->getHomeOwnershipType($home_mortgage['home_status']);
            $credit_application['market_value'] =  $this->cleanAndFormatNumericOrSetZero($home_mortgage['market_value']);
            $credit_application['mortgage_amount'] =  $this->cleanAndFormatNumericOrSetZero($home_mortgage['mortgage_amount']);
            $credit_application['property_owner'] = $home_mortgage['mortgage_holder'];
            $credit_application['monthly_payment'] = $this->cleanAndFormatNumericOrSetZero($home_mortgage['monthly_payment']);
        }

        if (array_key_exists('current_address', $payload)) {
            $credit_application['years_at_present_address'] = is_numeric($payload['current_address']['address_duration_years']) ? $payload['current_address']['address_duration_years'] : 0;
        }

        if (array_key_exists('previous_address', $payload)) {
            $credit_application = $this->mapAddress($credit_application, $payload['previous_address'], 'previous_address_', 'prev_add_');
            $credit_application['previous_residence_address_period_years'] = $payload['previous_address']['prev_add_duration_years'];
            $credit_application['previous_residence_period_months'] = $payload['previous_address']['prev_add_duration_months'];
        }

        if (array_key_exists('current_employment', $payload)) {
            $current_employment = $payload['current_employment'];
            $credit_application['employment_type'] = $this->getEmploymentStatus($current_employment['employment_status']);
            $credit_application['employer_name'] = $current_employment['employer_name'];
            $credit_application['occupation'] = $current_employment['occupation'];
            $credit_application['employer_work_phone'] = $this->cleanPhoneNumber($current_employment['employer_phone']);
            $credit_application['employer_work_phone_ext'] = $current_employment['employer_phone_extension'];
            $credit_application['employment_period_years'] = $current_employment['cur_emp_duration_years'];
            $credit_application['employment_period_months'] = $current_employment['cur_emp_duration_months'];
            $credit_application = $this->mapAddress($credit_application, $current_employment, 'employer_', 'cur_emp_');
        }

        if (array_key_exists('previous_employment', $payload)) {
            $pervious_employment = $payload['previous_employment'];
            $credit_application['previous_employment_type'] = $this->getEmploymentStatus($pervious_employment['prev_emp_employment_status']);
            $credit_application['previous_employer_name'] = $pervious_employment['prev_emp_occupation'];
            $credit_application['previous_employer_work_phone'] = $this->cleanPhoneNumber($pervious_employment['prev_emp_employer_phone']);
            $credit_application['previous_employer_work_phone_ext'] = $pervious_employment['prev_emp_employer_phone_extension'];
            $credit_application['previous_employment_period_years'] = $pervious_employment['prev_emp_duration_years'];
            $credit_application['previous_employment_period_months'] = $pervious_employment['prev_emp_duration_months'];
            $credit_application = $this->mapAddress($credit_application, $pervious_employment, 'previous_employer_', 'prev_emp_');
        }

        if (array_key_exists('income_details', $payload)) {
            $other_income_details = $payload['income_details'];
            $credit_application['other_income_source'] = $other_income_details['other_income_description'];
            $credit_application['annual_gross'] = $other_income_details['annual_gross_total'];

            if (is_numeric($other_income_details['other_income_basis']) && is_numeric($other_income_details['other_income_basis'])) {
                $credit_application['other_annual_gross'] = $this->getAnnualIncome($other_income_details['other_income_basis'], $other_income_details['other_income']);
            }
        }

        return $credit_application;
    }

    private function mapCustomer($payload): array
    {
        $customer = [];

        if (!empty($payload['personal_information'])) {
            $personal_information = $payload['personal_information'];
            $customer['first_name'] = $personal_information['first_name'] . ' ' . $personal_information['middle_name'];
            $customer['last_name'] = $personal_information['last_name'];
            $customer['rin_or_dl_flag'] = '0';
            $customer['customer_type'] = '0';
            $customer['driver_licence_expiry_date'] = '';
            $customer['date_of_birth'] = '';
            if (is_numeric($personal_information['date_of_birth_dd']) && is_numeric($personal_information['date_of_birth_mm']) && is_numeric($personal_information['date_of_birth_yyyy'])) {
                $date_of_birth = Carbon::create($personal_information['date_of_birth_yyyy'], $personal_information['date_of_birth_mm'], $personal_information['date_of_birth_dd']);
                $customer['date_of_birth'] = $date_of_birth->format('m/d/Y');
            }
            $customer['phone'] = $this->cleanPhoneNumber($personal_information['phone']);
            $customer['fax'] = '';
            $customer['mobile'] = $this->cleanPhoneNumber($personal_information['mobile_phone']);
            $customer['email'] = $personal_information['email'];

            if (array_key_exists('current_address', $payload)) {
                $customer = $this->mapAddress($customer, $payload['current_address']);
            }
        }



        return $customer;
    }

    private function mapAddress($entity, $payload, $prefix = '', $payload_prefix = ''): array
    {
        $address_no = !empty($payload[$payload_prefix . 'address_no']) ? $payload[$payload_prefix . 'address_no'] . ' ' : '';
        $street_name = !empty($payload[$payload_prefix . 'street_name']) ? $payload[$payload_prefix . 'street_name'] : '';
        $suite_no = !empty($payload[$payload_prefix . 'suite_no']) ? $payload[$payload_prefix . 'suite_no'] : '';
        $postal_code =  !empty($payload[$payload_prefix . 'postal_code']) ? $payload[$payload_prefix . 'postal_code'] : '';
        $province = !empty($payload[$payload_prefix . 'province']) ? $payload[$payload_prefix . 'province'] : '';
        $province = $this->getProvinceMapper($province);

        if (empty($postal_code)) {
            $postal_code =  !empty($payload['postal_code_' . substr($payload_prefix, 0, -1)]) ? $payload['postal_code_' . substr($payload_prefix, 0, -1)] : '';
        }

        $entity[$prefix . 'street_address'] = $address_no  . $street_name;

        if (!empty($prefix)) {
            $entity[preg_replace('/_$/', '', $prefix)] = $address_no  . $street_name;
            $entity[$prefix . 'address'] = $address_no  . $street_name;
        }

        $entity[$prefix . 'apt'] =  $suite_no;
        $entity[$prefix . 'city'] = $payload[$payload_prefix . 'city'];
        $entity[$prefix . 'province'] = str_replace('CA-', '', $province);
        $entity[$prefix . 'postal_code'] =  $postal_code;
        $entity[$prefix . 'country'] = '';
        return $entity;
    }

    private function getSalutation($salutation): String
    {
        switch ($salutation) {
            case 'DR':
                return 'Dr';
                break;

            case 'MR':
                return 'Mr';
                break;

            case 'MS':
                return 'Ms';
                break;

            case 'MIS':
                return 'Miss';
                break;

            case 'MRS':
                return 'Mrs';
                break;

            default:
                return 'Other';
                break;
        }
    }

    private function getMaritalStatus($marital_status): String
    {
        switch ($marital_status) {
            case 'CL':
                return 'Common Law';
                break;

            case 'DV':
                return 'Divorced';
                break;

            case 'MR':
                return 'Married';
                break;

            case 'SG':
                return 'Single';
                break;

            case 'SP':
                return 'Separated';
                break;

            case 'WD':
                return 'Widow/Widower';
                break;

            default:
                return '';
                break;
        }
    }

    private function getGender($gender): String
    {
        switch ($gender) {
            case 'MALE':
                return 'Male';
                break;

            case 'FEMALE':
                return 'Female';
                break;

            default:
                return 'Other';
                break;
        }
    }

    private function getHomeOwnershipType($home_ownership_type): String
    {
        switch ($home_ownership_type) {
            case 'OF':
                return 'Own Free Clear';
                break;

            case 'OM':
                return 'Other';
                break;

            case 'OT':
                return 'Other';
                break;

            case 'OW':
                return 'Own';
                break;

            case 'PA':
                return 'With Family';
                break;

            case 'RE':
                return 'Rent';
                break;

            default:
                return '';
                break;
        }
    }

    private function getEmploymentStatus($employment_status): String
    {
        switch ($employment_status) {
            case 'FT':
                return 'Fulltime';
                break;

            case 'FTP':
                return 'Fulltime Probation';
                break;

            case 'PTC':
                return 'Partime Casual';
                break;

            case 'PTR':
                return 'Partime Regular';
                break;

            case 'RET':
                return 'Retired';
                break;

            case 'SE':
                return 'Self Employed';
                break;

            case 'SEAS':
                return 'Seasonal Summer';
                break;

            case 'SEAW':
                return 'Seasonal Winter';
                break;

            default:
                return '';
                break;
        }
    }


    private function getAnnualIncome($period, $amount)
    {
        try {
            switch ($period) {
                case '1':
                    return (float) $amount;
                    break;

                case '12':
                    return (float) $amount * (int) $period;
                    break;

                case '52':
                    return (float) $amount * (int) $period;
                    break;

                default:
                    return '';
                    break;
            }
        } catch (\Exception $ex) {
            return 0;
        }
    }

    private function getProvinceMapper($region_id)
    {
        switch ($region_id . "") {
            case '1':
                return "CA-AB";
                break;

            case '2':
                return "CA-BC";
                break;

            case '3':
                return "CA-MB";
                break;

            case '4':
                return "CA-NB";
                break;

            case '5':
                return "CA-NL";
                break;

            case '6':
                return "CA-NT";
                break;

            case '7':
                return "CA-NS";
                break;

            case '8':
                return "CA-NU";
                break;

            case '9':
                return "CA-ON";
                break;

            case '10':
                return "CA-PE";
                break;

            case '11':
                return "CA-QC";
                break;

            case '12':
                return "CA-SK";
                break;

            case '13':
                return "CA-YT";
                break;
            default:
                return '';
                break;
        }
    }

    private function cleanAndFormatNumeric($numeric_string)
    {
        $cleaned_string = filter_var($numeric_string, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        return $cleaned_string;
    }

    private function cleanAndFormatNumericOrSetZero($numeric_string)
    {
        if (empty($numeric_string)) {
            return 0;
        }

        $number = $this->cleanAndFormatNumeric($numeric_string);
        return is_numeric($number) ? $number : 0;
    }

    private function cleanPhoneNumber($phone_no): ?string
    {
        $numeric_string = preg_replace('/[^0-9]/', '', $phone_no);
        $last_ten_chars = substr($numeric_string, -10);
        return $last_ten_chars;
    }
}
