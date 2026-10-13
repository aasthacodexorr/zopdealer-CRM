<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * AddSyndicationInboundForTheSuperAdmin This migration is related to the inbound syndication feed.
 */
class AddSyndicationInboundForTheSuperAdmin extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'syndication_inbound_id'          => [
                'type'           => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'client_id'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
            ],
            'domain_name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
            ],
            'source'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
            ],
            
            'file_name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'identity_id'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
            ],
            'cron'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
            ],
            'comment'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
            ],
            'status'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
            ],
            'folder'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => TRUE,
            ],
            'modified_by'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
            ],
            
            'updated_at'       => [
				'type'           => 'DATETIME',
				'null'           => TRUE,
			],
			'created_at'       => [
				'type'           => 'DATETIME',
				'null'           => TRUE,
			],
			'deleted_at'       => [
				'type'           => 'DATETIME',
				'null'           => TRUE,
			],
            'db_name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
        ]);

        $this->forge->addKey('syndication_inbound_id', TRUE);

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];

        $this->forge->createTable('syndication_inbound', TRUE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('syndication_inbound',TRUE);
    }
}
