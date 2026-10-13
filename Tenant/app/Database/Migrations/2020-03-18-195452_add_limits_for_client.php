<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLimitsForClient extends Migration {
    
    public function up()
	{
        		/* Add fields for the table */
		$this->forge->addField([
			'id'          => [
				'type'           => 'INT',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			],
			'slide_limit'       => [
				'type'           => 'INT',
				'unsigned'       => TRUE,
				'default'		 => 0,
			],
			'gallery_limit'       => [
				'type'           => 'INT',
				'unsigned'       => TRUE,
				'default'		 => 0,
			],
			'blog_limit'       => [
				'type'           => 'INT',
				'unsigned'       => TRUE,
				'default'		 => 0,
			],
			'review_limit'       => [
				'type'           => 'INT',
				'unsigned'       => TRUE,
				'default'		 => 0,
			],
			'team_limit'       => [
				'type'           => 'INT',
				'unsigned'       => TRUE,
				'default'		 => 0,
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
		/* Create the table for the clients */
		/* Create the table for the clients */
		$attributes = ['ENGINE' => 'InnoDB',
						'COLLATE' => 'utf8mb4_unicode_ci',
					'CHARACTER SET' => 'utf8mb4',
					];
		$this->forge->createTable('limits', FALSE, $attributes);
    }

    public function down(){
        $this->forge->dropTable('limits');
    }
}