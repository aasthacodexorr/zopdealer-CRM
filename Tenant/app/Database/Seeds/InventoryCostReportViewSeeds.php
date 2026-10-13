<?php

namespace Tenant\app\Database\Seeds;

use Tenant\app\Models\InventoryCostReportViewModel;
use Tenant\app\Models\PaymentReportViewModel;

/**
 * InventoryCostReportViewSeeds Seeds The Inventory Cost Report Views while onboarding the client.
 */
class InventoryCostReportViewSeeds extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $arrays = [
            [
                'header' => 'All Inventory Expenses',
                'view' => '[{"header":"EXPENSE DATE","column":"purchase_date","component":"date","visibility":"1"},{"header":"YEAR","column":"year","component":"text","visibility":"1"},{"header":"MAKE","column":"make","component":"text","visibility":"1"},{"header":"MODEL","column":"model","component":"text","visibility":"1"},{"header":"TRIM","column":"trim","component":"text","visibility":"1"},{"header":"STOCK NO","column":"stock_no","component":"text","visibility":"1"},{"header":"VIN","column":"vin","component":"text","visibility":"1"},{"header":"NAME","column":"name","component":"text","visibility":"1"},{"header":"INVOICE","column":"invoice","component":"text","visibility":"1"},{"header":"PAYMENT REF","column":"payment_reference","component":"text","visibility":"1"},{"header":"VENDOR NAME","column":"vendor_name","component":"text","visibility":"1"},{"header":"PRICE","column":"price","component":"currency","visibility":"1"},{"header":"QUANTITY","column":"quantity","component":"text","visibility":"1"},{"header":"DISCOUNT","column":"discount","component":"currency","visibility":"1"},{"header":"PRIMARY TAX","column":"tax_amount","component":"currency","visibility":"1"},{"header":"SECONDARY TAX","column":"tax_amount_secondary","component":"currency","visibility":"1"},{"header":"TOTAL TAX","column":"tax_amount_total","component":"currency","visibility":"1"},{"header":"LINE TOTAL","column":"sub_total","component":"currency","visibility":"1"}]',
                'query' => '[]',
                'default' => '1',
            ],
        ];


        foreach ($arrays as $array) {
            $inventoryCostReportViewModel = new InventoryCostReportViewModel();
            $inventoryCostReportViewModel->protect(false);
            $inventoryCostReportViewModel->ignore(true)->insert($array);
            $inventoryCostReportViewModel->protect(true);
        }
    }
}
