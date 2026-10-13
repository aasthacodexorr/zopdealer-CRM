<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCustomersView extends Migration
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
                'type'           => 'blob',
                'null'           => TRUE,
            ],
            'query'       => [
                'type'           => 'blob',
                'null'           => TRUE,
            ],
            'sort'          => [
                'type'           => 'blob',
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
        $this->forge->createTable('customers_view', FALSE, $attributes);

    }

    public function down()
    {
        $this->forge->dropTable('customers_view');
    }

}