<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLocationsForTheClient extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'street'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'city'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'state'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'zipcode'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'country'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'phone'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'           => TRUE,
            ],
            'email'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
            ],
            'status'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 1,
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
        $this->forge->addUniqueKey("name");
        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('locations', FALSE, $attributes);

    }

    public function down()
    {
        $this->forge->dropTable('locations');
    }

}