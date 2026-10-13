<?php

namespace Tenant\app\Database\Seeds;

use Tenant\app\Models\ProductViewModel;
use Tenant\app\Models\ReviewFeedbackViewModel;
use Tenant\app\Models\VendorViewModel;

class ReviewFeedbackViewSeeds extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $arrays = [
            [
                'header' => 'Feedbacks',
                'view' => '[{"header":"FIRST NAME","column":"first_name","component":"text","visibility":"1"},{"header":"LAST NAME","column":"last_name","component":"text","visibility":"1"},{"header":"PHONE","column":"phone","component":"phone","visibility":"0"},{"header":"MOBILE","column":"mobile","component":"phone","visibility":"1"},{"header":"EMAIL","column":"email","component":"email","visibility":"1"},{"header":"STATUS","column":"status","component":"badge","visibility":"1"},{"header":"RESPONSE","column":"response","component":"badge","visibility":"1"},{"header":"SENT COUNT","column":"sent_counter","component":"text","visibility":"1"},{"header":"COMMENT","column":"comment","component":"text","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"0"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"0"}]',
                'query' => '[]',
                'default' => '1',
            ],
        ];

        $reviewFeedbackViewModel = new ReviewFeedbackViewModel();
        $reviewFeedbackViewModel->protect(false);
        foreach ($arrays as $array) {
            $reviewFeedbackViewModel->ignore(true)->insert($array);
        }
        $reviewFeedbackViewModel->protect(true);
    }
}
