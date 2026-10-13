<?php

namespace Tenant\app\Database\Seeds;

use Tenant\app\Models\WorkOrderViewModel;

class WorkOrderViewSeeds extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $arrays = [
            [
                'header' => 'Work Orders',
                'view' => '[{"header":"ID","column":"work_order_id","component":"text","visibility":"1"},{"header":"YEAR","column":"year","component":"text","visibility":"1"},{"header":"MAKE","column":"make","component":"text","visibility":"1"},{"header":"MODEL","column":"model","component":"text","visibility":"1"},{"header":"VIN","column":"vin","component":"text","visibility":"1"},{"header":"STOCK NO","column":"stock_no","component":"text","visibility":"1"},{"header":"TYPE","column":"type","component":"text","visibility":"1"},{"header":"STATUS","column":"status","component":"badge","visibility":"1"},{"header":"AUTO SYNC","column":"auto_sync_expense_flag","component":"text","visibility":"0"},{"header":"FIRST NAME","column":"first_name","component":"text","visibility":"1"},{"header":"LAST NAME","column":"last_name","component":"text","visibility":"1"},{"header":"PHONE","column":"phone","component":"phone","visibility":"0"},{"header":"MOBILE","column":"mobile","component":"phone","visibility":"0"},{"header":"EMAIL","column":"email","component":"email","visibility":"0"},{"header":"CHECK IN","column":"check_in_date_time","component":"date","visibility":"0"},{"header":"ODOMETER IN","column":"check_in_odometer","component":"distance","visibility":"0"},{"header":"CHECK OUT","column":"check_out_date_time","component":"date","visibility":"0"},{"header":"ODOMETER OUT","column":"check_out_odometer","component":"distance","visibility":"0"},{"header":"MECHANIC","column":"assigned_mechanic","component":"text","visibility":"1"},{"header":"ADVISOR","column":"assigned_advisor","component":"user","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"}]',
                'query' => '[]',
                'default' => '1',
            ],
            [
                'header' => 'Open & In-Progress WO',
                'view' => '[{"header":"ID","column":"work_order_id","component":"text","visibility":"1"},{"header":"YEAR","column":"year","component":"text","visibility":"1"},{"header":"MAKE","column":"make","component":"text","visibility":"1"},{"header":"MODEL","column":"model","component":"text","visibility":"1"},{"header":"VIN","column":"vin","component":"text","visibility":"1"},{"header":"STOCK NO","column":"stock_no","component":"text","visibility":"1"},{"header":"TYPE","column":"type","component":"text","visibility":"1"},{"header":"STATUS","column":"status","component":"badge","visibility":"1"},{"header":"AUTO SYNC","column":"auto_sync_expense_flag","component":"text","visibility":"0"},{"header":"FIRST NAME","column":"first_name","component":"text","visibility":"1"},{"header":"LAST NAME","column":"last_name","component":"text","visibility":"1"},{"header":"PHONE","column":"phone","component":"phone","visibility":"0"},{"header":"MOBILE","column":"mobile","component":"phone","visibility":"0"},{"header":"EMAIL","column":"email","component":"email","visibility":"0"},{"header":"CHECK IN","column":"check_in_date_time","component":"date","visibility":"0"},{"header":"ODOMETER IN","column":"check_in_odometer","component":"distance","visibility":"0"},{"header":"CHECK OUT","column":"check_out_date_time","component":"date","visibility":"0"},{"header":"ODOMETER OUT","column":"check_out_odometer","component":"distance","visibility":"0"},{"header":"MECHANIC","column":"assigned_mechanic","component":"text","visibility":"1"},{"header":"ADVISOR","column":"assigned_advisor","component":"user","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"}]',
                'query' => '[{"header":"Status","column":"status","join":"AND","comparator":"is","type":"Multi Select","value_1":["Open","In Progress"],"value_2":""}]',
                'default' => '1',
            ],
            [
                'header' => 'Completed WO',
                'view' => '[{"header":"ID","column":"work_order_id","component":"text","visibility":"1"},{"header":"YEAR","column":"year","component":"text","visibility":"1"},{"header":"MAKE","column":"make","component":"text","visibility":"1"},{"header":"MODEL","column":"model","component":"text","visibility":"1"},{"header":"VIN","column":"vin","component":"text","visibility":"1"},{"header":"STOCK NO","column":"stock_no","component":"text","visibility":"1"},{"header":"TYPE","column":"type","component":"text","visibility":"1"},{"header":"STATUS","column":"status","component":"badge","visibility":"1"},{"header":"AUTO SYNC","column":"auto_sync_expense_flag","component":"text","visibility":"0"},{"header":"FIRST NAME","column":"first_name","component":"text","visibility":"1"},{"header":"LAST NAME","column":"last_name","component":"text","visibility":"1"},{"header":"PHONE","column":"phone","component":"phone","visibility":"0"},{"header":"MOBILE","column":"mobile","component":"phone","visibility":"0"},{"header":"EMAIL","column":"email","component":"email","visibility":"0"},{"header":"CHECK IN","column":"check_in_date_time","component":"date","visibility":"0"},{"header":"ODOMETER IN","column":"check_in_odometer","component":"distance","visibility":"0"},{"header":"CHECK OUT","column":"check_out_date_time","component":"date","visibility":"0"},{"header":"ODOMETER OUT","column":"check_out_odometer","component":"distance","visibility":"0"},{"header":"MECHANIC","column":"assigned_mechanic","component":"text","visibility":"1"},{"header":"ADVISOR","column":"assigned_advisor","component":"user","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"}]',
                'query' => '[{"header":"Status","column":"status","join":"AND","comparator":"is","type":"Multi Select","value_1":["Completed"],"value_2":""}]',
                'default' => '1',
            ],
            [
                'header' => 'Lost WO',
                'view' => '[{"header":"ID","column":"work_order_id","component":"text","visibility":"1"},{"header":"YEAR","column":"year","component":"text","visibility":"1"},{"header":"MAKE","column":"make","component":"text","visibility":"1"},{"header":"MODEL","column":"model","component":"text","visibility":"1"},{"header":"VIN","column":"vin","component":"text","visibility":"1"},{"header":"STOCK NO","column":"stock_no","component":"text","visibility":"1"},{"header":"TYPE","column":"type","component":"text","visibility":"1"},{"header":"STATUS","column":"status","component":"badge","visibility":"1"},{"header":"AUTO SYNC","column":"auto_sync_expense_flag","component":"text","visibility":"0"},{"header":"FIRST NAME","column":"first_name","component":"text","visibility":"1"},{"header":"LAST NAME","column":"last_name","component":"text","visibility":"1"},{"header":"PHONE","column":"phone","component":"phone","visibility":"0"},{"header":"MOBILE","column":"mobile","component":"phone","visibility":"0"},{"header":"EMAIL","column":"email","component":"email","visibility":"0"},{"header":"CHECK IN","column":"check_in_date_time","component":"date","visibility":"0"},{"header":"ODOMETER IN","column":"check_in_odometer","component":"distance","visibility":"0"},{"header":"CHECK OUT","column":"check_out_date_time","component":"date","visibility":"0"},{"header":"ODOMETER OUT","column":"check_out_odometer","component":"distance","visibility":"0"},{"header":"MECHANIC","column":"assigned_mechanic","component":"text","visibility":"1"},{"header":"ADVISOR","column":"assigned_advisor","component":"user","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"}]',
                'query' => '[{"header":"Status","column":"status","join":"AND","comparator":"is","type":"Multi Select","value_1":["Lost"],"value_2":""}]',
                'default' => '1',
            ]
        ];

        $workOrderViewModel = new WorkOrderViewModel();
        $workOrderViewModel->protect(false);
        foreach ($arrays as $array) {
            $workOrderViewModel->ignore(true)->insert($array);
        }
        $workOrderViewModel->protect(true);
    }
}
