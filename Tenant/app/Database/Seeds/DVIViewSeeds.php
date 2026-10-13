<?php

namespace Tenant\app\Database\Seeds;

use Tenant\app\Models\DigitalVehicleInspectionViewModel;

class DVIViewSeeds extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $arrays = [
            [
                'header' => 'Incomplete DVI',
                'view' => '[{"header":"YEAR","column":"year","component":"text","visibility":"1"},{"header":"MAKE","column":"make","component":"text","visibility":"1"},{"header":"MODEL","column":"model","component":"text","visibility":"1"},{"header":"VIN","column":"vin","component":"text","visibility":"1"},{"header":"STOCK NO","column":"stock_no","component":"text","visibility":"1"},{"header":"TITLE","column":"title","component":"text","visibility":"1"},{"header":"ODOMETER","column":"odometer","component":"distance","visibility":"1"},{"header":"STATUS","column":"status","component":"badge","visibility":"1"},{"header":"ASSIGNEE","column":"assignee","component":"user","visibility":"1"},{"header":"ISSUES","column":"issues_count","component":"number","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"}]',
                'query' => '[{"header":"Status","column":"status","join":"AND","comparator":"is","type":"Multi Select","value_1":["INCOMPLETE"],"value_2":""}]',
                'default' => '1',
            ],
            [
                'header' => 'Completed DVI',
                'view' => '[{"header":"YEAR","column":"year","component":"text","visibility":"1"},{"header":"MAKE","column":"make","component":"text","visibility":"1"},{"header":"MODEL","column":"model","component":"text","visibility":"1"},{"header":"VIN","column":"vin","component":"text","visibility":"1"},{"header":"STOCK NO","column":"stock_no","component":"text","visibility":"1"},{"header":"TITLE","column":"title","component":"text","visibility":"1"},{"header":"ODOMETER","column":"odometer","component":"distance","visibility":"1"},{"header":"STATUS","column":"status","component":"badge","visibility":"1"},{"header":"ASSIGNEE","column":"assignee","component":"user","visibility":"1"},{"header":"ISSUES","column":"issues_count","component":"number","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"}]',
                'query' => '[{"header":"Status","column":"status","join":"AND","comparator":"is","type":"Multi Select","value_1":["COMPLETE"],"value_2":""}]',
                'default' => '1',
            ],
            [
                'header' => 'All DVI',
                'view' => '[{"header":"YEAR","column":"year","component":"text","visibility":"1"},{"header":"MAKE","column":"make","component":"text","visibility":"1"},{"header":"MODEL","column":"model","component":"text","visibility":"1"},{"header":"VIN","column":"vin","component":"text","visibility":"1"},{"header":"STOCK NO","column":"stock_no","component":"text","visibility":"1"},{"header":"TITLE","column":"title","component":"text","visibility":"1"},{"header":"ODOMETER","column":"odometer","component":"distance","visibility":"1"},{"header":"STATUS","column":"status","component":"badge","visibility":"1"},{"header":"ASSIGNEE","column":"assignee","component":"user","visibility":"1"},{"header":"ISSUES","column":"issues_count","component":"number","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"}]',
                'query' => '[]',
                'default' => '1',
            ]
        ];

        foreach ($arrays as $array) {
            $dVIViewModel = new DigitalVehicleInspectionViewModel();
            $dVIViewModel->protect(false);
            $dVIViewModel->ignore(true)->insert($array);
            $dVIViewModel->protect(true);
        }
    }
}
