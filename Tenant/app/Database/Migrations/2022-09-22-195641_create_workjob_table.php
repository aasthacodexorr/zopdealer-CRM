<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWorkjobTableForTheClient extends Migration
{
    public function up()
    {

        $this->forge->addField([
            'id' => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'title'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'        => '',
            ],
            'description'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'        => '',
            ],
            'issue'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'        => '',
            ],
            'reason'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'        => '',
            ],
            'solution'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'        => '',
            ],
            'job_card_information' => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'        => '',
            ],
            'search_keyword'          => [
                'type'   => 'TEXT',
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
            'work_order_id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE
            ]
        ]);

        $this->forge->addKey('id', TRUE);
        $this->forge->addForeignKey('work_order_id', 'work_orders', 'id', 'CASCADE', 'CASCADE');

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];

        $this->forge->createTable('work_jobs', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('work_jobs');
    }
}
