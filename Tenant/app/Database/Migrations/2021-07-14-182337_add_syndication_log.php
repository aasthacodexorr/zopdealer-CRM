<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSyndicationLog extends Migration
{

    public function up()
    {
        /* Add fields for the table */
        $this->forge->addField([
            'syndication_log_id' => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'message' => [
                'type' => 'TEXT',
            ],
            'created_at datetime default current_timestamp',
            'feed_id' => [
                'type' => 'INT',
                'constraint' => '11',
                'unsigned'   => TRUE,
            ],
            'file_name' => [
                'type'  => 'VARCHAR',
                'constraint' => '255',
                'null'       => TRUE,
            ],
            'updated_at datetime default NULL on update current_timestamp',            
            'invcount' => [
                'type' => 'INT',
                'constraint' => '11',
                'default'  => '0',
            ],
            'source'  => [
                'type'    => 'VARCHAR',
                'constraint' => '50',
                'null'     => TRUE,
            ],
        ]);

        /* Add the constraints for the table */
        $this->forge->addKey('syndication_log_id', TRUE);

        /* Create the table for the clients */
        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('syndication_log', TRUE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('syndication_log');
    }
}
