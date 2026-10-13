<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMarketValuationTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'year'       => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
                'null'           => TRUE,
            ],
            'make'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => TRUE,
                'default'        => ''
            ],
            'model'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => TRUE,
                'default'        => ''
            ],
            'trim'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'        => ''
            ],
            'vin'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'        => ''
            ],
            'status'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'payload'       => [
                'type'   => 'TEXT',
                'null'   => TRUE,
                'default'   => ''
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
                'default'   => ''
            ]
        ]);

        $this->forge->addKey('id', TRUE);

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('market_valuations', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('market_valuations', TRUE);
    }
}
