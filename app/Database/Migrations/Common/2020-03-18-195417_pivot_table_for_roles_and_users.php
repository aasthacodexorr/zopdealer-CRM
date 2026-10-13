<?php

namespace App\Database\Migrations\Common;

use CodeIgniter\Database\Migration;

class PivotUserRole extends Migration
{
    public function up()
	{
        /***
         *  Implementation of Resource based RBAC roles and permissions for the resources.
         */
        $this->forge->addField([
            'user_id'          => [
				'type'           => 'INT',
				'unsigned'       => TRUE,
            ],
            'role_id'       => [
				'type'           => 'INT',
				'unsigned'       => TRUE,
            ],
            
        ]);

        /* Add the constraints for the table */
        $this->forge->addKey(['user_id', 'role_id']);

        $this->forge->addForeignKey('user_id','users','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('role_id','roles','id','CASCADE','CASCADE');

		/* Create the table for the pivot table of user and role */
		$attributes = ['ENGINE' => 'InnoDB',
						'COLLATE' => 'utf8mb4_unicode_ci',
					'CHARACTER SET' => 'utf8mb4',
					];
		$this->forge->createTable('user_role', FALSE, $attributes);




        
    }

    public function down()
	{
        $this->forge->dropTable('user_role',TRUE);
        
	}
}