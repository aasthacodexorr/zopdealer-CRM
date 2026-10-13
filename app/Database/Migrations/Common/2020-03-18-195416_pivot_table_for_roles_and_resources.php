<?php

namespace App\Database\Migrations\Common;

use CodeIgniter\Database\Migration;

class PivotResourceRole extends Migration
{
    public function up()
	{
        /***
         *  Implementation of Resource based RBAC roles and permissions for the resources.
         */
        $this->forge->addField([
            'resource_id'          => [
				'type'           => 'INT',
				'unsigned'       => TRUE,
            ],
            'role_id'       => [
				'type'           => 'INT',
				'unsigned'       => TRUE,
            ],
            'create'       => [
				'type'           => 'TINYINT',
                'default'        => 0,
            ],
            'read'       => [
				'type'           => 'TINYINT',
                'default'        => 0,
            ],
            'update'       => [
				'type'           => 'TINYINT',
                'default'        => 0,
            ],
            'delete'       => [
				'type'           => 'TINYINT',
                'default'        => 0,
            ],
            
        ]);

        /* Add the constraints for the table */
        $this->forge->addKey(['resource_id', 'role_id']);

        $this->forge->addForeignKey('resource_id','resources','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('role_id','roles','id','CASCADE','CASCADE');

		/* Create the table for the pivot table fo resource and role */
		$attributes = ['ENGINE' => 'InnoDB',
						'COLLATE' => 'utf8mb4_unicode_ci',
					'CHARACTER SET' => 'utf8mb4',
					];
		$this->forge->createTable('resource_role', FALSE, $attributes);




        
    }

    public function down()
	{
        $this->forge->dropTable('resource_role',TRUE);
        
	}
}