<?php

namespace App\Database\Migrations\Common;

use CodeIgniter\Database\Migration;

class CreateSuperUsers extends Migration
{
    public function up()
	{
        /***
         *  Users table contains id email and password
         */
        $this->forge->addField([
            'id'          => [
				'type'           => 'INT',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
            ],
            'email'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'password'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '300',
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
        $this->forge->addUniqueKey(['email']);

		/* Create the table for the users  */
		$attributes = ['ENGINE' => 'InnoDB',
						'COLLATE' => 'utf8mb4_unicode_ci',
					'CHARACTER SET' => 'utf8mb4',
					];

		$this->forge->createTable('users', FALSE, $attributes);



        
    }

    public function down()
	{
        $this->forge->dropTable('users',TRUE);
        
	}
}