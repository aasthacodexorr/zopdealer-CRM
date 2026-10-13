<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class GlobalScheduleTable extends Migration
{
    protected $DBGroup = 'default';

    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
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
            'context'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
            ],
            'schedule_template_id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE
            ],
            'database'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'schedule'       => [
                'type'           => 'DATETIME',
                'null'           => TRUE,
            ],
            'status'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => 'PREFETCH',
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
            ],
            'unique_index'          => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'   => ''
            ]

        ]);

        $this->forge->addKey('id', TRUE);
        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('schedule', TRUE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('schedule');
    }
}
