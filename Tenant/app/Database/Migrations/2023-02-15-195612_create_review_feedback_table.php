<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReviewFeedbackTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'status'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'       => 'SENT'
            ],
            'response'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'       => ''
            ],
            'sent_counter'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'default'       => 1
            ],
            'comment'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'       => ''
            ],
            'customer_id'      => [
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
            'search_keyword'          => [
                'type'   => 'TEXT',
                'null'   => TRUE,
            ],
        ]);

        $this->forge->addKey('id', TRUE);
        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('review_feedbacks', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('review_feedbacks');
    }
}
