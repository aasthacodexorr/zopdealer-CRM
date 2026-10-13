<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDVIItemTable extends Migration
{

    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'title'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '500',
                'default'       => ''
            ],
            'enable_comment'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'enable_attachments'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'type'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'       => ''
            ],
            'description'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'        => ''
            ],
            'comment'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'        => ''
            ],
            'status'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => TRUE,
            ],
            'reason'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'        => ''
            ],
            'has_response'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'render_only'       => [
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
            'dvi_id' => [
                'type'           => 'INT',
                'unsigned'       => TRUE,
            ],
        ]);

        $this->forge->addKey('id', TRUE);
        $this->forge->addForeignKey('dvi_id','digital_vehicle_inspections','id','CASCADE','CASCADE');

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('digital_vehicle_inspection_items', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('digital_vehicle_inspection_items', TRUE);
    }
}
