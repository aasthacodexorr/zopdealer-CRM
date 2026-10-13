<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSubStatusAndModeColumnsInPaymentTable extends Migration
{
    public function up()
    {
        $fields = [
            'sub_status'          => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'        => '',
            ],
            'mode'          => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'        => '',
            ]

        ];

        $this->forge->addColumn('payments', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('payments', ['sub_status','mode']);
    }
}
