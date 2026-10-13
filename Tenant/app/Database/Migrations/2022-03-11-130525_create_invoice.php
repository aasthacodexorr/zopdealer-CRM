<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInvoice extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'invoice_id' => [
                'type'           => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'invoice_no' => [
                'type'           => 'VARCHAR',
                'constraint'     => '20',
            ],
            'invoice_date' => [
                'type'           => 'DATE',
            ],
            'invoice_fullid' => [
                'type'           => 'VARCHAR',
                'constraint'     => '25',
            ],
            'subscription_no' => [
                'type'           => 'VARCHAR',
                'constraint'     => '25',
            ],
            'amount' => [
                'type'           => 'VARCHAR',
                'constraint'     => '10',
            ],
            'status' => [
                'type'           => 'VARCHAR',
                'constraint'     => '10',
            ],

        ]);

        $this->forge->addKey('invoice_id', TRUE);

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];

        $this->forge->createTable('invoice', TRUE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('invoice');
    }
}
