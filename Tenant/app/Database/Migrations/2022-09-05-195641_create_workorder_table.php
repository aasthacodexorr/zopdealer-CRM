<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWorkorderTableForTheClient extends Migration
{
    public function up()
    {

        $this->forge->addField([
            'id' => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],

            'status'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => TRUE,
                'default'   => '',
            ],
            'auto_sync_expense_flag'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'type'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => TRUE,
                'default'        => ''
            ],

            'check_in_date_time'       => [
                'type'           => 'DATETIME',
                'null'           => TRUE,
            ],
            'check_out_date_time'       => [
                'type'           => 'DATETIME',
                'null'           => TRUE,
            ],

            'check_in_odometer'          => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'check_out_odometer'          => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],

            'assigned_mechanic'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'assigned_advisor'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'search_keyword'          => [
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

        $this->forge->createTable('work_orders', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('work_orders');
    }
}
