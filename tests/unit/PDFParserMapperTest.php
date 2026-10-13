<?php

use Tenant\app\Libraries\PDFParser\PDFParserFactory;

class PDFParserMapperTest extends \CodeIgniter\Test\CIUnitTestCase
{
    /**
     * pdf_parser This Holds the Factory of PDF Parser Factory
     *
     * @var mixed
     */
    private $pdf_parser;

    private $trade_rev_success_payload;

    private $openlane_v1_success_payload;

    public function setUp(): void
    {
        parent::setUp();
        $this->pdf_parser = new PDFParserFactory();
        $this->trade_rev_success_payload = '{
	"status": "success",
	"reason": "",
	"invoiceData": {
		"sellerName": "TRADEREV",
		"sellerAddress": {
			"street": "370 King Street W, Suite 600",
			"city": "Toronto",
			"province": "ON",
			"postalCode": "M5V 1J9"
		},
		"invoiceNumber": "",
		"invoiceDate": "2025-01-10",
		"lineItems": [
			{
				"lineDescription": "Shipping Fee",
				"taxRate1": 13.00,
				"taxRate2": 0,
				"qty": 1,
				"rate": 170.00,
				"amount": 170.00
			},
			{
				"lineDescription": "HST (Shipping Fee)",
				"taxRate1": 13.00,
				"taxRate2": 0,
				"qty": 1,
				"rate": 22.10,
				"amount": 22.10
			},
			{
				"lineDescription": "Vehicle History Fee",
				"taxRate1": 13.00,
				"taxRate2": 0,
				"qty": 1,
				"rate": 44.54,
				"amount": 44.54
			},
			{
				"lineDescription": "HST (Vehicle History Fee)",
				"taxRate1": 13.00,
				"taxRate2": 0,
				"qty": 1,
				"rate": 5.79,
				"amount": 5.79
			},
			{
				"lineDescription": "Buyer Purchase Price",
				"taxRate1": 13.00,
				"taxRate2": 0,
				"qty": 1,
				"rate": 7300.00,
				"amount": 7300.00
			},
			{
				"lineDescription": "HST (Buyer Purchase Price)",
				"taxRate1": 13.00,
				"taxRate2": 0,
				"qty": 1,
				"rate": 949.00,
				"amount": 949.00
			},
			{
				"lineDescription": "Transaction Fee",
				"taxRate1": 13.00,
				"taxRate2": 0,
				"qty": 1,
				"rate": 273.00,
				"amount": 273.00
			},
			{
				"lineDescription": "HST (Transaction Fee)",
				"taxRate1": 13.00,
				"taxRate2": 0,
				"qty": 1,
				"rate": 35.49,
				"amount": 35.49
			}
		],
		"inventory": {
			"vin": "5FNYF4H59DB504501",
			"year": "2013",
			"make": "Honda",
			"model": "Pilot",
			"trim": "",
			"odometer": 158849,
			"interior_color": "Grey",
			"exterior_color": "Grey"
		}
	}
}';

        $this->openlane_v1_success_payload = '{
	"status": "success",
	"reason": "",
	"invoiceData": {
		"sellerName": "NthGen Software Inc. O/A OPENLANE Canada",
		"sellerAddress": {
			"street": "370 King Street W, Suite 600",
			"city": "Toronto",
			"province": "ON",
			"postalCode": "M5V 1J9"
		},
		"invoiceNumber": "",
		"invoiceDate": "2025-05-20",
		"lineItems": [
			{
				"lineDescription": "Transaction Fee",
				"taxRate1": 13,
				"taxRate2": 0,
				"qty": 1,
				"rate": 515,
				"amount": 515
			},
			{
				"lineDescription": "HST on Transaction Fee",
				"taxRate1": 0,
				"taxRate2": 0,
				"qty": 1,
				"rate": 66.95,
				"amount": 66.95
			},
			{
				"lineDescription": "Vehicle History Fee",
				"taxRate1": 13,
				"taxRate2": 0,
				"qty": 1,
				"rate": 44.54,
				"amount": 44.54
			},
			{
				"lineDescription": "HST on Vehicle History Fee",
				"taxRate1": 0,
				"taxRate2": 0,
				"qty": 1,
				"rate": 5.79,
				"amount": 5.79
			},
			{
				"lineDescription": "Shipping Fee",
				"taxRate1": 13,
				"taxRate2": 0,
				"qty": 1,
				"rate": 150,
				"amount": 150
			},
			{
				"lineDescription": "HST on Shipping Fee",
				"taxRate1": 0,
				"taxRate2": 0,
				"qty": 1,
				"rate": 19.5,
				"amount": 19.5
			},
			{
				"lineDescription": "Buyer Purchase Price",
				"taxRate1": 13,
				"taxRate2": 0,
				"qty": 1,
				"rate": 29800,
				"amount": 29800
			},
			{
				"lineDescription": "HST on Buyer Purchase Price",
				"taxRate1": 0,
				"taxRate2": 0,
				"qty": 1,
				"rate": 3874,
				"amount": 3874
			}
		],
		"inventory": {
			"vin": "WA1EAAFY9N2058247",
			"year": "2022",
			"make": "AUDI",
			"model": "Q5",
			"trim": "",
			"odometer": 72247,
			"interior_color": "Black",
			"exterior_color": "WHITE"
		}
	}
}';
    }


    public function tearDown(): void
    {
        parent::tearDown();
        $this->pdf_parser = NULL;
        $this->trade_rev_success_payload = NULL;
        $this->openlane_v1_success_payload = NULL;
    }

    public function test_purchase_price_for_response_mapping_from_traderrev_for_a_valid_json()
    {

        $json_payload = json_decode($this->trade_rev_success_payload, true);
        $pdf_parser_instance = $this->pdf_parser::getInstance("traderev");
        $response = $pdf_parser_instance->mapResponse($json_payload);
        $this->assertArrayHasKey('inventory_purchase', $response);
        $this->assertArraySubset(['inventory_purchase' => ['price' => 7300]], $response);
    }

    public function test_inventory_expenses_for_response_mapping_from_traderrev_for_a_valid_json()
    {

        $json_payload = json_decode($this->trade_rev_success_payload, true);
        $pdf_parser_instance = $this->pdf_parser::getInstance("traderev");
        $response = $pdf_parser_instance->mapResponse($json_payload);
        $this->assertArrayHasKey('inventory_expenses', $response);
        $this->assertArraySubset(['inventory_expenses' => [
            [
                'purchase_date' => '01/10/2025',
                'vendor_name' => 'TRADEREV',
                'name' => 'Shipping Fee',
                'invoice' => '',
                'price'    => 170,
                'quantity' =>  1
            ],
            [
                'purchase_date' => '01/10/2025',
                'vendor_name' => 'TRADEREV',
                'name' => 'Vehicle History Fee',
                'invoice' => '',
                'price'    => 44.54,
                'quantity' =>  1
            ],
            [
                'purchase_date' => '01/10/2025',
                'vendor_name' => 'TRADEREV',
                'name' => 'Transaction Fee',
                'invoice' => '',
                'price'    => 273,
                'quantity' =>  1
            ]
        ]], $response);
    }

    public function test_purchase_price_for_response_mapping_from_openlane_v1_for_a_valid_json()
    {

        $json_payload = json_decode($this->openlane_v1_success_payload, true);
        $pdf_parser_instance = $this->pdf_parser::getInstance("openlane");
        $response = $pdf_parser_instance->mapResponse($json_payload);
        $this->assertArrayHasKey('inventory_purchase', $response);
        $this->assertArraySubset(['inventory_purchase' => ['price' => 29800]], $response);
    }

    public function test_inventory_expenses_for_response_mapping_from_openlane_v1_for_a_valid_json()
    {

        $json_payload = json_decode($this->openlane_v1_success_payload, true);
        $pdf_parser_instance = $this->pdf_parser::getInstance("openlane");
        $response = $pdf_parser_instance->mapResponse($json_payload);
        $this->assertArrayHasKey('inventory_expenses', $response);
        $this->assertArraySubset(['inventory_expenses' => [
            [
                'purchase_date' => '05/20/2025',
                'vendor_name' => 'NthGen Software Inc. O/A OPENLANE Canada',
                'name' => 'Transaction Fee',
                'invoice' => '',
                'price'    => 515,
                'quantity' =>  1
            ],
            [
                'purchase_date' => '05/20/2025',
                'vendor_name' => 'NthGen Software Inc. O/A OPENLANE Canada',
                'name' => 'Vehicle History Fee',
                'invoice' => '',
                'price'    => 44.54,
                'quantity' =>  1
            ],
            [
                'purchase_date' => '05/20/2025',
                'vendor_name' => 'NthGen Software Inc. O/A OPENLANE Canada',
                'name' => 'Shipping Fee',
                'invoice' => '',
                'price'    => 150,
                'quantity' =>  1
            ]
        ]], $response);
    }
}
