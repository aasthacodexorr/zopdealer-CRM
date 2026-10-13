<?php

namespace Tenant\app\Database\Seeds;

use Tenant\app\Models\ProductViewModel;
use Tenant\app\Models\VendorViewModel;

class ProductViewSeeds extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $arrays = [
            [
                'header' => 'Products',
                'view' => '[{"header":"NAME","column":"name","component":"text","visibility":"1"},{"header":"ITEM CODE","column":"item_code","component":"text","visibility":"1"},{"header":"CATEGORY","column":"category","component":"badge","visibility":"1"},{"header":"TYPE","column":"type","component":"badge","visibility":"1"},{"header":"VENDOR","column":"vendor_name","component":"text","visibility":"1"},{"header":"CURRENT QTY","column":"available_quantity","component":"text","visibility":"1"},{"header":"AUTO UPDATE","column":"auto_update_quantity","component":"boolean","visibility":"1"},{"header":"COST","column":"cost","component":"currency","visibility":"1"},{"header":"PRICE","column":"price","component":"currency","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"}]',
                'query' => '[]',
                'default' => '1',
            ],
        ];

        $productViewModel = new ProductViewModel();
        $productViewModel->protect(false);
        foreach ($arrays as $array) {
            $productViewModel->ignore(true)->insert($array);
        }
        $productViewModel->protect(true);
    }
}