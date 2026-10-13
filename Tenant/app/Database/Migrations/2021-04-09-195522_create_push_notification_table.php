<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePushNotification extends Migration
{
        public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ],

            'sender'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '400',
                'default'   => '',
                'null'           => TRUE
            ],
            'recipient'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '400',
                'default'   => '',
                'null'           => TRUE
            ],
            'template_file'       => [
                'type'           => 'TEXT',
                'default'        => '',
                'null'           => TRUE
            ],
            'content'       => [
                'type'           => 'TEXT',
                'default'        => '',
                'null'           => TRUE
            ],
            'premium'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'subject'       => [
                'type'           => 'TEXT',
                'default'        => '',
                'null'           => TRUE
            ],
            'read'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'max_tries'          => [
                'type'   => 'INT',
                'unsigned'      => TRUE,
                'default'       => 0,
            ],
            'status'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'default'   => 'PENDING',
                'null'           => TRUE
            ],
            'comment'       => [
                'type'           => 'TEXT',
                'default'        => '',
                'null'           => TRUE
            ],
            'additional_recipients'       => [
                'type'           => 'TEXT',
                'default'        => '',
                'null'           => TRUE
            ],
            'spam'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'schedule'       => [
                'type'           => 'DATETIME',
                'null'           => TRUE,
            ],
            'identifier'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'   => '',
                'null'           => TRUE
            ],
            'context'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'   => '',
                'null'           => TRUE
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
            ]

        ]);

        $this->forge->addKey('id', TRUE);

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('push_notifications', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('push_notifications');
    }
}