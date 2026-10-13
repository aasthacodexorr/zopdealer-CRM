<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddClientForBusiness extends Migration
{
	
	public function up()
	{
		/* Add fields for the table */
		$this->forge->addField([
			'client_id'          => [
				'type'           => 'INT',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			],
			'name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
			],
			'street'       => [
                'type'           => 'TEXT',
			],
			'city'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '100',
			],
			'state'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '100',
			],
			'zip'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '100',
			],
			'email'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '255',
			],
			'telephone'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '100',
			],
			'alternate_telephone'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '100',
				'null'           => TRUE,
			],
			'subdomain'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '255',
				'unique'         => TRUE,
			],
			'website'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '255',
				'null'           => TRUE,
			],
			'dealer_registration_number'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '100',
				'null'           => TRUE,
			],
			'gst_number'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '100',
				'null'           => TRUE,
			],
			'hst_number'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '100',
				'null'           => TRUE,
			],
			'client_hash'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '32',
			],
			'connection_group'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '100',
				'null'           => TRUE,
				'default'        => 'default',
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
		$this->forge->addKey('client_id', TRUE);
		$this->forge->addUniqueKey(['subdomain', 'website']);
		$this->forge->addUniqueKey('client_hash');

		/* Create the table for the clients */
		$attributes = ['ENGINE' => 'InnoDB',
						'COLLATE' => 'utf8mb4_unicode_ci',
					'CHARACTER SET' => 'utf8mb4',
					];
		$this->forge->createTable('clients', TRUE, $attributes);
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('clients',TRUE);
	}
}
