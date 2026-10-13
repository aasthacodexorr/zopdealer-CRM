<?php

namespace Tenant\app\Database\Seeds;

use Tenant\app\Models\LeadResponseViewModel;

class LeadResponseViewSeeds extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $arrays = [
            [
                'header' => 'All Leads',
                'view' => '[{"header":"FIRST NAME","column":"first_name","component":"text","visibility":"1"},{"header":"LAST NAME","column":"last_name","component":"text","visibility":"1"},{"header":"PHONE","column":"phone","component":"phone","visibility":"1"},{"header":"EMAIL","column":"email","component":"email","visibility":"1"},{"header":"SOURCE","column":"source","component":"text","visibility":"1"},{"header":"STATUS","column":"status","component":"text","visibility":"1"},{"header":"ASSIGNEE","column":"assignee","component":"user","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"},{"header":"YEAR","column":"year","component":"text","visibility":"1"},{"header":"MAKE","column":"make","component":"text","visibility":"1"},{"header":"MODEL","column":"model","component":"text","visibility":"1"},{"header":"VIN","column":"vin","component":"text","visibility":"1"},{"header":"STOCK NO","column":"stock_no","component":"text","visibility":"1"}]',
                'query' => '[]',
                'default' => '1',
            ],
            [
                'header' => 'Junk Leads',
                'view' => '[{"header":"FIRST NAME","column":"first_name","component":"text","visibility":"1"},{"header":"LAST NAME","column":"last_name","component":"text","visibility":"1"},{"header":"PHONE","column":"phone","component":"phone","visibility":"1"},{"header":"EMAIL","column":"email","component":"email","visibility":"1"},{"header":"SOURCE","column":"source","component":"text","visibility":"1"},{"header":"STATUS","column":"status","component":"text","visibility":"1"},{"header":"ASSIGNEE","column":"assignee","component":"user","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"},{"header":"YEAR","column":"year","component":"text","visibility":"1"},{"header":"MAKE","column":"make","component":"text","visibility":"1"},{"header":"MODEL","column":"model","component":"text","visibility":"1"},{"header":"VIN","column":"vin","component":"text","visibility":"1"},{"header":"STOCK NO","column":"stock_no","component":"text","visibility":"1"}]',
                'query' => '[{"header":"Status","column":"status","join":"AND","comparator":"is","type":"Multi Select","value_1":["Junk Lead"],"value_2":""}]',
                'default' => '1',
            ],
            [
                'header' => 'Lost Leads',
                'view' => '[{"header":"FIRST NAME","column":"first_name","component":"text","visibility":"1"},{"header":"LAST NAME","column":"last_name","component":"text","visibility":"1"},{"header":"PHONE","column":"phone","component":"phone","visibility":"1"},{"header":"EMAIL","column":"email","component":"email","visibility":"1"},{"header":"SOURCE","column":"source","component":"text","visibility":"1"},{"header":"STATUS","column":"status","component":"text","visibility":"1"},{"header":"ASSIGNEE","column":"assignee","component":"user","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"},{"header":"YEAR","column":"year","component":"text","visibility":"1"},{"header":"MAKE","column":"make","component":"text","visibility":"1"},{"header":"MODEL","column":"model","component":"text","visibility":"1"},{"header":"VIN","column":"vin","component":"text","visibility":"1"},{"header":"STOCK NO","column":"stock_no","component":"text","visibility":"1"}]',
                'query' => '[{"header":"Status","column":"status","join":"AND","comparator":"is","type":"Multi Select","value_1":["Lost Lead"],"value_2":""}]',
                'default' => '1',
            ],
            [
                'header' => 'Last 30 Days',
                'view' => '[{"header":"FIRST NAME","column":"first_name","component":"text","visibility":"1"},{"header":"LAST NAME","column":"last_name","component":"text","visibility":"1"},{"header":"PHONE","column":"phone","component":"phone","visibility":"1"},{"header":"EMAIL","column":"email","component":"email","visibility":"1"},{"header":"SOURCE","column":"source","component":"text","visibility":"1"},{"header":"STATUS","column":"status","component":"text","visibility":"1"},{"header":"ASSIGNEE","column":"assignee","component":"user","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"},{"header":"YEAR","column":"year","component":"text","visibility":"1"},{"header":"MAKE","column":"make","component":"text","visibility":"1"},{"header":"MODEL","column":"model","component":"text","visibility":"1"},{"header":"VIN","column":"vin","component":"text","visibility":"1"},{"header":"STOCK NO","column":"stock_no","component":"text","visibility":"1"}]',
                'query' => '[{"header":"Leads Time Period","column":"created_at","join":"AND","comparator":"less than","type":"Relative Time Period Comparision","value_1":"30","value_2":""}]',
                'default' => '1',
            ],
            [
                'header' => 'New Leads',
                'view' => '[{"header":"FIRST NAME","column":"first_name","component":"text","visibility":"1"},{"header":"LAST NAME","column":"last_name","component":"text","visibility":"1"},{"header":"PHONE","column":"phone","component":"phone","visibility":"1"},{"header":"EMAIL","column":"email","component":"email","visibility":"1"},{"header":"SOURCE","column":"source","component":"text","visibility":"1"},{"header":"STATUS","column":"status","component":"text","visibility":"1"},{"header":"ASSIGNEE","column":"assignee","component":"user","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"},{"header":"YEAR","column":"year","component":"text","visibility":"1"},{"header":"MAKE","column":"make","component":"text","visibility":"1"},{"header":"MODEL","column":"model","component":"text","visibility":"1"},{"header":"VIN","column":"vin","component":"text","visibility":"1"},{"header":"STOCK NO","column":"stock_no","component":"text","visibility":"1"}]',
                'query' => '[{"header":"Status","column":"status","join":"AND","comparator":"is","type":"Multi Select","value_1":["New"],"value_2":""}]',
                'default' => '1',
            ],
        ];

        $leadResponseViewModel = new LeadResponseViewModel();
        $leadResponseViewModel->protect(false);
        foreach ($arrays as $array) {
            $leadResponseViewModel->ignore(true)->insert($array);
        }
        $leadResponseViewModel->protect(true);
    }
}