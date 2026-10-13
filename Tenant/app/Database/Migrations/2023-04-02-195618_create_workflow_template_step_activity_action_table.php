<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWorkFlowTemplateStepActivityActionTable extends Migration
{

    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ],
            'title'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'   => '',
            ],
            'description'       => [
                'type'           => 'TEXT',
                'default'        => '',
                'null'           => TRUE,
            ],
            'notify'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'notification_method'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'   => '',
            ],
            'automate_assignment'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'default_assignee'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'priority'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'activity_type'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'activity_method'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'wft_step_id'          => [
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
            ]

        ]);

        $this->forge->addKey('id', TRUE);
        $this->forge->addForeignKey('wft_step_id', 'workflow_template_steps', 'id', 'CASCADE', 'CASCADE');

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('workflow_template_step_activity_action', FALSE, $attributes);
    }


    public function down()
    {
        $this->forge->dropTable('workflow_template_step_activity_action');
    }
}
