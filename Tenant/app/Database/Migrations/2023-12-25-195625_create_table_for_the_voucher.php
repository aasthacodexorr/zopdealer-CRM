<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableForTheVoucher extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'title'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'description'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'        => ''
            ],
            'disclosure'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'        => ''
            ],
            'expiry_date'       => [
                'type'           => 'DATE',
                'null'           => TRUE,
                'default'       => null
            ],
            'template'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'       => ''
            ],
            'status'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'        => ''
            ],
            'module'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'module_id'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
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
            'search_keyword'          => [
                'type'   => 'TEXT',
                'null'   => TRUE,
                'default'   => ''
            ]
        ]);

        $this->forge->addKey('id', TRUE);
        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('vouchers', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('vouchers');
    }
}
