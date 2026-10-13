<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddExactSearchFieldInCustomerTable extends Migration
{
    public function up()
    {
        $fields = [
            'search_keyword'          => [
                'type'   => 'TEXT',
                'null'   => TRUE,
            ]

            ];

        $this->forge->addColumn('customers', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('customers','search_keyword');
    }
}
