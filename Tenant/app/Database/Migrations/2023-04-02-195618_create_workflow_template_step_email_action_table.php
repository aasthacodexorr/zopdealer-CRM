<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWorkFlowTemplateStepEmailActionTable extends Migration
{

    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ],
            'subject'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'   => '',
            ],
            'content'       => [
                'type'           => 'TEXT',
                'default'        => '',
                'null'           => TRUE,
            ],
            'wft_step_id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE
            ],
            'override_custom_template'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
			],
            'primary_recipients'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'   => '',
            ],
            'additional_recipients'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'   => '',
            ],
            'custom_template'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
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
        $this->forge->createTable('workflow_template_step_email_action', FALSE, $attributes);
    }


    public function down()
    {
        $this->forge->dropTable('workflow_template_step_email_action');
    }
}
