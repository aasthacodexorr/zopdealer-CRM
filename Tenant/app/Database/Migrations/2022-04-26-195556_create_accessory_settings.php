<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAccessorySettingTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'        => '',
            ],
            'amount'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'        => 0
            ],
            'description' => [
                'type'   => 'TEXT',
                'null'   => TRUE,
                'default'        => '',
            ],
            'tax_type'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'        => '',
            ],
            'tax_percentage'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'tax_amount'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'tax_type_secondary'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'        => '',
            ],
            'tax_percentage_secondary'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'tax_amount_secondary'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'tax_amount_total'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
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
            'cost'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'        => 0
            ],
            'cost_tax_type'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'        => '',
            ],
            'cost_tax_percentage'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'cost_tax_amount'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'cost_tax_type_secondary'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'        => '',
            ],
            'cost_tax_amount_secondary'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'cost_tax_percentage_secondary'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'cost_tax_amount_total'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],

        ]);

        $this->forge->addKey('id', TRUE);

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('accessories_settings', TRUE, $attributes);
    }


    public function down()
    {
        $this->forge->dropTable('accessories_settings');
    }
}
