<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddConfigurationForClient extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'code_scope'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'label'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
            ],
            'key'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'value'          => [
                'type'   => 'TEXT',
            ],
            'type'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'active'       => [
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
        $this->forge->addUniqueKey("key");
        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('configurations', FALSE, $attributes);

    }

    public function down()
    {
        $this->forge->dropTable('configurations');
    }

}