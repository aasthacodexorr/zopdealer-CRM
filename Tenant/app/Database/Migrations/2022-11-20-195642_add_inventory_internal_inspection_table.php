<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddInventoryInternalInspectionTable extends Migration
{
    public function up(){

        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'inventory_id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE
            ],
            'question'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'       => '',
            ],
            'answer'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'       => '',
            ],
            'type'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => TRUE,
            ],
            'options'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '500',
                'null'           => TRUE,
            ],
            'has_additional_information'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'additional_information'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'       => ''
            ],
            'additional_information_placeholder'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '300',
                'null'           => TRUE,
                'default'       => ''
            ],
            'css_class'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '300',
                'null'           => TRUE,
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
            ],
        ]);

        $this->forge->addKey('id', TRUE);
        $this->forge->addForeignKey('inventory_id','inventory','inventory_id','CASCADE','CASCADE');

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('inventory_internal_inspection', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('inventory_internal_inspection');
    }
}