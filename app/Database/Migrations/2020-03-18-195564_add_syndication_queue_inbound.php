<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * AddSyndicationInboundForTheSuperAdmin This migration is related to the inbound syndication feed.
 */
class AddSyndicationQueueInboundForTheSuperAdmin extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'syndication_queue_inbound_id'          => [
                'type'           => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'syndication_inbound_id'       => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
            ],
            'scheduled_at'       => [
				'type'           => 'DATETIME',
				'null'           => TRUE,
			],
            'status'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '10',
                'default'        => 'pending'
            ],
            'message'           => [
                'type'   => 'TEXT',
                'null'           => TRUE,
            ],
            'oldfile'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE
            ],
            'updated_at'       => [
				'type'           => 'DATETIME',
				'null'           => TRUE,
			],
            
        ]);

        $this->forge->addKey('syndication_queue_inbound_id', TRUE);
        $this->forge->addForeignKey('syndication_inbound_id', 'syndication_inbound', 'syndication_inbound_id', 'CASCADE', 'CASCADE');

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];

        $this->forge->createTable('syndication_queue_inbound', TRUE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('syndication_queue_inbound',TRUE);
    }
}
