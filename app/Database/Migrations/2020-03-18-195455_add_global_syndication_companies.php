<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * AddGlobalSyndicationCompanies This migration is related to building the table for the syndication company list.
 */
class AddGlobalSyndicationCompanies extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'label'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'slug'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'image_url'       => [
                'type'           => 'TEXT',
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

        $this->forge->addKey('id', TRUE);

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];

        $this->forge->createTable('global_syndication_companies', TRUE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('global_syndication_companies',TRUE);
    }
}
