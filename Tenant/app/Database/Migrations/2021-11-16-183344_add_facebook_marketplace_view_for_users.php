<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFacebookMarketPlaceViewForTheUser extends Migration
{
    public function up()
    {
        $fields = [
            'facebook_marketplace_view'    => [
                'type'           => 'INT',
                'default'         => 1
            ],
        ];

        $this->forge->addColumn('user_metadata', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('user_metadata', ['facebook_marketplace_view']); // to drop one single column
    }
}
