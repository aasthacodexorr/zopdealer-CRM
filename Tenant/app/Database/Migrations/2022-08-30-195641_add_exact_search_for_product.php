<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddExactSearchFieldInProductTable extends Migration
{
    public function up()
    {
        $fields = [
            'search_keyword'          => [
                'type'   => 'TEXT',
                'null'   => TRUE,
            ]

            ];

        $this->forge->addColumn('products', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('products','search_keyword');
    }
}
