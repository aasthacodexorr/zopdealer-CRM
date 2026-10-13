<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSocialConnectViewForUser extends Migration
{
    public function up()
    {
        $fields = [
            'social_connect_view'    => [
                'type'           => 'INT',
                'default'         => 1
            ],
        ];

        $this->forge->addColumn('user_metadata', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('user_metadata', ['social_connect_view']); // to drop one single column
    }
}