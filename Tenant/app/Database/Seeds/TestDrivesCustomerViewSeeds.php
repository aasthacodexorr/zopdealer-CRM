<?php

namespace Tenant\app\Database\Seeds;

use Tenant\app\Models\TestDriveCustomerViewModel;

class TestDrivesCustomerViewSeeds extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $arrays = [
            [
                'header' => 'Test Drives Customers',
                'view' => '[{"header":"FIRST NAME","column":"first_name","component":"text","visibility":"1"},{"header":"LAST NAME","column":"last_name","component":"text","visibility":"1"},{"header":"PHONE","column":"phone","component":"phone","visibility":"1"},{"header":"EMAIL","column":"email","component":"email","visibility":"1"},{"header":"DRIVER LICENSE","column":"driver_license","component":"text","visibility":"1"},{"header":"LICENSE EXPIRY","column":"license_expiry","component":"date","visibility":"1"},{"header":"STREET ADDRESS","column":"street_address","component":"text","visibility":"0"},{"header":"APT","column":"apt","component":"text","visibility":"0"},{"header":"CITY","column":"city","component":"text","visibility":"0"},{"header":"PROVINCE","column":"province","component":"text","visibility":"1"},{"header":"POSTAL CODE","column":"postal_code","component":"text","visibility":"0"},{"header":"COUNTRY","column":"country","component":"text","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"}]',
                'query' => '[]',
                'sort' => '{"created_at":"DESC"}',
                'default' => '1',
            ],
        ];

        $testDrivesCustomerViewModel = new TestDriveCustomerViewModel();
        $testDrivesCustomerViewModel->protect(false);
        foreach ($arrays as $array) {
            $testDrivesCustomerViewModel->ignore(true)->insert($array);
        }
        $testDrivesCustomerViewModel->protect(true);
    }
}
