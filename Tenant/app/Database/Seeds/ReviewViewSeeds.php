<?php

namespace Tenant\app\Database\Seeds;

use Tenant\app\Models\ProductViewModel;
use Tenant\app\Models\ReviewViewModel;
use Tenant\app\Models\VendorViewModel;

class ReviewViewSeeds extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $arrays = [
            [
                'header' => 'Reviews',
                'view' => '[{"header":"FEATURED","column":"featured","component":"text","visibility":"1"},{"header":"VISIBILITY","column":"visibility","component":"text","visibility":"1"},{"header":"UUID","column":"uuid","component":"text","visibility":"0"},{"header":"SOURCE","column":"source","component":"source","visibility":"1"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"0"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"0"}]',
                'query' => '[]',
                'default' => '1',
            ],
        ];

        $reviewViewModel = new ReviewViewModel();
        $reviewViewModel->protect(false);
        foreach ($arrays as $array) {
            $reviewViewModel->ignore(true)->insert($array);
        }
        $reviewViewModel->protect(true);
    }
}