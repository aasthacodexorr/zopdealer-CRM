<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLeadFilesForTheClient extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'lead_response_id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
            ],
            'file_url'          => [
                'type'   => 'TEXT',
            ],
            'header'       => [
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
        $this->forge->addForeignKey('lead_response_id','lead_responses','id', 'CASCADE', 'CASCADE');
        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('lead_responses_files', TRUE, $attributes);

    }

    public function down()
    {
        $this->forge->dropTable('lead_responses_files');
    }

}