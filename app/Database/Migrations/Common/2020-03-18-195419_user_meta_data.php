<?php

namespace App\Database\Migrations\Common;

use CodeIgniter\Database\Migration;

class UsersMetaData extends Migration
{
    public function up()
	{
        /***
         *  Users table contains id email and password
         */
        $this->forge->addField([
            'user_id'          => [
				'type'           => 'INT',
				'unsigned'       => TRUE,
            ],
            'inventory_view'       => [
				'type'           => 'INT',
				'default'		 => 1
			],
			'lead_response_view'	=>[
				'type'           => 'INT',
				'default'		 => 1
			],
			'vendor_view'	=>[
				'type'           => 'INT',
				'default'		 => 1
			],
			'customer_view'	=>[
				'type'           => 'INT',
				'default'		 => 1
			],
			'name'	=>[
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
			],
            
        ]);


        		/* Add the constraints for the table */
        $this->forge->addKey('user_id', TRUE);

        $this->forge->addForeignKey('user_id','users','id');
        

		/* Create the table for the user metadata */
		$attributes = ['ENGINE' => 'InnoDB',
						'COLLATE' => 'utf8mb4_unicode_ci',
					'CHARACTER SET' => 'utf8mb4',
                    ];
                    
		$this->forge->createTable('user_metadata', FALSE, $attributes);



        
    }

    public function down()
	{
        $this->forge->dropTable('user_metadata',TRUE);
        
	}
}