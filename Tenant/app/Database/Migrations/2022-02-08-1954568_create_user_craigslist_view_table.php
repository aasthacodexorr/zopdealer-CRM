<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCraigslistViewForTheUser extends Migration
{
    public function up()
    {
        $fields = [
            'craigslist_view'    => [
                'type'           => 'INT',
                'default'         => 1
            ],
        ];

        $this->forge->addColumn('user_metadata', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('user_metadata', ['craigslist_view']); // to drop one single column
    }
}
