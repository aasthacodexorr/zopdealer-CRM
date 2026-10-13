<?php

namespace Tenant\app\Libraries\PDFParser;

use Carbon\Carbon;

class OpenlanePDFParser extends InventoryPDFParserAbstract implements PDFParserInterface
{
    protected string $path = '/services/pdf/extract/openlane';

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
                $response['interior_color'] = $inventory['interior_color'];
                $response['exterior_color'] = $inventory['exterior_color'];
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

                if ($this->skipTheLineItems($line_item['lineDescription'])) {
                    continue;
                }

                if ($this->hasPurchasePriceInformation($line_item['lineDescription'])) {
                    $response['inventory_purchase']['price'] = $line_item['rate'];
                } else {

                    $response['has_inventory_expenses'] = true;
                    $response['inventory_expenses'][$expenses_count] = [
                        "purchase_date" => $purchase_date,
                        "vendor_name" => $vendor_name,
                        "name"  => $line_item['lineDescription'],
                        "invoice" => !empty($data['invoiceData']['invoiceNumber']) ? $data['invoiceData']['invoiceNumber'] : '',
                        "price" => $line_item['rate'],
                        "quantity" => $line_item['qty'],
                    ];
                    ++$expenses_count;
                }
            }
        }

        return $response;
    }

    private function skipTheLineItems($description)
    {
        $skip_keyword_notations = ["%", "HST", "PST", "GST", "QST", "Subtotal"];
        $skip_notation_pattern = '/(' . implode('|', array_map('preg_quote', $skip_keyword_notations)) . ')/i';
        return preg_match($skip_notation_pattern, $description);
    }

    private function hasPurchasePriceInformation($description)
    {
        $has_purchase_pricing_information = stripos($description, 'Buyer Purchase');
        return $has_purchase_pricing_information !== false;
    }
}
