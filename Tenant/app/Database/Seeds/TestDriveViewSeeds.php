<?php

namespace Tenant\app\Database\Seeds;

use Tenant\app\Models\TestDriveViewModel;

class TestDriveViewSeeds extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $arrays = [
            [
                'header' => 'Test Drives',
                'view' => '[{"column":"first_name","header":"FIRST NAME","component":"text","visibility":"1"},{"column":"last_name","header":"LAST NAME","component":"text","visibility":"1"},{"column":"phone","header":"PHONE","component":"phone","visibility":"1"},{"column":"email","header":"EMAIL","component":"email","visibility":"1"},{"column":"vehicle","header":"VEHICLE","component":"text","visibility":"1"},{"column":"stock_no","header":"STOCK#","component":"text","visibility":"1"},{"column":"vin","header":"VIN","component":"text","visibility":"1"},{"column":"number_plate","header":"NUMBER PLATE","component":"text","visibility":"1"},{"column":"odo_start","header":"ODO START","component":"text","visibility":"1"},{"column":"odo_end","header":"ODO END","component":"text","visibility":"1"},{"column":"sales_rep_id","header":"SALES REP","component":"user","visibility":"1"},{"column":"issue","header":"ISSUES?","component":"text","visibility":"1"},{"column":"status","header":"STATUS","component":"badge","visibility":"1"},{"column":"created_at","header":"CREATION DATE","component":"date","visibility":"1"},{"column":"updated_at","header":"MODIFIED DATE","component":"date","visibility":"1"}]',
                'query' => '[]',
                'default' => '1',
            ],
        ];

        $testDriveViewModel = new TestDriveViewModel();
        $testDriveViewModel->protect(false);
        foreach ($arrays as $array) {
            $testDriveViewModel->ignore(true)->insert($array);
        }
        $testDriveViewModel->protect(true);
    }
}