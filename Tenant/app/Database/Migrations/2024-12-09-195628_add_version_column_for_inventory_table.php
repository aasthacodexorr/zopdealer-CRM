<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVersionColumnForInventory extends Migration
{
    public function up()
    {
        $fields = [
            'modification_hash'          => [
                'type'           => 'VARCHAR',
                'constraint'     => '350',
                'null'           => TRUE,
                'default'        => ''
            ]

        ];

        $this->forge->addColumn('inventory', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('inventory', 'modification_hash');
    }
}
