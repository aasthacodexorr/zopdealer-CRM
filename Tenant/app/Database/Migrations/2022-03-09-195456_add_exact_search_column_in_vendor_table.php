<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddExactSearchFieldInVendorTable extends Migration
{
    public function up()
    {
        $fields = [
            'search_keyword'          => [
                'type'   => 'TEXT',
                'null'   => TRUE,
            ]

            ];

        $this->forge->addColumn('vendors', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('vendors','search_keyword');
    }
}
