<?php

namespace Tenant\app\Database\Seeds;

use Tenant\app\Models\FacebookMarketPlaceViewModel;

class FacebookMarketPlaceViewSeeds extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $arrays = [
            [
                'header' => 'Facebook Marketplace',
                'view' => '[{"header":"Post Id","column":"post_id","component":"text","visibility":"1"},{"header":"Stock No","column":"stock_no","component":"text","visibility":"1"},{"header":"Vin","column":"vin","component":"text","visibility":"1"},{"header":"Year","column":"year","component":"text","visibility":"1"},{"header":"Make","column":"make","component":"text","visibility":"1"},{"header":"Model","column":"model","component":"text","visibility":"1"},{"header":"Trim","column":"trim","component":"text","visibility":"0"},{"header":"Odometer","column":"odometer","component":"text","visibility":"1"},{"header":"Selling Price","column":"selling_price","component":"text","visibility":"1"},{"header":"Automatic Transmission","column":"transmission","component":"text","visibility":"0"},{"header":"Exterior Color","column":"exterior_color","component":"text","visibility":"0"},{"header":"Interior Color","column":"interior_color","component":"text","visibility":"0"},{"header":"Vehicle Type","column":"vehicle_type","component":"text","visibility":"0"},{"header":"Body Style","column":"body_type","component":"text","visibility":"0"},{"header":"Fuel Type","column":"fuel_type","component":"text","visibility":"0"},{"header":"Location","column":"location","component":"text","visibility":"0"},{"header":"Post Status","column":"facebook_status","component":"badge","visibility":"1"},{"header":"Published Date","column":"facebook_created_at","component":"date","visibility":"1"},{"header":"Modified Date","column":"facebook_updated_at","component":"date","visibility":"1"}]',
                'query' => '[{"header":"Status","column":"status","join":"AND","comparator":"is","type":"Multi Select","value_1":["Instock"],"value_2":""},{"header":"Visibility","column":"visibility","join":"AND","comparator":"is","type":"Single Select","value_1":"1","value_2":""}]',
                'default' => '1',
            ],
        ];

        $vendorViewModel = new FacebookMarketPlaceViewModel();
        $vendorViewModel->protect(false);
        foreach ($arrays as $array) {
            $vendorViewModel->ignore(true)->insert($array);
        }
        $vendorViewModel->protect(true);
    }
}
