<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDealTable extends Migration
{
    public function up()
    {

        $this->forge->addField([
            'id' => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'type'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => TRUE,
                'default'        => ''
            ],
            'date'       => [
                'type'           => 'DATE',
                'null'           => TRUE,
            ],
            'retail_or_wholesale_flag'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'status'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'delivery_date_time'       => [
                'type'           => 'DATETIME',
                'null'           => TRUE,
            ],
            'note' => [
                'type'   => 'TEXT',
                'null'   => TRUE,
                'default'   => '',
            ],
            'disclosure' => [
                'type'   => 'TEXT',
                'null'   => TRUE,
                'default'   => '',
            ],
            'condition' => [
                'type'   => 'TEXT',
                'null'   => TRUE,
                'default'   => '',
            ],
            'approved_by'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'salesperson'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'close_date'       => [
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
        ]);
    
        $this->forge->addKey('id', TRUE);

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];

        $this->forge->createTable('deals', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('deals');
    }
}
