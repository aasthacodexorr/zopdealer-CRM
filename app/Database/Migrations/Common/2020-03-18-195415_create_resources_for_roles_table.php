<?php

namespace App\Database\Migrations\Common;

use CodeIgniter\Database\Migration;

class ResourcesForRBAC extends Migration
{
    public function up()
	{
        /***
         *  Implementation of Resource based RBAC roles and permissions for the resources.
         */
        $this->forge->addField([
            'id'          => [
				'type'           => 'INT',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
            ],
            'name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'title'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '255',
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

        /* Add the constraints for the table */
		$this->forge->addKey('id', TRUE);
		$this->forge->addUniqueKey(['name']);

		/* Create the table for the resources */
		$attributes = ['ENGINE' => 'InnoDB',
						'COLLATE' => 'utf8mb4_unicode_ci',
					'CHARACTER SET' => 'utf8mb4',
					];
		$this->forge->createTable('resources', FALSE, $attributes);




        
    }

    public function down()
	{
        $this->forge->dropTable('resources',TRUE);
        
	}
}