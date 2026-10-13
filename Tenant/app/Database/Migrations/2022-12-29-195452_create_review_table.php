<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReviewsForTheClient extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'uuid'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
            ],
            'profile_photo_url'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'       => ''
            ],
            'name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
            ],
            'comment'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'       => ''
            ],
            'reply'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'       => ''
            ],
            'rating'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
            ],
            'review_date_time'       => [
                'type'           => 'DATE',
                'null'           => TRUE,
            ],
            'review_reply_date_time'       => [
                'type'           => 'DATE',
                'null'           => TRUE,
            ],
            'source'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
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
        $this->forge->createTable('reviews', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('reviews');
    }
}
