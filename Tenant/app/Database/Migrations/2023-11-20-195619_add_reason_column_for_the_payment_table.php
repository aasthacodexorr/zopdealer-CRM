<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddReasonColumnInPaymentTable extends Migration
{
    public function up()
    {
        $fields = [
            'reason'          => [
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
        $this->forge->dropColumn('payments', 'reason');
    }
}
