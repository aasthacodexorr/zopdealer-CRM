<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsActiveColumnForTheUser extends Migration
{
    public function up()
    {
        $fields = [
            'is_active'    => [
                'type'           => 'INT',
                'default'         => 1
            ],
        ];

        $this->forge->addColumn('user_metadata', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('user_metadata', ['is_active']);
    }
}
