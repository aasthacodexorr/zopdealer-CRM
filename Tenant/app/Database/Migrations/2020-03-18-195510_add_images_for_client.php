<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddImagesForTheClient extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'image_url'          => [
                'type'   => 'TEXT',
            ],
            'type'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'index'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'null'           => TRUE,
            ],
            'active'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 1,
            ],
            'tenant_default'       => [
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
        $this->forge->createTable('images', FALSE, $attributes);

    }

    public function down()
    {
        $this->forge->dropTable('images');
    }

}