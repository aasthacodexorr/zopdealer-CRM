<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddExactSearchFieldInLeadResponseTable extends Migration
{
    public function up()
    {
        $fields = [
            'search_keyword'          => [
                'type'   => 'TEXT',
                'null'   => TRUE,
            ]

            ];

        $this->forge->addColumn('lead_responses', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('lead_responses','search_keyword');
    }
}
