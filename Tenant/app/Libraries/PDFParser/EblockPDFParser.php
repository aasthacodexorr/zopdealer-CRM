<?php

namespace Tenant\app\Libraries\PDFParser;

use Carbon\Carbon;

class EblockPDFParser extends InventoryPDFParserAbstract implements PDFParserInterface
{
    protected string $path = '/services/pdf/extract/eblock';

    public function mapResponse(array $data): array
    {
        $response = [];
        $response['has_inventory_purchase'] = false;
        $response['has_inventory_expenses'] = false;
        $response['inventory_purchase'] = [];
        $response['inventory_expenses'] = [];
        $vendor_name = '';
        $expenses_count = 0;
        $purchase_date = strtotime($data['invoiceData']['invoiceDate']) ? Carbon::parse($data['invoiceData']['invoiceDate'])->format('m/d/Y') :
            Carbon::now()->format('m/d/Y');

        if (!empty($data['invoiceData'])) {
            if (!empty($data['invoiceData']['inventory'])) {
                $inventory = $data['invoiceData']['inventory'];
                $response['vin'] = $inventory['vin'];
                $response['year'] = $inventory['year'];
                $response['make'] = $inventory['make'];
                $response['model'] = $inventory['model'];
                $response['trim'] = $inventory['trim'];
                $response['odometer'] = $inventory['odometer'];
                $response['interior_color'] = $response['exterior_color'] = "";
            }
        }

        if (!empty($data['invoiceData']['sellerName'])) {
            $response['has_inventory_purchase'] = true;
            $response['inventory_purchase']['company_name'] = $data['invoiceData']['sellerName'];
            $response['inventory_purchase']['first_name'] = $data['invoiceData']['sellerName'];
            $vendor_name = $data['invoiceData']['sellerName'];
            $response['inventory_purchase']['purchase_date'] = $purchase_date;
        }

        if (!empty($data['invoiceData']['sellerAddress'])) {
            $response['has_inventory_purchase'] = true;
            $response['inventory_purchase']['street_address'] = $data['invoiceData']['sellerAddress']['street'];
            $response['inventory_purchase']['city'] = $data['invoiceData']['sellerAddress']['city'];
            $response['inventory_purchase']['province'] = $data['invoiceData']['sellerAddress']['province'];
            $response['inventory_purchase']['postal_code'] = $data['invoiceData']['sellerAddress']['postalCode'];
        }

        if (!empty($data['invoiceData']['lineItems'])) {
            foreach ($data['invoiceData']['lineItems'] as $line_item_index => $line_item) {
                if ($line_item_index === 0) {
                    $has_purchase_pricing_information = stripos($line_item['lineDescription'], 'purchase');
                    if ($has_purchase_pricing_information !== false) {
                        $response['inventory_purchase']['price'] = $line_item['rate'];
                    }
                } else {
                    $is_tax_description = stripos($line_item['lineDescription'], 'tax');
                    if ($is_tax_description === false) {
                        $response['has_inventory_expenses'] = true;

                        $price = $line_item['rate'];
                        $response['inventory_expenses'][$expenses_count] = [
                            "purchase_date" => $purchase_date,
                            "vendor_name" => $vendor_name,
                            "name"  => $line_item['lineDescription'],
                            "invoice" => !empty($data['invoiceData']['invoiceNumber']) ? $data['invoiceData']['invoiceNumber'] : '',
                            "price" => $price,
                            "quantity" => $line_item['qty'],
                        ];
                        ++$expenses_count;
                    }
                }
            }
        }

        return $response;
    }
}
