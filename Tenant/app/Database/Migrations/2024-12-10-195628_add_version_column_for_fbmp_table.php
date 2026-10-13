<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVersionColumnForFBMPTable extends Migration
{
    public function up()
    {
        $fields = [
            'current_modification_hash'          => [
                'type'           => 'VARCHAR',
                'constraint'     => '350',
                'null'           => TRUE,
                'default'        => ''
            ]

        ];

        $this->forge->addColumn('facebook_marketplace', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('facebook_marketplace', 'current_modification_hash');
    }
}
