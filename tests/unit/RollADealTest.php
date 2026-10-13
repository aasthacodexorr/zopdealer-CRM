<?php

use Tenant\app\Entities\DealWorksheet;
use Tenant\app\Libraries\RollADeal\AdjustorFactory;

class RollADealTest extends \CodeIgniter\Test\CIUnitTestCase
{

    /**
     * adjustor_factory This Holds the Factory of Roll A Deal Service
     *
     * @var mixed
     */
    private $adjustor_factory;

    public function setUp(): void
    {
        parent::setUp();
        $this->adjustor_factory = new AdjustorFactory();
    }


    public function tearDown(): void
    {
        parent::tearDown();
        $this->adjustor_factory = NULL;
    }

    /**
     * test_calculations_to_adjust_selling_price_based_on_emi_on_monthly_basis  
     * Given a specific EMI, we need to calculate the Selling Price
     *
     * @return void
     */
    public function test_calculations_to_adjust_selling_price_based_on_emi_on_monthly_basis()
    {

        $data = [
            'adjust_amount'  => '1000',
            'adjust_column'  => 'purchase_price',
            'request_column' => 'emi_amount',
            'deal_type'      => 'Financed',
            'retail_or_wholesale_flag'   => '0',
            'total_balance_cost_includes_interest'   => '0',
            'emi_amount'     => '1161.16',
            'term'           => '60',
            'interest_rate'  => '12.00',
            'override_finance_calculation'   => '0',
            'payment_frequency'  => 'Monthly',
            'total_balance_due'  => '52200.00',
            'trade_value'        => '10000',
            'purchase_tax_percentage'    => '13.00',
            'purchase_tax_percentage_secondary'  => '5.00',
            'purchase_price'     => '50000.00',
            'finance_amount'     => '52200.00'
        ];


        $worksheet = new DealWorksheet($data);

        $adjustor = $this->adjustor_factory->getInstance($data['request_column'], $data['adjust_column']);

        $this->assertCloseEnough($adjustor->adjust($worksheet), 43860.2);
    }

    /**
     * test_calculations_to_adjust_selling_price_based_on_emi_on_biweekly_basis  
     * Given a specific EMI, we need to calculate the Selling Price
     *
     * @return void
     */
    public function test_calculations_to_adjust_selling_price_based_on_emi_on_biweekly_basis()
    {

        $data = [
            'adjust_amount'  => '1000',
            'adjust_column'  => 'purchase_price',
            'request_column' => 'emi_amount',
            'deal_type'      => 'Financed',
            'retail_or_wholesale_flag'   => '0',
            'total_balance_cost_includes_interest'   => '0',
            'emi_amount'     => '534.87',
            'term'           => '60',
            'interest_rate'  => '12.00',
            'override_finance_calculation'   => '0',
            'payment_frequency'  => 'Bi-Weekly',
            'total_balance_due'  => '52200.00',
            'trade_value'        => '10000',
            'purchase_tax_percentage'    => '13.00',
            'purchase_tax_percentage_secondary'  => '5.00',
            'purchase_price'     => '50000.00',
            'finance_amount'     => '52200.00'
        ];


        $worksheet = new DealWorksheet($data);

        $adjustor = $this->adjustor_factory->getInstance($data['request_column'], $data['adjust_column']);

        $this->assertCloseEnough($adjustor->adjust($worksheet), 88468.84);
    }

    /**
     * test_calculations_to_adjust_selling_price_based_on_emi_on_weekly_basis  
     * Given a specific EMI, we need to calculate the Selling Price
     *
     * @return void
     */
    public function test_calculations_to_adjust_selling_price_based_on_emi_on_weekly_basis()
    {

        $data = [
            'adjust_amount'  => '1000',
            'adjust_column'  => 'purchase_price',
            'request_column' => 'emi_amount',
            'deal_type'      => 'Financed',
            'retail_or_wholesale_flag'   => '0',
            'total_balance_cost_includes_interest'   => '0',
            'emi_amount'     => '267.21',
            'term'           => '60',
            'interest_rate'  => '12.00',
            'override_finance_calculation'   => '0',
            'payment_frequency'  => 'Weekly',
            'total_balance_due'  => '52200.00',
            'trade_value'        => '10000',
            'purchase_tax_percentage'    => '13.00',
            'purchase_tax_percentage_secondary'  => '5.00',
            'purchase_price'     => '50000.00',
            'finance_amount'     => '52200.00'
        ];


        $worksheet = new DealWorksheet($data);

        $adjustor = $this->adjustor_factory->getInstance($data['request_column'], $data['adjust_column']);

        $this->assertCloseEnough($adjustor->adjust($worksheet), 171313.99);
    }

    /**
     * test_calculations_to_adjust_term_based_on_emi_on_monthly_basis  
     * Given a specific EMI, we need to calculate the Term
     *
     * @return void
     */
    public function test_calculations_to_adjust_term_based_on_emi_on_monthly_basis()
    {

        $data = [
            'adjust_amount'  => '1000',
            'adjust_column'  => 'term',
            'request_column' => 'emi_amount',
            'deal_type'      => 'Financed',
            'retail_or_wholesale_flag'   => '0',
            'total_balance_cost_includes_interest'   => '0',
            'emi_amount'     => '1161.16',
            'term'           => '60',
            'interest_rate'  => '12.00',
            'override_finance_calculation'   => '0',
            'payment_frequency'  => 'Monthly',
            'total_balance_due'  => '52200.00',
            'trade_value'        => '10000',
            'purchase_tax_percentage'    => '13.00',
            'purchase_tax_percentage_secondary'  => '5.00',
            'purchase_price'     => '50000.00',
            'finance_amount'     => '52200.00'
        ];


        $worksheet = new DealWorksheet($data);

        $adjustor = $this->adjustor_factory->getInstance($data['request_column'], $data['adjust_column']);

        $this->assertCloseEnough($adjustor->adjust($worksheet), 74);
    }


    /**
     * test_calculations_to_adjust_term_based_on_emi_on_biweekly_basis  
     * Given a specific EMI, we need to calculate the Term
     *
     * @return void
     */
    public function test_calculations_to_adjust_term_based_on_emi_on_biweekly_basis()
    {

        $data = [
            'adjust_amount'  => '1000',
            'adjust_column'  => 'term',
            'request_column' => 'emi_amount',
            'deal_type'      => 'Financed',
            'retail_or_wholesale_flag'   => '0',
            'total_balance_cost_includes_interest'   => '0',
            'emi_amount'     => '534.87',
            'term'           => '60',
            'interest_rate'  => '12.00',
            'override_finance_calculation'   => '0',
            'payment_frequency'  => 'Bi-Weekly',
            'total_balance_due'  => '52200.00',
            'trade_value'        => '10000',
            'purchase_tax_percentage'    => '13.00',
            'purchase_tax_percentage_secondary'  => '5.00',
            'purchase_price'     => '50000.00',
            'finance_amount'     => '52200.00'
        ];


        $worksheet = new DealWorksheet($data);

        $adjustor = $this->adjustor_factory->getInstance($data['request_column'], $data['adjust_column']);

        $this->assertCloseEnough($adjustor->adjust($worksheet), 27);
    }

    /**
     * test_calculations_to_adjust_term_based_on_emi_on_weekly_basis  
     * Given a specific EMI, we need to calculate the Term
     *
     * @return void
     */
    public function test_calculations_to_adjust_term_based_on_emi_on_weekly_basis()
    {

        $data = [
            'adjust_amount'  => '1000',
            'adjust_column'  => 'term',
            'request_column' => 'emi_amount',
            'deal_type'      => 'Financed',
            'retail_or_wholesale_flag'   => '0',
            'total_balance_cost_includes_interest'   => '0',
            'emi_amount'     => '267.21',
            'term'           => '60',
            'interest_rate'  => '12.00',
            'override_finance_calculation'   => '0',
            'payment_frequency'  => 'Weekly',
            'total_balance_due'  => '52200.00',
            'trade_value'        => '10000',
            'purchase_tax_percentage'    => '13.00',
            'purchase_tax_percentage_secondary'  => '5.00',
            'purchase_price'     => '50000.00',
            'finance_amount'     => '52200.00'
        ];


        $worksheet = new DealWorksheet($data);

        $adjustor = $this->adjustor_factory->getInstance($data['request_column'], $data['adjust_column']);

        $this->assertCloseEnough($adjustor->adjust($worksheet), 12);
    }


    /**
     * test_adjustor_should_not_be_used_if_financing_is_overriden
     * This test makes sure calculations do not take place if the auto calculation is overriden 
     *
     * @expectedException Exception
     */
    public function test_adjustor_should_not_be_used_if_financing_is_overriden()
    {
        $data = [
            'adjust_amount'  => '1000',
            'adjust_column'  => 'purchase_price',
            'request_column' => 'emi_amount',
            'deal_type'      => 'Financed',
            'retail_or_wholesale_flag'   => '0',
            'total_balance_cost_includes_interest'   => '0',
            'emi_amount'     => '1161.16',
            'term'           => '60',
            'interest_rate'  => '12.00',
            'override_finance_calculation'   => '1',
            'payment_frequency'  => 'Monthly',
            'total_balance_due'  => '52200.00',
            'trade_value'        => '10000',
            'purchase_tax_percentage'    => '13.00',
            'purchase_tax_percentage_secondary'  => '5.00',
            'purchase_price'     => '50000.00',
            'finance_amount'     => '52200.00'
        ];


        $worksheet = new DealWorksheet($data);

        $adjustor = $this->adjustor_factory->getInstance($data['request_column'], $data['adjust_column']);

        $adjustor->adjust($worksheet);

        $this->expectException(Exception::class);
        $this->expectExceptionCode(400);
    }

    /**
     * test_finance_based_adjustor_should_not_be_used_if_deal_is_cash
     * This test makes sure calculations do not take place if the deal type is cash
     *
     * @expectedException Exception
     */
    public function test_finance_based_adjustor_should_not_be_used_if_deal_is_cash()
    {
        $data = [
            'adjust_amount'  => '1000',
            'adjust_column'  => 'purchase_price',
            'request_column' => 'emi_amount',
            'deal_type'      => 'Cash',
            'retail_or_wholesale_flag'   => '0',
            'total_balance_cost_includes_interest'   => '0',
            'emi_amount'     => '1161.16',
            'term'           => '60',
            'interest_rate'  => '12.00',
            'override_finance_calculation'   => '1',
            'payment_frequency'  => 'Monthly',
            'total_balance_due'  => '52200.00',
            'trade_value'        => '10000',
            'purchase_tax_percentage'    => '13.00',
            'purchase_tax_percentage_secondary'  => '5.00',
            'purchase_price'     => '50000.00',
            'finance_amount'     => '52200.00'
        ];


        $worksheet = new DealWorksheet($data);

        $adjustor = $this->adjustor_factory->getInstance($data['request_column'], $data['adjust_column']);

        $adjustor->adjust($worksheet);

        $this->expectException(Exception::class);
        $this->expectExceptionCode(400);
    }


    /**
     * test_calculations_to_adjust_selling_price_based_on_total_balance_due  
     * Given a specific Total Balance Due, we need to calculate the Selling Price
     *
     * @return void
     */
    public function test_calculations_to_adjust_selling_price_based_on_total_balance_due()
    {

        $data = [
            'adjust_amount'  => '35000',
            'adjust_column'  => 'purchase_price',
            'request_column' => 'total_balance_due',
            'deal_type'      => 'Cash',
            'retail_or_wholesale_flag'   => '0',
            'total_balance_cost_includes_interest'   => '0',
            'emi_amount'     => '0',
            'term'           => '0',
            'interest_rate'  => '0',
            'override_finance_calculation'   => '0',
            'payment_frequency'  => 'Monthly',
            'total_balance_due'  => '52200.00',
            'trade_value'        => '10000',
            'purchase_tax_percentage'    => '13.00',
            'purchase_tax_percentage_secondary'  => '5.00',
            'purchase_price'     => '50000.00',
            'finance_amount'     => '52200.00'
        ];


        $worksheet = new DealWorksheet($data);

        $adjustor = $this->adjustor_factory->getInstance($data['request_column'], $data['adjust_column']);

        $this->assertCloseEnough($adjustor->adjust($worksheet), 35423.73);
    }
}
