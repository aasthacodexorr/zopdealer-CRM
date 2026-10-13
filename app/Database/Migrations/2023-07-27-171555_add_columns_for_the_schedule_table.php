<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnsForTheGlobalScheduleTable extends Migration
{
    public function up()
    {
        $fields = [
            'frequency'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'       => ''
            ],
            'timezone'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'        => 'UTC'
            ]
        ];

        $this->forge->addColumn('schedule', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('schedule', ['frequency','timezone']); // to drop one single column
    }
}
