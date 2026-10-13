<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableForTheVoucherTemplate extends Migration
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
            'expiry_date_type'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'       => ''
            ],
            'expiry_date'       => [
                'type'           => 'DATE',
                'null'           => TRUE,
                'default'       => null
            ],
            'expiry_date_interval'       => [
                'type'           => 'INT',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'expiry_date_interval_unit'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'       => ''
            ],
            'template'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'       => ''
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
            ]
        ]);

        $this->forge->addKey('id', TRUE);
        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('voucher_templates', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('voucher_templates');
    }
}
