<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * CreateTableForPasswordRequestAuth0V2 This migration is related to the password request on Oauth2
 */
class CreateTableForPasswordRequestAuth0V2 extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'email'    => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'        => '',
            ],
            'connection_id'    => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
            ], 
            'url_segment'    => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
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
        $this->forge->addKey('url_segment', FALSE, TRUE);

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];

        $this->forge->createTable('password_requests', TRUE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('password_requests', TRUE);
    }
}
