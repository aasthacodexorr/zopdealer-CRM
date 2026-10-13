<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuditOutboxTable extends Migration
{

    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ],
            'database' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => TRUE,
                'default'    => '',
            ],
            'database_uid' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => TRUE,
                'default'    => '',
            ],
            'event_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => TRUE,
                'default'    => '',
            ],
            'payload' => [
                'type' => 'TEXT',
                'null' => TRUE,
            ],
            'response' => [
                'type' => 'TEXT',
                'null' => TRUE,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => TRUE,
                'default'    => 'PENDING',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],
        ]);

        $this->forge->addKey('id', TRUE);
        $this->forge->addKey('event_id');
        $this->forge->addKey('status');
        $this->forge->addKey('database_uid');

        $attributes = [
            'ENGINE'        => 'InnoDB',
            'COLLATE'       => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];

        $this->forge->createTable('audit_outbox', TRUE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('audit_outbox', TRUE);
    }
}
