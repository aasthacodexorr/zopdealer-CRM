<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class ResetCraigslistLocationNameForCurrentUsers extends Migration
{

    public function up()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('craigslist');
        $builder->set('location', 'abbotsford');
        $builder->where('location', '');
        $builder->update();
    }

    public function down()
    {
    }
}
