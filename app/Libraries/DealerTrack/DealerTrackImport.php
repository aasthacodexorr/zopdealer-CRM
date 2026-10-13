<?php

namespace App\Libraries\DealerTrack;

use App\Helpers\Utility;
use App\Interfaces\DealerTrackLenderInterface;
use App\Libraries\DealerTrack\Import\Lender\LenderFactory;
use App\Libraries\DealerTrack\Import\Region\RegionFactory;
use Carbon\Carbon;
use Error;
use Tenant\app\Libraries\Calculator\LineCalculator;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\InventoryRepository;

/**
 * DealerTrackImport  This class contains methods associated with DealerTrackImport
 */
class DealerTrackImport implements DealerTrackImportInterface
{

    private array $transformed_data = [];

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

    protected $configuration_repository;

    /**
     * inventory_repository
     *
     * @var InventoryRepository
     */
    private $inventory_repository;

    public function __construct(array $data)
    {
        $business_tax_settings = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_tax_settings', 'value');
        $business_tax_settings = $business_tax_settings->value;
        $business_tax_settings = json_decode($business_tax_settings, true);

        foreach ($business_tax_settings as $tax_setting) {
            $this->tax_mapping[$tax_setting['name']] = $tax_setting['value'];
        }

        $does_balance_cost_includes_interest = (new ConfigurationRepository())->findConfigurationColumnsByKey('total_balance_cost_includes_interest')->value;
        $this->does_balance_cost_includes_interest = $does_balance_cost_includes_interest == "1" ? true : false;

        $this->lineCalculator = new LineCalculator();

        $this->original_data = $data;

        $lender_identifier = '';

        if (array_key_exists('Header', $this->original_data)) {
            $lender_identifier =
                !empty($this->original_data['Header']['LenderCode'])
                ? $this->original_data['Header']['LenderCode'] : '';
        }

        $this->lenderInstance = LenderFactory::getInstance($lender_identifier);
        $this->inventory_repository = new InventoryRepository();
        $this->configuration_repository = new ConfigurationRepository();
    }

    public function transform(): self
    {
        if (!array_key_exists('deal_id', $this->original_data)) {
            throw new Error('No Deal Id Found !');
        }

        $this->transformed_data['has_inventory'] = false;

        if (array_key_exists('Automobile', $this->original_data)) {
            if (!empty($this->original_data['Automobile']['VehicleId'])) {
                $this->transformed_data['has_inventory'] = true;
                $this->transformed_data['inventory'] = $this->mapInventory($this->original_data['Automobile']);
            }
        }

        $this->transformed_data['has_primary_customer'] = false;
        if (array_key_exists('PrimaryApplicant', $this->original_data)) {

            $this->transformed_data['has_primary_customer'] = true;
            $this->transformed_data['primary_customer'] = $this->mapCustomer($this->original_data['PrimaryApplicant']);
            $this->transformed_data['primary_customer_credit_application'] = $this->mapCustomerCreditApplication($this->original_data['PrimaryApplicant']);
        }

        $this->transformed_data['has_co_applicant_customer'] = false;
        if (array_key_exists('CoApplicants', $this->original_data)) {

            if (!empty($this->original_data['CoApplicants']) && is_array($this->original_data['CoApplicants'])) {
                $this->transformed_data['has_co_applicant_customer'] = true;
                $this->transformed_data['co_applicant_customer'] = $this->mapCustomer($this->original_data['CoApplicants'][0]);
                $this->transformed_data['co_applicant_customer_credit_application'] = $this->mapCustomerCreditApplication($this->original_data['CoApplicants'][0]);
            }
        }

        if (array_key_exists('Worksheet', $this->original_data)) {

            $deal_break_down = [];

            $region_identifier =
                !empty($this->original_data['Worksheet']['Taxes']['TaxableProvince'])
                ? $this->original_data['Worksheet']['Taxes']['TaxableProvince'] : '';

            $this->primary_tax_rate = !empty($this->original_data['Worksheet']['Taxes']['GSTHSTRate'])
                ? (float) $this->original_data['Worksheet']['Taxes']['GSTHSTRate'] : 0;

            $this->secondary_tax_rate = !empty($this->original_data['Worksheet']['Taxes']['PSTRate'])
                ? (float) $this->original_data['Worksheet']['Taxes']['PSTRate'] : 0;


            $regionalInstance = RegionFactory::getInstance($region_identifier);

            $tax_rates = $regionalInstance->setTaxRates($this);

            foreach ($tax_rates as $key => $tax_rate) {
                $this->{$key} = $tax_rate;
            }


            if (array_key_exists('PurchaseDetails', $this->original_data['Worksheet'])) {

                $purchase_price = (float) $this->original_data['Worksheet']['PurchaseDetails']['CashPrice'];
                $purchase_price -= (float) $this->original_data['Worksheet']['PurchaseDetails']['Discount'];

                $taxable_purchase_price = $purchase_price;

                if (!empty($this->original_data['Worksheet']['TradeIn']['TradeInAllowance'])) {
                    $taxable_purchase_price -= (float) $this->original_data['Worksheet']['TradeIn']['TradeInAllowance'];
                }

                if (!empty($this->original_data['Worksheet']['Fees']['FactoryRebate'])) {
                    $purchase_price -= (float) $this->original_data['Worksheet']['Fees']['FactoryRebate'];
                }

                $purchase_details = $this->currentLineCalculation(
                    (float) $taxable_purchase_price,
                    $this->tax_type_primary,
                    $this->tax_type_secondary,
                    ['deal_id' => $this->original_data['deal_id']]
                );

                $deal_break_down['deal_id'] =  $purchase_details['deal_id'];
                $deal_break_down['purchase_price'] =  $purchase_price;
                $deal_break_down['purchase_tax_type'] =  $purchase_details['tax_type'];
                $deal_break_down['purchase_tax_percentage'] =  $purchase_details['tax_percentage'];
                $deal_break_down['tax_on_purchase_price'] =  $purchase_details['tax_amount'];
                $deal_break_down['purchase_tax_type_secondary'] =  $purchase_details['tax_type_secondary'];
                $deal_break_down['tax_on_purchase_price_secondary'] =  $purchase_details['tax_amount_secondary'];
                $deal_break_down['purchase_tax_percentage_secondary'] =  $purchase_details['tax_percentage_secondary'];
                $deal_break_down['tax_on_purchase_total'] =  $purchase_details['tax_amount_total'];
            }


            if (array_key_exists('Fees', $this->original_data['Worksheet'])) {
                if (!empty($this->original_data['Worksheet']['Fees']['DownPayment'])) {
                    $downpayment = floatval($this->original_data['Worksheet']['Fees']['DownPayment']);
                    $this->transformed_data['payments'] =
                        [
                            'description' => '',
                            'is_downpayment_or_deposit' => 0,
                            'amount' => $downpayment,
                            'deal_id' => $this->original_data['deal_id']
                        ];
                }

                $this->transformed_data['fees'] = [];

                if (!empty($this->original_data['Worksheet']['Fees']['OtherTaxable'])) {
                    array_push(
                        $this->transformed_data['fees'],
                        $this->currentLineCalculation(
                            (float) $this->original_data['Worksheet']['Fees']['OtherTaxable'],
                            $this->tax_type_primary,
                            $this->tax_type_secondary,
                            ['name' => $this->original_data['Worksheet']['Fees']['OtherTaxableDescription'], 'deal_id' => $this->original_data['deal_id']]
                        )
                    );
                }

                if (!empty($this->original_data['Worksheet']['Fees']['NonTaxableItems'])) {
                    array_push(
                        $this->transformed_data['fees'],
                        $this->currentLineCalculation(
                            (float) $this->original_data['Worksheet']['Fees']['NonTaxableItems'],
                            'Tax Exempt',
                            'Tax Exempt',
                            ['name' => $this->original_data['Worksheet']['Fees']['NonTaxableItemsDescription'], 'deal_id' => $this->original_data['deal_id']]
                        )
                    );
                }


                $taxable_fees_attributes = $this->lenderInstance->getTaxableFees();

                foreach ($taxable_fees_attributes as $taxable_fee_attribute) {
                    if (!empty($this->original_data['Worksheet']['Fees'][$taxable_fee_attribute])) {

                        $fee_name = $taxable_fee_attribute;
                        $fee_name = preg_replace('/([a-z])([A-Z])/s', '$1 $2', $fee_name);

                        array_push(
                            $this->transformed_data['fees'],
                            $this->currentLineCalculation(
                                (float) $this->original_data['Worksheet']['Fees'][$taxable_fee_attribute],
                                $this->tax_type_primary,
                                $this->tax_type_secondary,
                                ['name' => $fee_name, 'deal_id' => $this->original_data['deal_id']]
                            )
                        );
                    }
                }



                $non_taxable_fees_attributes = $this->lenderInstance->getNonTaxableFees();

                foreach ($non_taxable_fees_attributes as $non_taxable_fee_attribute) {
                    if (!empty($this->original_data['Worksheet']['Fees'][$non_taxable_fee_attribute])) {

                        $fee_name = $non_taxable_fee_attribute;
                        $fee_name = preg_replace('/([a-z])([A-Z])/s', '$1 $2', $fee_name);

                        array_push(
                            $this->transformed_data['fees'],
                            $this->currentLineCalculation(
                                (float) $this->original_data['Worksheet']['Fees'][$non_taxable_fee_attribute],
                                'Tax Exempt',
                                'Tax Exempt',
                                ['name' => $fee_name, 'deal_id' => $this->original_data['deal_id']]
                            )
                        );
                    }
                }

                if ($this->lenderInstance->hasLicenceFee()) {
                    $deal_break_down['licence_fee'] =  !empty($this->original_data['Worksheet']['Fees']['LicenseFee']) ? (float) $this->original_data['Worksheet']['Fees']['LicenseFee'] : 0;
                } else {
                    $deal_break_down['licence_fee'] = 0;
                }

                $deal_break_down['lien_registration_fee'] =  !empty($this->original_data['Worksheet']['Fees']['PPSA']) ? (float) $this->original_data['Worksheet']['Fees']['PPSA'] : 0;
            }

            if (array_key_exists('InsurancesAftermarket', $this->original_data['Worksheet'])) {
                $this->transformed_data['warranties'] = $this->transformed_data['insurances'] = [];
                $warranty_attributes = $this->lenderInstance->getTaxableWarranties();
                $insurance_attributes = $this->lenderInstance->getTaxableInsurance();
                $non_taxable_warranty_attributes = $this->lenderInstance->getNonTaxableWarranties();
                $warranty_as_fee_attributes = $this->lenderInstance->getWarrantiesAsFees();

                if ($this->lenderInstance->hasOtherInsurance()) {
                    if (!empty($this->original_data['Worksheet']['InsurancesAftermarket']['OtherInsurances'])) {
                        array_push(
                            $this->transformed_data['insurances'],
                            $this->currentLineCalculation(
                                (float) $this->original_data['Worksheet']['InsurancesAftermarket']['OtherInsurances'],
                                $this->insurance_tax_type_primary,
                                $this->insurance_tax_type_secondary,
                                ['type' => 'Other', 'description' => $this->original_data['Worksheet']['InsurancesAftermarket']['OtherInsurancesDescription'], 'deal_id' => $this->original_data['deal_id']]
                            )
                        );
                    }
                }

                if ($this->original_data['Worksheet']['InsurancesAftermarket']['GapType'] == "3") {
                    array_push($insurance_attributes, 'Gap');
                } else {
                    array_push($warranty_attributes, 'Gap');
                }

                if ($this->original_data['Worksheet']['InsurancesAftermarket']['AntiTheftType'] == "") {
                    array_push($non_taxable_warranty_attributes, 'AntiTheft');
                } else if ($this->original_data['Worksheet']['InsurancesAftermarket']['AntiTheftType'] == "Insurance") {
                    array_push($insurance_attributes, 'AntiTheft');
                } else if ($this->original_data['Worksheet']['InsurancesAftermarket']['AntiTheftType'] == "Warranty") {
                    array_push($warranty_attributes, 'AntiTheft');
                } else if ($this->original_data['Worksheet']['InsurancesAftermarket']['AntiTheftType'] == "3") {
                    array_push($insurance_attributes, 'AntiTheft');
                } else if ($this->original_data['Worksheet']['InsurancesAftermarket']['AntiTheftType'] == "4") {
                    array_push($warranty_attributes, 'AntiTheft');
                }

                foreach ($warranty_attributes as $warranty) {
                    if (!empty($this->original_data['Worksheet']['InsurancesAftermarket'][$warranty])) {

                        $warranty_name = $warranty;
                        $warranty_name = preg_replace('/([a-z])([A-Z])/s', '$1 $2', $warranty_name);

                        array_push(
                            $this->transformed_data[$this->replaceWarrantyAttributeWithFeeAttribute()],
                            $this->currentLineCalculation(
                                (float) $this->original_data['Worksheet']['InsurancesAftermarket'][$warranty],
                                $this->warranty_tax_type_primary,
                                $this->warranty_tax_type_secondary,
                                ['company_name' => $warranty_name, 'name' => $warranty_name, 'deal_id' => $this->original_data['deal_id']]
                            )
                        );
                    }
                }

                foreach ($warranty_as_fee_attributes as $warranty) {
                    if (!empty($this->original_data['Worksheet']['InsurancesAftermarket'][$warranty])) {

                        $warranty_name = $warranty;
                        $warranty_name = preg_replace('/([a-z])([A-Z])/s', '$1 $2', $warranty_name);

                        array_push(
                            $this->transformed_data['fees'],
                            $this->currentLineCalculation(
                                (float) $this->original_data['Worksheet']['InsurancesAftermarket'][$warranty],
                                $this->tax_type_primary,
                                $this->tax_type_secondary,
                                ['company_name' => $warranty_name, 'name' => $warranty_name, 'deal_id' => $this->original_data['deal_id']]
                            )
                        );
                    }
                }

                foreach ($non_taxable_warranty_attributes as $warranty) {
                    if (!empty($this->original_data['Worksheet']['InsurancesAftermarket'][$warranty])) {

                        $warranty_name = $warranty;
                        $warranty_name = preg_replace('/([a-z])([A-Z])/s', '$1 $2', $warranty_name);

                        array_push(
                            $this->transformed_data[$this->replaceWarrantyAttributeWithFeeAttribute()],
                            $this->currentLineCalculation(
                                (float) $this->original_data['Worksheet']['InsurancesAftermarket'][$warranty],
                                'Tax Exempt',
                                'Tax Exempt',
                                ['company_name' => $warranty_name, 'name' => $warranty_name, 'deal_id' => $this->original_data['deal_id']]
                            )
                        );
                    }
                }


                if ($this->lenderInstance->hasOtherAfterMarketFee()) {
                    if (!empty($this->original_data['Worksheet']['InsurancesAftermarket']['Am_OtherNonTaxable'])) {
                        array_push(
                            $this->transformed_data[$this->replaceWarrantyAttributeWithFeeAttribute()],
                            $this->currentLineCalculation(
                                (float) $this->original_data['Worksheet']['InsurancesAftermarket']['Am_OtherNonTaxable'],
                                'Tax Exempt',
                                'Tax Exempt',
                                [
                                    'company_name' => $this->original_data['Worksheet']['InsurancesAftermarket']['Am_OtherNonTaxableDescription'],
                                    'name' => $this->original_data['Worksheet']['InsurancesAftermarket']['Am_OtherNonTaxableDescription'],
                                    'deal_id' => $this->original_data['deal_id']
                                ]
                            )
                        );
                    }

                    if (!empty($this->original_data['Worksheet']['InsurancesAftermarket']['Am_OtherTaxable'])) {
                        array_push(
                            $this->transformed_data[$this->replaceWarrantyAttributeWithFeeAttribute()],
                            $this->currentLineCalculation(
                                (float) $this->original_data['Worksheet']['InsurancesAftermarket']['Am_OtherTaxable'],
                                $this->tax_type_primary,
                                $this->tax_type_secondary,
                                [
                                    'company_name' => $this->original_data['Worksheet']['InsurancesAftermarket']['Am_OtherTaxableDescription'],
                                    'name' => $this->original_data['Worksheet']['InsurancesAftermarket']['Am_OtherTaxableDescription'],
                                    'deal_id' => $this->original_data['deal_id']
                                ]
                            )
                        );
                    }
                }



                foreach ($insurance_attributes as $insurance) {
                    if (!empty($this->original_data['Worksheet']['InsurancesAftermarket'][$insurance])) {

                        $insurance_name = $insurance;
                        $insurance_name = preg_replace('/([a-z])([A-Z])/s', '$1 $2', $insurance_name);

                        array_push(
                            $this->transformed_data['insurances'],
                            $this->currentLineCalculation(
                                (float) $this->original_data['Worksheet']['InsurancesAftermarket'][$insurance],
                                $this->insurance_tax_type_primary,
                                $this->insurance_tax_type_secondary,
                                ['type' => $this->getInsuranceType($insurance_name), 'description' => $insurance_name, 'deal_id' => $this->original_data['deal_id']]
                            )
                        );
                    }
                }
            }


            if (array_key_exists('TradeIn', $this->original_data['Worksheet'])) {
                if (!empty($this->original_data['Worksheet']['TradeIn'])) {
                    $trade_in = [];
                    $trade_in['deal_id'] = $this->original_data['deal_id'];
                    $deal_break_down['trade_value'] = $trade_in['trade_value'] = $this->original_data['Worksheet']['TradeIn']['TradeInAllowance'];
                    $deal_break_down['lien_payout'] = $trade_in['lien_value'] = $this->original_data['Worksheet']['TradeIn']['LienAmount'];
                    $deal_break_down['trade_equity'] = $trade_in['trade_equity'] = $this->original_data['Worksheet']['TradeIn']['NetTradeInAllowance'];
                    $trade_in['lien_holder'] = $this->original_data['Worksheet']['TradeIn']['BalanceOwedTo'];
                    $trade_in['vin'] = !empty($this->original_data['TradeIn']['VIN']) ? $this->original_data['TradeIn']['VIN'] : strtoupper(Utility::randomString(6));

                    if (array_key_exists('TradeIn', $this->original_data)) {
                        $trade_in['year'] = $this->original_data['TradeIn']['Year'];
                        $trade_in['make'] = $this->original_data['TradeIn']['Make'];
                        $trade_in['model'] = $this->original_data['TradeIn']['Model'];
                        $trade_in['trim'] = $this->original_data['TradeIn']['BodyStyle'];
                        $trade_in['status'] = "Unpublished";
                        $trade_in['sub_status'] = "Reconditioning";
                        $trade_in['title'] = $this->configuration_repository->findConfigurationColumnsByKey('editor_default_title', 'value')->value;
                        $trade_in['km_miles'] = 'KM';
                        $trade_in['vehicle_description'] = $this->configuration_repository->findConfigurationColumnsByKey('editor_default_vehicle_description', 'value')->value;
                        $trade_in['feature_listing'] = "0";
                        $trade_in['vehicle_type'] = "Used";
                        $trade_in['visibility'] = "1";
                        $trade_in['vehicle_tags'] = "[]";
                        $trade_in['odometer'] = is_numeric($this->original_data['TradeIn']['Odometer']) ? $this->original_data['TradeIn']['Odometer'] : 0;
                        $trade_in['stock_no'] =  $this->inventory_repository->getAvailableStockNumber($trade_in);
                        $trade_in['special_price'] = $trade_in['price'] = 0;
                    }
                }

                $this->transformed_data['tradein'] = $trade_in;
            }

            //Calculate SubTotal By Foreach
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


            if (array_key_exists('FinancingTerms', $this->original_data['Worksheet'])) {
                if (!empty($this->original_data['Worksheet']['FinancingTerms'])) {
                    $finance_entity = [];
                    $finance_entity['total_amount'] = $total_balance_due;
                    $finance_entity['deal_id'] = $this->original_data['deal_id'];
                    $finance_entity['override_finance_calculation'] = 1;
                    $finance_entity['interest_rate'] = $this->original_data['Worksheet']['FinancingTerms']['ActualInterestRate'];
                    $finance_entity['term'] = $this->original_data['Worksheet']['FinancingTerms']['Term'];
                    $finance_entity['payment_frequency'] = $this->setPaymentFrequency($this->original_data['Worksheet']['FinancingTerms']['PaymentFrequency']);
                    $finance_entity['interest_amount'] = $this->original_data['Worksheet']['FinancingTerms']['CostOfBorrowing'];
                    $finance_entity['emi_amount'] = $this->original_data['Worksheet']['FinancingTerms']['InstallmentPayment'];
                    $finance_entity['interest_rate_type'] = 'Fixed';
                    $finance_entity['lien_holder_name'] = $this->original_data['Worksheet']['FinancingTerms']['LenderCode'];

                    if (!empty($this->original_data['Worksheet']['PaymentDates'])) {
                        if (isset($this->original_data['Worksheet']['PaymentDates']['FirstPaymentDate']) && strtotime($this->original_data['Worksheet']['PaymentDates']['FirstPaymentDate']) == true) {
                            $first_payment_date = Carbon::parse($this->original_data['Worksheet']['PaymentDates']['FirstPaymentDate']);
                            $finance_entity['first_payment_date'] = $first_payment_date->format('m/d/Y');
                        }
                    }

                    $this->transformed_data['finance'] = $finance_entity;

                    if ($this->does_balance_cost_includes_interest) {
                        $total_balance_due += (float) $finance_entity['interest_amount'];
                    }
                }
            }

            $deal_break_down['total_balance_due'] = $total_balance_due;
            $this->transformed_data['dealbreakdown'] = $deal_break_down;
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

    protected function getInsuranceType(String $insurance_name): String
    {
        switch ($insurance_name) {
            case 'LifeInsurance':
                return 'Life';
                break;
            case 'CriticalIllness':
                return 'Sickness';
                break;
            case 'Gap':
                return 'GAP';
                break;
            default:
                return 'Other';
                break;
        }
    }

    protected function replaceWarrantyAttributeWithFeeAttribute(): String
    {
        ++$this->warranty_count;
        return $this->warranty_count <= 1 ? 'warranties' : 'fees';
    }

    /**
     * Get the value of original_data
     */
    public function getOriginalData(): array
    {
        return $this->original_data;
    }

    private function mapInventory($payload): array
    {
        $inventory = [];
        $inventory['year'] = $payload['Year'];
        $inventory['make'] = $payload['Make'];
        $inventory['model'] = $payload['Model'];
        $inventory['trim'] = $payload['Series'];
        $inventory['body_type'] = $payload['BodyStyle'];
        $inventory['vin'] = $payload['VIN'];
        $inventory['status'] = 'Instock';
        $inventory['sub_status'] = 'Deal Pending';
        $inventory['title'] = $this->configuration_repository->findConfigurationColumnsByKey('editor_default_title', 'value')->value;
        $inventory['odometer'] = is_numeric($payload['Odometer']) ? $payload['Odometer'] : '';
        $inventory['km_miles'] = 'KM';
        $inventory['vehicle_description'] = $this->configuration_repository->findConfigurationColumnsByKey('editor_default_vehicle_description', 'value')->value;
        $inventory['price'] = 0;
        $inventory['special_price'] = 0;
        $inventory['visibility'] = "0";
        $inventory['stock_date'] = Carbon::now()->format('Y-m-d');
        $inventory['stock_no'] = !empty($payload['StockNumber']) ? $payload['StockNumber'] : $this->inventory_repository->getAvailableStockNumber($inventory);

        return $inventory;
    }

    private function mapCustomer($payload): array
    {
        $customer = [];
        $customer['first_name'] = $payload['FirstName'];
        $customer['last_name'] = $payload['LastName'];
        $customer['rin_or_dl_flag'] = '0';
        $customer['customer_type'] = '0';
        $customer['driver_licence_expiry_date'] = '';

        $customer['date_of_birth'] = '';

        if (array_key_exists('AdditionalInfo', $payload)) {
            if (!empty($payload['AdditionalInfo']['DateOfBirth']) && strtotime($payload['AdditionalInfo']['DateOfBirth']) == true) {
                $date_of_birth = Carbon::parse($payload['AdditionalInfo']['DateOfBirth']);
                $customer['date_of_birth'] = $date_of_birth->format('m/d/Y');
            }
        }

        $customer['phone'] = $payload['Phone'];
        $customer['fax'] = '';
        $customer['mobile'] = $payload['MobilePhone'];
        $customer['email'] = $payload['Email'];

        if (array_key_exists('CurrentAddress', $payload)) {
            $customer = $this->mapAddress($customer, $payload['CurrentAddress']);
        }

        return $customer;
    }

    private function mapCustomerCreditApplication($payload): array
    {
        $credit_application = [];

        if (array_key_exists('AdditionalInfo', $payload)) {
            $credit_application['salutation'] = $this->getSalutation($payload['AdditionalInfo']['Salutation']);
            $credit_application['gender'] = $this->getGender($payload['AdditionalInfo']['Gender']);
            $credit_application['marital_status'] = $this->getMaritalStatus($payload['AdditionalInfo']['MaritalStatus']);
        }

        if (array_key_exists('HomeMortgageDetails', $payload)) {
            $credit_application['residence_ownership'] = $this->getHomeOwnershipType($payload['HomeMortgageDetails']['HomeOwnershipType']);
            $credit_application['market_value'] = is_numeric($payload['HomeMortgageDetails']['PropertyValue']) ? $payload['HomeMortgageDetails']['PropertyValue'] : 0;
            $credit_application['mortgage_amount'] = is_numeric($payload['HomeMortgageDetails']['HomeMortgageAmount']) ? $payload['HomeMortgageDetails']['HomeMortgageAmount'] : 0;
            $credit_application['property_owner'] = $payload['HomeMortgageDetails']['HomeMortgageOwnership'];
            $credit_application['monthly_payment'] = is_numeric($payload['HomeMortgageDetails']['HomeMonthlyPayment']) ? $payload['HomeMortgageDetails']['HomeMonthlyPayment'] : 0;
        }

        if (array_key_exists('CurrentAddress', $payload)) {
            $credit_application['years_at_present_address'] = is_numeric($payload['CurrentAddress']['YearsAtAddress']) ? $payload['CurrentAddress']['YearsAtAddress'] : 0;
        }

        if (array_key_exists('PreviousAddress', $payload)) {
            $credit_application = $this->mapAddress($credit_application, $payload['PreviousAddress'], 'previous_address_');
            $credit_application['previous_residence_address_period_years'] = $payload['PreviousAddress']['YearsAtAddress'];
            $credit_application['previous_residence_period_months'] = $payload['PreviousAddress']['MonthsAtAddress'];
        }

        if (array_key_exists('CurrentEmployment', $payload)) {
            $credit_application['employment_type'] = $this->getEmploymentStatus($payload['CurrentEmployment']['EmploymentStatus']);
            $credit_application['employer_name'] = $payload['CurrentEmployment']['EmployerName'];
            $credit_application['occupation'] = $payload['CurrentEmployment']['Occupation'];
            $credit_application['employer_work_phone'] = $payload['CurrentEmployment']['EmployerPhone'];
            $credit_application['employer_work_phone_ext'] = $payload['CurrentEmployment']['EmployerPhoneExt'];
            $credit_application['employment_period_years'] = $payload['CurrentEmployment']['EmployerDurationYears'];
            $credit_application['employment_period_months'] = $payload['CurrentEmployment']['EmployerDurationMonths'];

            $credit_application = $this->mapAddress($credit_application, $payload['CurrentEmployment']['Address'], 'employer_');
        }

        if (array_key_exists('PreviousEmployment', $payload)) {
            $credit_application['previous_employment_type'] = $this->getEmploymentStatus($payload['PreviousEmployment']['EmploymentStatus']);
            $credit_application['previous_employer_name'] = $payload['PreviousEmployment']['EmployerName'];
            $credit_application['previous_occupation'] = $payload['PreviousEmployment']['Occupation'];
            $credit_application['previous_employer_work_phone'] = $payload['PreviousEmployment']['EmployerPhone'];
            $credit_application['previous_employer_work_phone_ext'] = $payload['PreviousEmployment']['EmployerPhoneExt'];
            $credit_application['previous_employment_period_years'] = $payload['PreviousEmployment']['EmployerDurationYears'];
            $credit_application['previous_employment_period_months'] = $payload['PreviousEmployment']['EmployerDurationMonths'];

            $credit_application = $this->mapAddress($credit_application, $payload['PreviousEmployment']['Address'], 'previous_employer_');
        }

        if (array_key_exists('IncomeDetails', $payload)) {
            $credit_application['other_income_source'] = $payload['IncomeDetails']['OtherIncomeDescription'];
            $credit_application['other_annual_gross'] = $this->getAnnualIncome($payload['IncomeDetails']['OtherIncomeFrequency'], $payload['IncomeDetails']['OtherIncomeAmount']);
            $credit_application['annual_gross'] = $this->getAnnualIncome($payload['IncomeDetails']['GrossIncomeFrequency'], $payload['IncomeDetails']['GrossIncomeAmount']);
        }

        return $credit_application;
    }

    public function getPrimaryTaxRate(): float
    {
        return $this->primary_tax_rate;
    }

    public function getSecondaryTaxRate(): float
    {
        return $this->secondary_tax_rate;
    }

    private function mapAddress($entity, $payload, $prefix = ''): array
    {
        $entity[$prefix . 'street_address'] = $payload['StreetNo'] . ' ' . $payload['StreetName'];

        if (!empty($prefix)) {
            $entity[preg_replace('/_$/', '', $prefix)] = $payload['StreetNo'] . ' ' . $payload['StreetName'];
            $entity[$prefix . 'address'] = $payload['StreetNo'] . ' ' . $payload['StreetName'];
        }

        $entity[$prefix . 'apt'] = $payload['SuiteNo'];
        $entity[$prefix . 'city'] = $payload['City'];
        $entity[$prefix . 'province'] = !empty($payload['Province']) ? str_replace('CA-', '', $payload['Province']) : '';
        $entity[$prefix . 'postal_code'] = $payload['PostalCode'];
        $entity[$prefix . 'country'] = '';
        return $entity;
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

    private function getAnnualIncome($period, $amount)
    {
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
    }
}
