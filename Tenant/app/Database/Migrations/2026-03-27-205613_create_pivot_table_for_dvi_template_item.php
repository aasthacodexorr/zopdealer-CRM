<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class DVITemplateItemPivotTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'dvi_template_id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE
            ],
            'dvi_template_item_id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE
            ],
            'sort_rank'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'default' => null
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
        $this->forge->addKey('dvi_template_id');

        $this->forge->addForeignKey('dvi_template_id', 'digital_vehicle_inspection_templates', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('dvi_template_item_id', 'digital_vehicle_inspection_template_items', 'id', 'CASCADE', 'CASCADE');

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];

        $this->forge->createTable('dvi_template_item_pivot_table', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('dvi_template_item_pivot_table');
    }
}
