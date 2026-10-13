<?php

namespace App\Database\Migrations\Common;

use CodeIgniter\Database\Migration;

class AppConfiguration extends Migration
{
    public function up()
	{
        /***
         *  Contains name of the application (organization) and add other configurations here
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
			'vin_company'       => [
                'type'           => 'VARCHAR',
				'constraint'     => '255',
				'default'		=> 'vinaudit',
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

		/* Create the table for the app information */
		$attributes = ['ENGINE' => 'InnoDB',
						'COLLATE' => 'utf8mb4_unicode_ci',
					'CHARACTER SET' => 'utf8mb4',
                    ];
                    
		$this->forge->createTable('app', FALSE, $attributes);



        
    }

    public function down()
	{
        $this->forge->dropTable('app',TRUE);
        
	}
}