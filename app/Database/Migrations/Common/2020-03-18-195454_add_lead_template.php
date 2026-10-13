<?php

namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class AddLeadTemplateFormTable extends Migration
{
    public function up()
	{
        $this->forge->addField([
            'id'          => [
				'type'           => 'INT',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
            ],
            'name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'unique'         => TRUE,
            ],
            'description'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
            ],
            'type'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
			],
			'source'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
			],
			'disclosure'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
			],
			'default_terms_and_condition'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
			],
			'VOI'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
			],
			'override_custom_template'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
			],
			'custom_template'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
            ],
			'slug'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'recipient_email'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '500',
                'null'           => TRUE,
            ],
            'custom_css'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
            ],
            'auto_reply'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 1,
            ],
            'auto_reply_template'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
            ],
            'admin_reply_template'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
            ],
            'override_auto_reply_content'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'auto_reply_content'     => [
                'type'           => 'TEXT',
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
			],
            
        ]);

        $this->forge->addKey('id', TRUE);

		/* Create the table for the clients */
		$attributes = ['ENGINE' => 'InnoDB',
						'COLLATE' => 'utf8mb4_unicode_ci',
					'CHARACTER SET' => 'utf8mb4',
					];
		$this->forge->createTable('lead_template_forms', FALSE, $attributes);

    }

    public function down()
	{
		$this->forge->dropTable('lead_template_forms',TRUE);
	}
}