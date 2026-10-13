<?php

namespace Tenant\app\Database\Seeds;

use Tenant\app\Models\VoucherViewModel;

class VoucherViewSeeds extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $arrays = [
            [
                'header' => 'Vouchers',
                'view' => '[{"header":"ID","column":"id","component":"text","visibility":"1"},{"header":"TITLE","column":"title","component":"text","visibility":"1"},{"header":"FIRST NAME","column":"first_name","component":"text","visibility":"1"},{"header":"LAST NAME","column":"last_name","component":"text","visibility":"1"},{"header":"PHONE","column":"phone","component":"phone","visibility":"0"},{"header":"MOBILE","column":"mobile","component":"phone","visibility":"0"},{"header":"EMAIL","column":"email","component":"email","visibility":"0"},{"header":"YEAR","column":"year","component":"text","visibility":"0"},{"header":"MAKE","column":"make","component":"text","visibility":"0"},{"header":"MODEL","column":"model","component":"text","visibility":"0"},{"header":"VIN","column":"vin","component":"text","visibility":"1"},{"header":"STOCK NO","column":"stock_no","component":"text","visibility":"1"},{"header":"ODOMETER","column":"odometer","component":"distance","visibility":"0"},{"header":"EXPIRY DATE","column":"expiry_date","component":"date","visibility":"1"},{"header":"STATUS","column":"status","component":"badge","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"}]',
                'query' => '[]',
                'default' => '1',
            ],
        ];

        $voucherViewModel = new VoucherViewModel();
        $voucherViewModel->protect(false);
        foreach ($arrays as $array) {
            $voucherViewModel->ignore(true)->insert($array);
        }
        $voucherViewModel->protect(true);
    }
}