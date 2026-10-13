<?php

namespace Tenant\app\Database\Seeds;

use Tenant\app\Models\CraigslistViewModel;
use Tenant\app\Models\FacebookMarketPlaceViewModel;

class CraigslistViewSeeds extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $arrays = [
            [
                'header' => 'Craigslist',
                'view' => '[{"header":"Post Id","column":"post_id","component":"text","visibility":"1"},{"header":"Stock No","column":"stock_no","component":"text","visibility":"1"},{"header":"Vin","column":"vin","component":"text","visibility":"1"},{"header":"Year","column":"year","component":"text","visibility":"1"},{"header":"Make","column":"make","component":"text","visibility":"1"},{"header":"Model","column":"model","component":"text","visibility":"1"},{"header":"Trim","column":"trim","component":"text","visibility":"0"},{"header":"Odometer","column":"odometer","component":"text","visibility":"1"},{"header":"Selling Price","column":"selling_price","component":"text","visibility":"1"},{"header":"Transmission","column":"transmission","component":"text","visibility":"0"},{"header":"Exterior Color","column":"exterior_color","component":"text","visibility":"0"},{"header":"Cylinders","column":"engine","component":"text","visibility":"0"},{"header":"Drive Train","column":"drivetrain","component":"text","visibility":"0"},{"header":"Body Style","column":"body_type","component":"text","visibility":"0"},{"header":"Fuel Type","column":"fuel_type","component":"text","visibility":"0"},{"header":"Brand Type","column":"disclosure_brand_type","component":"text","visibility":"0"},{"header":"Post Status","column":"craigslist_status","component":"badge","visibility":"1"},{"header":"Post Location","column":"craigslist_location","component":"text","visibility":"1"},{"header":"Published Date","column":"craigslist_created_at","component":"date","visibility":"1"},{"header":"Modified Date","column":"craigslist_updated_at","component":"date","visibility":"1"}]',
                'query' => '[{"header":"Status","column":"status","join":"AND","comparator":"is","type":"Multi Select","value_1":["Instock"],"value_2":""},{"header":"Visibility","column":"visibility","join":"AND","comparator":"is","type":"Single Select","value_1":"1","value_2":""}]',
                'default' => '1',
            ],
        ];

        $vendorViewModel = new CraigslistViewModel();
        $vendorViewModel->protect(false);
        foreach ($arrays as $array) {
            $vendorViewModel->ignore(true)->insert($array);
        }
        $vendorViewModel->protect(true);
    }
}
