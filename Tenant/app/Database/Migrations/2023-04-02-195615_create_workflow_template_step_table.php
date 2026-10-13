<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWorkFlowTemplateStepTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'index'       => [
                'type'           => 'INT',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'wait_time'       => [
                'type'           => 'INT',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'wait_time_unit'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'       => ''
            ],
            'action'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'       => ''
            ],
            'workflow_template_id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE
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
        $this->forge->addForeignKey('workflow_template_id', 'workflow_templates', 'id', 'CASCADE', 'CASCADE');

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('workflow_template_steps', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('workflow_template_steps');
    }
}
