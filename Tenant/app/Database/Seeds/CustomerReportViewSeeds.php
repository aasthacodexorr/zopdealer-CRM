<?php

namespace Tenant\app\Database\Seeds;

use Tenant\app\Models\CustomerReportViewModel;

/**
 * CustomerReportViewSeeds Seeds The Customer Report Views while onboarding the client.
 */
class CustomerReportViewSeeds extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $arrays = [
            [
                'header' => 'All Customers',
                'view' => '[{"header":"COMPANY NAME","column":"company_name","component":"text","visibility":"1"},{"header":"FIRST NAME","column":"first_name","component":"text","visibility":"1"},{"header":"LAST NAME","column":"last_name","component":"text","visibility":"1"},{"header":"PHONE","column":"phone","component":"phone","visibility":"1"},{"header":"FAX","column":"fax","component":"phone","visibility":"1"},{"header":"MOBILE","column":"mobile","component":"phone","visibility":"1"},{"header":"EMAIL","column":"email","component":"email","visibility":"1"},{"header":"CITY","column":"city","component":"text","visibility":"1"},{"header":"PROVINCE","column":"province","component":"text","visibility":"1"},{"header":"COUNTRY","column":"country","component":"text","visibility":"1"},{"header":"POSTAL CODE","column":"postal_code","component":"text","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"}]',
                'query' => '[]',
                'default' => '1',
            ],
        ];


        foreach ($arrays as $array) {
            $customerReportViewModel = new CustomerReportViewModel();
            $customerReportViewModel->protect(false);
            $customerReportViewModel->ignore(true)->insert($array);
            $customerReportViewModel->protect(true);
        }
    }
}
