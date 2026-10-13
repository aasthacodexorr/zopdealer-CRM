<?php

namespace Tenant\app\Database\Seeds;

use Tenant\app\Models\DealViewModel;
use Tenant\app\Models\LeadReportViewModel;

class LeadReportViewSeeds extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $arrays = [
            [
                'header' => 'All Leads',
                'view' => '[{"header":"LEAD ID","column":"id","component":"text","visibility":"1"},{"header":"FIRST NAME","column":"first_name","component":"text","visibility":"1"},{"header":"LAST NAME","column":"last_name","component":"text","visibility":"1"},{"header":"PHONE","column":"phone","component":"phone","visibility":"1"},{"header":"EMAIL","column":"email","component":"email","visibility":"1"},{"header":"SOURCE","column":"source","component":"text","visibility":"1"},{"header":"STATUS","column":"status","component":"text","visibility":"1"},{"header":"ASSIGNEE","column":"assignee","component":"user","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"},{"header":"YEAR","column":"year","component":"text","visibility":"1"},{"header":"MAKE","column":"make","component":"text","visibility":"1"},{"header":"MODEL","column":"model","component":"text","visibility":"1"},{"header":"VIN","column":"vin","component":"text","visibility":"1"},{"header":"STOCK NO","column":"stock_no","component":"text","visibility":"1"}]',
                'query' => '[]',
                'default' => '1',
            ],
            [
                'header' => 'New Leads - Last 24 Hours',
                'view' => '[{"header":"LEAD ID","column":"id","component":"text","visibility":"1"},{"header":"FIRST NAME","column":"first_name","component":"text","visibility":"1"},{"header":"LAST NAME","column":"last_name","component":"text","visibility":"1"},{"header":"PHONE","column":"phone","component":"phone","visibility":"1"},{"header":"EMAIL","column":"email","component":"email","visibility":"1"},{"header":"SOURCE","column":"source","component":"text","visibility":"1"},{"header":"STATUS","column":"status","component":"text","visibility":"1"},{"header":"ASSIGNEE","column":"assignee","component":"user","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"},{"header":"YEAR","column":"year","component":"text","visibility":"1"},{"header":"MAKE","column":"make","component":"text","visibility":"1"},{"header":"MODEL","column":"model","component":"text","visibility":"1"},{"header":"VIN","column":"vin","component":"text","visibility":"1"},{"header":"STOCK NO","column":"stock_no","component":"text","visibility":"1"}]',
                'query' => '[{"header":"Status","column":"status","join":"AND","comparator":"is","type":"Multi Select","value_1":["New"],"value_2":""},{"header":"Lead Date","column":"created_at","join":"AND","comparator":"less than","type":"Relative Time Period Comparision","value_1":"1","value_2":""}]',
                'default' => '1',
            ],
            [
                'header' => 'New Leads - This Month',
                'view' => '[{"header":"LEAD ID","column":"id","component":"text","visibility":"1"},{"header":"FIRST NAME","column":"first_name","component":"text","visibility":"1"},{"header":"LAST NAME","column":"last_name","component":"text","visibility":"1"},{"header":"PHONE","column":"phone","component":"phone","visibility":"1"},{"header":"EMAIL","column":"email","component":"email","visibility":"1"},{"header":"SOURCE","column":"source","component":"text","visibility":"1"},{"header":"STATUS","column":"status","component":"text","visibility":"1"},{"header":"ASSIGNEE","column":"assignee","component":"user","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"},{"header":"YEAR","column":"year","component":"text","visibility":"1"},{"header":"MAKE","column":"make","component":"text","visibility":"1"},{"header":"MODEL","column":"model","component":"text","visibility":"1"},{"header":"VIN","column":"vin","component":"text","visibility":"1"},{"header":"STOCK NO","column":"stock_no","component":"text","visibility":"1"}]',
                'query' => '[{"header":"Status","column":"status","join":"AND","comparator":"is","type":"Multi Select","value_1":["New"],"value_2":""},{"header":"Lead Date","column":"created_at","join":"AND","comparator":"is","type":"Relative Month Comparision","value_1":"0","value_2":""},{"header":"Lead Date","column":"created_at","join":"AND","comparator":"is","type":"Relative Year Comparision","value_1":"0","value_2":""}]',
                'default' => '1',
            ],
            [
                'header' => 'New Leads - Last Month',
                'view' => '[{"header":"LEAD ID","column":"id","component":"text","visibility":"1"},{"header":"FIRST NAME","column":"first_name","component":"text","visibility":"1"},{"header":"LAST NAME","column":"last_name","component":"text","visibility":"1"},{"header":"PHONE","column":"phone","component":"phone","visibility":"1"},{"header":"EMAIL","column":"email","component":"email","visibility":"1"},{"header":"SOURCE","column":"source","component":"text","visibility":"1"},{"header":"STATUS","column":"status","component":"text","visibility":"1"},{"header":"ASSIGNEE","column":"assignee","component":"user","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"},{"header":"YEAR","column":"year","component":"text","visibility":"1"},{"header":"MAKE","column":"make","component":"text","visibility":"1"},{"header":"MODEL","column":"model","component":"text","visibility":"1"},{"header":"VIN","column":"vin","component":"text","visibility":"1"},{"header":"STOCK NO","column":"stock_no","component":"text","visibility":"1"}]',
                'query' => '[{"header":"Status","column":"status","join":"AND","comparator":"is","type":"Multi Select","value_1":["New"],"value_2":""},{"header":"Lead Date","column":"created_at","join":"AND","comparator":"is","type":"Relative Month Comparision","value_1":"1","value_2":""},{"header":"Lead Date","column":"created_at","join":"AND","comparator":"is","type":"Relative Year Comparision","value_1":"0","value_2":""}]',
                'default' => '1',
            ],
            [
                'header' => 'Won Leads - This Month',
                'view' => '[{"header":"LEAD ID","column":"id","component":"text","visibility":"1"},{"header":"FIRST NAME","column":"first_name","component":"text","visibility":"1"},{"header":"LAST NAME","column":"last_name","component":"text","visibility":"1"},{"header":"PHONE","column":"phone","component":"phone","visibility":"1"},{"header":"EMAIL","column":"email","component":"email","visibility":"1"},{"header":"SOURCE","column":"source","component":"text","visibility":"1"},{"header":"STATUS","column":"status","component":"text","visibility":"1"},{"header":"ASSIGNEE","column":"assignee","component":"user","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"},{"header":"YEAR","column":"year","component":"text","visibility":"1"},{"header":"MAKE","column":"make","component":"text","visibility":"1"},{"header":"MODEL","column":"model","component":"text","visibility":"1"},{"header":"VIN","column":"vin","component":"text","visibility":"1"},{"header":"STOCK NO","column":"stock_no","component":"text","visibility":"1"}]',
                'query' => '[{"header":"Status","column":"status","join":"AND","comparator":"is","type":"Multi Select","value_1":["Won"],"value_2":""},{"header":"Lead Date","column":"created_at","join":"AND","comparator":"is","type":"Relative Month Comparision","value_1":"0","value_2":""},{"header":"Lead Date","column":"created_at","join":"AND","comparator":"is","type":"Relative Year Comparision","value_1":"0","value_2":""}]',
                'default' => '1',
            ],
            [
                'header' => 'Won Leads - Last Month',
                'view' => '[{"header":"LEAD ID","column":"id","component":"text","visibility":"1"},{"header":"FIRST NAME","column":"first_name","component":"text","visibility":"1"},{"header":"LAST NAME","column":"last_name","component":"text","visibility":"1"},{"header":"PHONE","column":"phone","component":"phone","visibility":"1"},{"header":"EMAIL","column":"email","component":"email","visibility":"1"},{"header":"SOURCE","column":"source","component":"text","visibility":"1"},{"header":"STATUS","column":"status","component":"text","visibility":"1"},{"header":"ASSIGNEE","column":"assignee","component":"user","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"},{"header":"YEAR","column":"year","component":"text","visibility":"1"},{"header":"MAKE","column":"make","component":"text","visibility":"1"},{"header":"MODEL","column":"model","component":"text","visibility":"1"},{"header":"VIN","column":"vin","component":"text","visibility":"1"},{"header":"STOCK NO","column":"stock_no","component":"text","visibility":"1"}]',
                'query' => '[{"header":"Status","column":"status","join":"AND","comparator":"is","type":"Multi Select","value_1":["Won"],"value_2":""},{"header":"Lead Date","column":"created_at","join":"AND","comparator":"is","type":"Relative Month Comparision","value_1":"1","value_2":""},{"header":"Lead Date","column":"created_at","join":"AND","comparator":"is","type":"Relative Year Comparision","value_1":"0","value_2":""}]',
                'default' => '1',
            ],
            [
                'header' => 'Won Leads - This Year',
                'view' => '[{"header":"LEAD ID","column":"id","component":"text","visibility":"1"},{"header":"FIRST NAME","column":"first_name","component":"text","visibility":"1"},{"header":"LAST NAME","column":"last_name","component":"text","visibility":"1"},{"header":"PHONE","column":"phone","component":"phone","visibility":"1"},{"header":"EMAIL","column":"email","component":"email","visibility":"1"},{"header":"SOURCE","column":"source","component":"text","visibility":"1"},{"header":"STATUS","column":"status","component":"text","visibility":"1"},{"header":"ASSIGNEE","column":"assignee","component":"user","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"},{"header":"YEAR","column":"year","component":"text","visibility":"1"},{"header":"MAKE","column":"make","component":"text","visibility":"1"},{"header":"MODEL","column":"model","component":"text","visibility":"1"},{"header":"VIN","column":"vin","component":"text","visibility":"1"},{"header":"STOCK NO","column":"stock_no","component":"text","visibility":"1"}]',
                'query' => '[{"header":"Status","column":"status","join":"AND","comparator":"is","type":"Multi Select","value_1":["Won"],"value_2":""},{"header":"Lead Date","column":"created_at","join":"AND","comparator":"is","type":"Relative Year Comparision","value_1":"0","value_2":""}]',
                'default' => '1',
            ],
            [
                'header' => 'Won Leads - Last Year',
                'view' => '[{"header":"LEAD ID","column":"id","component":"text","visibility":"1"},{"header":"FIRST NAME","column":"first_name","component":"text","visibility":"1"},{"header":"LAST NAME","column":"last_name","component":"text","visibility":"1"},{"header":"PHONE","column":"phone","component":"phone","visibility":"1"},{"header":"EMAIL","column":"email","component":"email","visibility":"1"},{"header":"SOURCE","column":"source","component":"text","visibility":"1"},{"header":"STATUS","column":"status","component":"text","visibility":"1"},{"header":"ASSIGNEE","column":"assignee","component":"user","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"},{"header":"YEAR","column":"year","component":"text","visibility":"1"},{"header":"MAKE","column":"make","component":"text","visibility":"1"},{"header":"MODEL","column":"model","component":"text","visibility":"1"},{"header":"VIN","column":"vin","component":"text","visibility":"1"},{"header":"STOCK NO","column":"stock_no","component":"text","visibility":"1"}]',
                'query' => '[{"header":"Status","column":"status","join":"AND","comparator":"is","type":"Multi Select","value_1":["Won"],"value_2":""},{"header":"Lead Date","column":"created_at","join":"AND","comparator":"is","type":"Relative Year Comparision","value_1":"1","value_2":""}]',
                'default' => '1',
            ],
            [
                'header' => 'Contact In Future',
                'view' => '[{"header":"LEAD ID","column":"id","component":"text","visibility":"1"},{"header":"FIRST NAME","column":"first_name","component":"text","visibility":"1"},{"header":"LAST NAME","column":"last_name","component":"text","visibility":"1"},{"header":"PHONE","column":"phone","component":"phone","visibility":"1"},{"header":"EMAIL","column":"email","component":"email","visibility":"1"},{"header":"SOURCE","column":"source","component":"text","visibility":"1"},{"header":"STATUS","column":"status","component":"text","visibility":"1"},{"header":"ASSIGNEE","column":"assignee","component":"user","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"},{"header":"YEAR","column":"year","component":"text","visibility":"1"},{"header":"MAKE","column":"make","component":"text","visibility":"1"},{"header":"MODEL","column":"model","component":"text","visibility":"1"},{"header":"VIN","column":"vin","component":"text","visibility":"1"},{"header":"STOCK NO","column":"stock_no","component":"text","visibility":"1"}]',
                'query' => '[{"header":"Status","column":"status","join":"AND","comparator":"is","type":"Multi Select","value_1":["Contact in Future"],"value_2":""}]',
                'default' => '1',
            ],
            [
                'header' => 'Lost And Junk Leads',
                'view' => '[{"header":"LEAD ID","column":"id","component":"text","visibility":"1"},{"header":"FIRST NAME","column":"first_name","component":"text","visibility":"1"},{"header":"LAST NAME","column":"last_name","component":"text","visibility":"1"},{"header":"PHONE","column":"phone","component":"phone","visibility":"1"},{"header":"EMAIL","column":"email","component":"email","visibility":"1"},{"header":"SOURCE","column":"source","component":"text","visibility":"1"},{"header":"STATUS","column":"status","component":"text","visibility":"1"},{"header":"ASSIGNEE","column":"assignee","component":"user","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"},{"header":"YEAR","column":"year","component":"text","visibility":"1"},{"header":"MAKE","column":"make","component":"text","visibility":"1"},{"header":"MODEL","column":"model","component":"text","visibility":"1"},{"header":"VIN","column":"vin","component":"text","visibility":"1"},{"header":"STOCK NO","column":"stock_no","component":"text","visibility":"1"}]',
                'query' => '[{"header":"Status","column":"status","join":"AND","comparator":"is","type":"Multi Select","value_1":["Junk Lead","Lost Lead"],"value_2":""}]',
                'default' => '1',
            ]
        ];

        foreach ($arrays as $array) {
            $leadReportViewModel = new LeadReportViewModel();
            $leadReportViewModel->protect(false);
            $leadReportViewModel->ignore(true)->insert($array);
            $leadReportViewModel->protect(true);
        }
    }
}
