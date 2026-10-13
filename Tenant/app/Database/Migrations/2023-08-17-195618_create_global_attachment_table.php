<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGlobalAttachmentTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'module'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'sub_module'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'entity_id'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'url'       => [
                'type'           => 'TEXT',
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
        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('attachments', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('attachments');
    }
}
