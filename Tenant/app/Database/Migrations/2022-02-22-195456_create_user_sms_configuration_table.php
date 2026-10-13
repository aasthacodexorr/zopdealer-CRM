<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class UsersSMSConfigurationTable extends Migration
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
            'phone'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
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

        $this->forge->createTable('user_sms_configuration', TRUE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('user_sms_configuration', TRUE);
    }
}
