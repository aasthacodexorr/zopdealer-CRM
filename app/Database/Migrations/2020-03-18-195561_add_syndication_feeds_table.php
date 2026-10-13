<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * AddSyndicationFeedForTheSuperAdmin This migration is for the syndication feed.
 */
class AddSyndicationFeedForTheSuperAdmin extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'syndication_feed_id'          => [
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
            'ftp_host'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
            ],
            'ftp_user'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
            ],
            'ftp_pass'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
            ],
            'ftp_port'       => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
                'null'           => TRUE,
                'default'        => 21,
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

        $this->forge->addKey('syndication_feed_id', TRUE);

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];

        $this->forge->createTable('syndication_feed', TRUE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('syndication_feed',TRUE);
    }
}
