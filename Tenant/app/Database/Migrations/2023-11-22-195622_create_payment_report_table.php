<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentReportViewTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'header'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'view'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
            ],
            'query'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
            ],
            'sort'          => [
                'type'           => 'TEXT',
                'null'           => TRUE,
            ],
            'default'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'created_at'       => [
                'type'           => 'DATETIME',
                'null'           => TRUE,
            ],
            'updated_at'       => [
                'type'           => 'DATETIME',
                'null'           => TRUE,
            ],
            'deleted_at'       => [
                'type'           => 'DATETIME',
                'null'           => TRUE,
            ],
        ]);

        $this->forge->addKey('id', TRUE);
        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('payment_reports_view', FALSE, $attributes);

    }

    public function down()
    {
        $this->forge->dropTable('payment_reports_view');
    }

}