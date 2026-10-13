<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class KBBDataTable extends Migration
{
    protected $DBGroup = 'default';

    public function up()
    {
        /* Add fields for the table */
        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'year'       => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
            ],
            'make'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '300',
                'null'           => TRUE,
            ],
            'model'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '300',
                'null'           => TRUE,
            ],
            'trim'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '500',
                'null'           => TRUE,
            ],
            'page_url'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '300',
                'null'           => TRUE,
            ],
            'action'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '300',
                'null'           => TRUE,
            ],


        ]);

        $this->forge->addKey('id', TRUE);
        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('kelly_blue_book', TRUE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('kelly_blue_book');
    }
}
