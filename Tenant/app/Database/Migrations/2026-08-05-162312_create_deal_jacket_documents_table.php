<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDealJacketDocumentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'deal_id'          => [
                'type'           => 'INT',
                'unsigned'       => TRUE,
                'null'           => TRUE,
            ],
            'url'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
            ],
            'type'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'        => '',
            ],
            'user'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'        => '',
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
        $this->forge->addForeignKey('deal_id', 'deals', 'id', 'CASCADE', 'CASCADE');

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('deal_jacket_documents', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('deal_jacket_documents');
    }
}
