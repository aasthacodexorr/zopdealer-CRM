<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVersionColumnForCraigslistTable extends Migration
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

        $this->forge->addColumn('craigslist', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('craigslist', 'current_modification_hash');
    }
}
