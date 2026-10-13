<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration to add multi-source support to the fbleads table
 */
class AddMultiSourceSupportToFbleads extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'retailer_id' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'creation_time' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'leadgen_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'platform' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'campaign_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'ad_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'form_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'data' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'full_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'client_id' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'message' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'source' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'provider_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->createTable('fbleads', true, [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARSET' => 'utf8mb4',
        ]);

    }

    public function down()
    {
        $this->forge->dropTable('fbleads', true);
    }
}
