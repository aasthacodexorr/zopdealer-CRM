<?php

namespace Tenant\app\Database\Seeds;

use Tenant\app\Models\DealViewModel;

class DealViewSeeds extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $arrays = [
            [
                'header' => 'Deals',
                'view' => '[{"header":"FIRST NAME","column":"first_name","component":"text","visibility":"1"},{"header":"LAST NAME","column":"last_name","component":"text","visibility":"1"},{"header":"COMPANY NAME","column":"company_name","component":"text","visibility":"0"},{"header":"PHONE","column":"phone","component":"phone","visibility":"1"},{"header":"MOBILE","column":"mobile","component":"phone","visibility":"1"},{"header":"EMAIL","column":"email","component":"email","visibility":"1"},{"header":"YEAR","column":"year","component":"text","visibility":"0"},{"header":"MAKE","column":"make","component":"text","visibility":"0"},{"header":"MODEL","column":"model","component":"text","visibility":"0"},{"header":"VIN","column":"vin","component":"text","visibility":"1"},{"header":"STOCK NO","column":"stock_no","component":"text","visibility":"1"},{"header":"ODOMETER","column":"odometer","component":"distance","visibility":"1"},{"header":"EXTERIOR COLOR","column":"exterior_color","component":"text","visibility":"0"},{"header":"INTERIOR COLOR","column":"interior_color","component":"text","visibility":"0"},{"header":"TYPE","column":"type","component":"text","visibility":"1"},{"header":"DATE","column":"date","component":"date","visibility":"1"},{"header":"RL or WHL","column":"retail_or_wholesale_flag","component":"rtl_wh_flag","visibility":"1"},{"header":"STATUS","column":"status","component":"badge","visibility":"1"},{"header":"Approved By","column":"approved_by","component":"user","visibility":"1"},{"header":"Salesperson","column":"salesperson","component":"user","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"}]',
                'query' => '[]',
                'default' => '1',
            ],
        ];

        foreach ($arrays as $array) {
            $dealViewModel = new DealViewModel();
            $dealViewModel->protect(false);
            $dealViewModel->ignore(true)->insert($array);
            $dealViewModel->protect(true);
        }
    }
}
