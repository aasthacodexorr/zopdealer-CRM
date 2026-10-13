<?php

namespace Tenant\app\Database\Seeds;

use Tenant\app\Models\ActivityViewModel;
use Tenant\app\Models\VendorViewModel;

class ActivityViewSeeds extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $arrays = [
            [
                'header' => 'All Activities',
                'view' => '[]',
                'query' => '[]',
                'default' => '1',
            ],
        ];

        
        foreach ($arrays as $array) {
            $activityViewModel = new ActivityViewModel();
            $activityViewModel->protect(false);
            $activityViewModel->ignore(true)->insert($array);
            $activityViewModel->protect(true);

        }
    }
}