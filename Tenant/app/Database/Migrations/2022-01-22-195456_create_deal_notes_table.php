<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNoteForTheDeal extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'title'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
            ],
            'description'          => [
                'type'           => 'TEXT',
                'null'           => TRUE,
            ],
            'attachment'          => [
                'type'           => 'TEXT',
                'null'           => TRUE,
            ],
            'deal_id'      =>[
                'type'           => 'INT',
                'unsigned'       => TRUE
            ],
            'user_id'      =>[
                'type'           => 'INT',
                'unsigned'       => TRUE
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
        $this->forge->addForeignKey('deal_id','deals','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('user_id','users','id');

        
        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('deal_notes', FALSE, $attributes);

    }

    public function down()
    {
        $this->forge->dropTable('deal_notes');
    }

}