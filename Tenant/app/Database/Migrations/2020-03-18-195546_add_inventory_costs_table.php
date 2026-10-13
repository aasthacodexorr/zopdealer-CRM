<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddInventoryCost extends Migration
{
        public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'description'       => [
                'type'           => 'TEXT',
                'default'        => '',
                'null'           => TRUE,
            ],
            'invoice'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'        => '',
                'null'           => TRUE,
            ],
            'vendor_name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'        => '',
                'null'           => TRUE,
            ],
            'price'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'quantity'          => [
                'type'   => 'INT',
                'unsigned'      => TRUE,
                'default'       => 0,
            ],
            'discount'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
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
            'sub_total'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'purchase_date'       => [
                'type'           => 'DATE',
                'null'           => TRUE,
            ],
            'phonetic_string'          => [
                'type'   => 'TEXT',
                'null'   => TRUE,
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
            'inventory_id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
            ],

        ]);

        $this->forge->addKey('id', TRUE);
        $this->forge->addForeignKey('inventory_id','inventory','inventory_id','CASCADE','CASCADE');

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('inventory_costs', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('inventory_costs');
    }
}