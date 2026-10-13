<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class UsersEmailConfigurationTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'user_id'          => [
                'type'           => 'INT',
                'unsigned'       => TRUE,
            ],

            'name' => [
                'type'  => 'VARCHAR',
                'constraint' => '255',
                'null'       => TRUE,
                'default'    => ''
            ],
            'email' => [
                'type'  => 'VARCHAR',
                'constraint' => '255',
                'null'       => TRUE,
                'default'    => ''
            ],
            'password' => [
                'type'  => 'VARCHAR',
                'constraint' => '255',
                'null'       => TRUE,
                'default'    => ''
            ],
            'auth'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 1,
            ],
            'outgoing_hostname' => [
                'type'  => 'VARCHAR',
                'constraint' => '255',
                'null'       => TRUE,
                'default'    => ''
            ],
            'outgoing_port' => [
                'type'  => 'VARCHAR',
                'constraint' => '10',
                'null'       => TRUE,
                'default'    => '465'
            ],
            'outgoing_secure' => [
                'type'  => 'VARCHAR',
                'constraint' => '10',
                'null'       => TRUE,
                'default'    => 'ssl'
            ],

            'incoming_hostname' => [
                'type'  => 'VARCHAR',
                'constraint' => '255',
                'null'       => TRUE,
                'default'    => ''
            ],
            'incoming_port' => [
                'type'  => 'VARCHAR',
                'constraint' => '10',
                'null'       => TRUE,
                'default'    => '993'
            ],
            'incoming_secure' => [
                'type'  => 'VARCHAR',
                'constraint' => '10',
                'null'       => TRUE,
                'default'    => 'ssl'
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
        $this->forge->addUniqueKey('user_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');


        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];

        $this->forge->createTable('user_email_configuration', TRUE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('user_email_configuration', TRUE);
    }
}
