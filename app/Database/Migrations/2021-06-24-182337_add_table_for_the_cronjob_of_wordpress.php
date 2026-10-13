<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CronJobForWordpress extends Migration
{
	protected $DBGroup = 'default';
	
    public function up()
    {
        /* Add fields for the table */
        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'url'          => [
                'type'   => 'TEXT',
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
        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('wordpress_jobs', TRUE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('wordpress_jobs');
    }
}
