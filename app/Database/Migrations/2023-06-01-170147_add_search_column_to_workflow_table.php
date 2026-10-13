<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSearchColumnToWorkFlowTable extends Migration
{
    public function up()
    {
        $fields = [
            'search_keyword'          => [
                'type'   => 'TEXT',
                'null'   => TRUE,
                'default'   => ''
            ]

            ];

        $this->forge->addColumn('workflow', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('workflow','search_keyword');
    }
}
