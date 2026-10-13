<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUniqueColumToTheWorkFlowTable extends Migration
{
    public function up()
    {
        $fields = [
            'unique_index'          => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'   => ''
            ]

        ];

        $this->forge->addColumn('workflow', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('workflow', 'unique_index');
    }
}
