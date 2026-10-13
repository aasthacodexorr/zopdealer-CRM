<?php

namespace Tenant\app\Database\Seeds;

use Tenant\app\Models\InventoryViewModel;

/**
 * InventoryViewSeeds Seeds The Inventory Views while onboarding the client.
 */
class InventoryViewSeeds extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $arrays = [
            [
                'header' => 'All Inventory',
                'view' => '[{"header":"PRICE","column":"price","component":"currency","visibility":"1"},{"header":"SPECIAL PRICE","column":"special_price","component":"currency","visibility":"1"},{"header":"EXT COLOR","column":"exterior_color","component":"text","visibility":"1"},{"header":"ODOMETER","column":"odometer","component":"distance","visibility":"1"},{"header":"FEATURED","column":"feature_listing","component":"text","visibility":"1"},{"header":"TRANSMISSION","column":"transmission","component":"text","visibility":"1"},{"header":"BODY TYPE","column":"body_type","component":"text","visibility":"1"},{"header":"STATUS","column":"status","component":"badge","visibility":"1"},{"header":"TYPE","column":"vehicle_type","component":"text","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"}]',
                'query' => '[]',
                'default' => '1',
            ],
            [
                'header' => 'In Stock Inventory',
                'view' => '[{"header":"PRICE","column":"price","component":"currency","visibility":"1"},{"header":"SPECIAL PRICE","column":"special_price","component":"currency","visibility":"1"},{"header":"EXT COLOR","column":"exterior_color","component":"text","visibility":"1"},{"header":"ODOMETER","column":"odometer","component":"distance","visibility":"1"},{"header":"FEATURED","column":"feature_listing","component":"text","visibility":"1"},{"header":"TRANSMISSION","column":"transmission","component":"text","visibility":"1"},{"header":"BODY TYPE","column":"body_type","component":"text","visibility":"1"},{"header":"STATUS","column":"status","component":"badge","visibility":"1"},{"header":"TYPE","column":"vehicle_type","component":"text","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"}]',
                'query' => '[{"header":"Status","column":"status","join":"AND","comparator":"is","type":"Multi Select","value_1":["Instock"],"value_2":""}]',
                'default' => '1',
            ],
            [
                'header' => 'Sold Inventory',
                'view' => '[{"header":"PRICE","column":"price","component":"currency","visibility":"1"},{"header":"SPECIAL PRICE","column":"special_price","component":"currency","visibility":"1"},{"header":"EXT COLOR","column":"exterior_color","component":"text","visibility":"1"},{"header":"ODOMETER","column":"odometer","component":"distance","visibility":"1"},{"header":"FEATURED","column":"feature_listing","component":"text","visibility":"1"},{"header":"TRANSMISSION","column":"transmission","component":"text","visibility":"1"},{"header":"BODY TYPE","column":"body_type","component":"text","visibility":"1"},{"header":"STATUS","column":"status","component":"badge","visibility":"1"},{"header":"TYPE","column":"vehicle_type","component":"text","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"}]',
                'query' => '[{"header":"Status","column":"status","join":"AND","comparator":"is","type":"Multi Select","value_1":["Sold"],"value_2":""}]',
                'default' => '1',
            ],
            [
                'header' => 'Inventory Age: 90+',
                'view' => '[{"header":"PRICE","column":"price","component":"currency","visibility":"1"},{"header":"SPECIAL PRICE","column":"special_price","component":"currency","visibility":"1"},{"header":"EXT COLOR","column":"exterior_color","component":"text","visibility":"1"},{"header":"ODOMETER","column":"odometer","component":"distance","visibility":"1"},{"header":"FEATURED","column":"feature_listing","component":"text","visibility":"1"},{"header":"TRANSMISSION","column":"transmission","component":"text","visibility":"1"},{"header":"BODY TYPE","column":"body_type","component":"text","visibility":"1"},{"header":"STATUS","column":"status","component":"badge","visibility":"1"},{"header":"TYPE","column":"vehicle_type","component":"text","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"}]',
                'query' => '[{"header":"Days in Stock","column":"days_in_stock","join":"AND","comparator":"greater than","type":"Numeric Comparision","value_1":"90","value_2":""},{"header":"Status","column":"status","join":"AND","comparator":"is","type":"Multi Select","value_1":["Instock"],"value_2":""}]',
                'default' => '1',
            ],
            [
                'header' => 'Website Inventory',
                'view' => '[{"header":"PRICE","column":"price","component":"currency","visibility":"1"},{"header":"SPECIAL PRICE","column":"special_price","component":"currency","visibility":"1"},{"header":"EXT COLOR","column":"exterior_color","component":"text","visibility":"1"},{"header":"ODOMETER","column":"odometer","component":"distance","visibility":"1"},{"header":"FEATURED","column":"feature_listing","component":"text","visibility":"1"},{"header":"TRANSMISSION","column":"transmission","component":"text","visibility":"1"},{"header":"BODY TYPE","column":"body_type","component":"text","visibility":"1"},{"header":"STATUS","column":"status","component":"badge","visibility":"1"},{"header":"TYPE","column":"vehicle_type","component":"text","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"}]',
                'query' => '[{"header":"Status","column":"status","join":"AND","comparator":"is","type":"Multi Select","value_1":["Instock","Sold"],"value_2":""},{"header":"Visibility","column":"visibility","join":"AND","comparator":"is","type":"Single Select","value_1":"1","value_2":""}]',
                'default' => '1',
            ],
            [
                'header' => 'Instock Age: 30 - 90',
                'view' => '[{"header":"PRICE","column":"price","component":"currency","visibility":"1"},{"header":"SPECIAL PRICE","column":"special_price","component":"currency","visibility":"1"},{"header":"EXT COLOR","column":"exterior_color","component":"text","visibility":"1"},{"header":"ODOMETER","column":"odometer","component":"distance","visibility":"1"},{"header":"FEATURED","column":"feature_listing","component":"text","visibility":"1"},{"header":"TRANSMISSION","column":"transmission","component":"text","visibility":"1"},{"header":"BODY TYPE","column":"body_type","component":"text","visibility":"1"},{"header":"STATUS","column":"status","component":"badge","visibility":"1"},{"header":"TYPE","column":"vehicle_type","component":"text","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"}]',
                'query' => '[{"header":"Days in Stock","column":"days_in_stock","join":"AND","comparator":"between","type":"Numeric Comparision","value_1":"30","value_2":"90"},{"header":"Status","column":"status","join":"AND","comparator":"is","type":"Multi Select","value_1":["Instock"],"value_2":""}]',
                'default' => '1',
            ],
            [
                'header' => 'New Instock',
                'view' => '[{"header":"PRICE","column":"price","component":"currency","visibility":"1"},{"header":"SPECIAL PRICE","column":"special_price","component":"currency","visibility":"1"},{"header":"EXT COLOR","column":"exterior_color","component":"text","visibility":"1"},{"header":"ODOMETER","column":"odometer","component":"distance","visibility":"1"},{"header":"FEATURED","column":"feature_listing","component":"text","visibility":"1"},{"header":"TRANSMISSION","column":"transmission","component":"text","visibility":"1"},{"header":"BODY TYPE","column":"body_type","component":"text","visibility":"1"},{"header":"STATUS","column":"status","component":"badge","visibility":"1"},{"header":"TYPE","column":"vehicle_type","component":"text","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"}]',
                'query' => '[{"header":"Days in Stock","column":"days_in_stock","join":"AND","comparator":"less than","type":"Numeric Comparision","value_1":"30","value_2":""},{"header":"Status","column":"status","join":"AND","comparator":"is","type":"Multi Select","value_1":["Instock"],"value_2":""}]',
                'default' => '1',
            ],
        ];

        
        foreach ($arrays as $array) {
            $inventoryViewModel = new InventoryViewModel();
            $inventoryViewModel->protect(false);
            $inventoryViewModel->ignore(true)->insert($array);
            $inventoryViewModel->protect(true);
        }
    }
}
