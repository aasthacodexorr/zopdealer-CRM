<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSMSEntryForTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'sid'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'from'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'to'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'body'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
            ],
            'status'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'       => 'SENT'
            ],
            'direction'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'read'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
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
        $this->forge->createTable('sms_conversation', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('sms_conversation');
    }
}
