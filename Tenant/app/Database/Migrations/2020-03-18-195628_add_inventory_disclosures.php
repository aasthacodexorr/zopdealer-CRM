<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddInventoryDisclosures extends Migration
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
            'answer_flag'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'answer'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'       => '',
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
        $this->forge->addForeignKey('inventory_id','inventory','inventory_id','CASCADE','CASCADE');

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('inventory_disclosures', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('inventory_disclosures');
    }
}