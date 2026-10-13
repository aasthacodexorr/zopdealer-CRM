<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class ResetDateColumnForThePaymentTable extends Migration
{
    public function up()
    {

        $this->forge->dropColumn('payments', 'date');

        $db      = \Config\Database::connect();
        $db->query("ALTER TABLE `payments` ADD `date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ;");
    }

    public function down()
    {
        $fields = [
            'date'          => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'       => ''
            ]

        ];

        $this->forge->addColumn('payments', $fields);
    }
}
