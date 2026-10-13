<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddExactSearchFieldInInventoryTable extends Migration
{
    public function up()
    {
        $fields = [
            'search_keyword'          => [
                'type'   => 'TEXT',
                'null'   => TRUE,
            ]

        ];

        $this->forge->addColumn('inventory', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('inventory', 'search_keyword');
    }
}
