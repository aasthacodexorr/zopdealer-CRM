<?php

namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class AddLeadTemplateFormElementsTable extends Migration
{
    public function up()
	{
        $this->forge->addField([
            'dom_element_id'          => [
				'type'           => 'INT',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
            ],
            'dom_label'          => [
				'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,

            ],
            'dom_placeholder'          => [
				'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
            ],
            'dom_name'          => [
				'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'dom_value'          => [
				'type'           => 'TEXT',
                'null'           => TRUE,
            ],
            'dom_value_type'          => [
				'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'        => 'text',
            ],
            'dom_id'          => [
				'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
            ],
            'dom_class'          => [
				'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
            ],
            'dom_type'          => [
				'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'        => 'text',
            ],
            'dom_validation'          => [
				'type'           => 'TEXT',
                'null'           => TRUE,
            ],
            'options'   => [
                'type'           => 'TEXT',
                'null'           => TRUE,
            ],
            'dom_order'          => [
				'type'           => 'INT',
				'unsigned'       => TRUE,
				'default'		 => 0,
            ],
            'lead_template_form_id' => [
                'type'           => 'INT',
                'unsigned'       => TRUE,
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

        $this->forge->addKey('dom_element_id', TRUE);
        //$this->forge->addUniqueKey(['lead_template_form_id', 'dom_name']);

		/* Create the table for the clients */
		$attributes = ['ENGINE' => 'InnoDB',
						'COLLATE' => 'utf8mb4_unicode_ci',
					'CHARACTER SET' => 'utf8mb4',
                    ];
                    
        $this->forge->addForeignKey('lead_template_form_id','lead_template_forms','id','CASCADE','CASCADE');
		$this->forge->createTable('lead_template_form_elements', FALSE, $attributes);
    }

    public function down()
	{
		$this->forge->dropTable('lead_template_form_elements',TRUE);
	}

}