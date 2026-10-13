<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterUnsignedAttributesForInventoryCost extends Migration
{
    public function up()
    {
        $fields = [
            'quantity' => [
                'type' => 'INT',
                'unsigned' => FALSE,
                'default' => 0,
            ],
            'tax_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '8,2',
                'null' => TRUE,
                'unsigned' => FALSE,
                'default' => 0,
            ],
            'sub_total' => [
                'type' => 'DECIMAL',
                'constraint' => '8,2',
                'null' => TRUE,
                'unsigned' => FALSE,
                'default' => 0,
            ],
            'tax_amount_secondary' => [
                'type' => 'DECIMAL',
                'constraint' => '8,2',
                'null' => TRUE,
                'unsigned' => FALSE,
                'default' => 0,
            ],
            'tax_amount_total' => [
                'type' => 'DECIMAL',
                'constraint' => '8,2',
                'null' => TRUE,
                'unsigned' => FALSE,
                'default' => 0,
            ],
        ];

        $this->forge->modifyColumn('inventory_costs', $fields);
    }

    public function down()
    {
        $fields = [
            'quantity' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'default' => 0,
            ],
            'tax_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '8,2',
                'null' => TRUE,
                'unsigned' => TRUE,
                'default' => 0,
            ],
            'sub_total' => [
                'type' => 'DECIMAL',
                'constraint' => '8,2',
                'null' => TRUE,
                'unsigned' => TRUE,
                'default' => 0,
            ],
            'tax_amount_secondary' => [
                'type' => 'DECIMAL',
                'constraint' => '8,2',
                'null' => TRUE,
                'unsigned' => TRUE,
                'default' => 0,
            ],
            'tax_amount_total' => [
                'type' => 'DECIMAL',
                'constraint' => '8,2',
                'null' => TRUE,
                'unsigned' => TRUE,
                'default' => 0,
            ],
        ];

        $this->forge->modifyColumn('inventory_costs', $fields);
    }
}
