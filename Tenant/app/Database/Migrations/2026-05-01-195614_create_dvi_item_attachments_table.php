<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDVIItemAttachmentsTable extends Migration
{

    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'url'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'        => ''
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
            'dvi_item_id' => [
                'type'           => 'INT',
                'unsigned'       => TRUE,
            ],
        ]);

        $this->forge->addKey('id', TRUE);
        $this->forge->addForeignKey('dvi_item_id', 'digital_vehicle_inspection_items', 'id', 'CASCADE', 'CASCADE');

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('digital_vehicle_inspection_item_attachments', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('digital_vehicle_inspection_item_attachments');
    }
}
