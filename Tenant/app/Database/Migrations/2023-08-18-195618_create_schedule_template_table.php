<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddScheduleTemplateTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'datetime'       => [
                'type'           => 'DATETIME',
                'null'           => TRUE,
            ],
            'frequency'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'        => ''
            ],
            'format'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
            ],
            'enable'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 1,
            ],
            'primary_recipients'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'        => ''
            ],
            'additional_recipients'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
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
            ]
        ]);

        $this->forge->addKey('id', TRUE);
        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('schedule_templates', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('schedule_templates');
    }
}
