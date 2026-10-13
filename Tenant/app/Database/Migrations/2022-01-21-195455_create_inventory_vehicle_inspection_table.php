<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddInventoryVehicleInspectionSheetForTheClient extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'inventory_id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
            ],
            'detail'          => [
                'type'   => 'TEXT',
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
        ]);

        $this->forge->addKey('id', TRUE);
        $this->forge->addUniqueKey(['inventory_id']);
        $this->forge->addForeignKey('inventory_id','inventory','inventory_id','CASCADE','CASCADE');
        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('inventory_vehicle_inspection', FALSE, $attributes);

    }

    public function down()
    {
        $this->forge->dropTable('inventory_vehicle_inspection');
    }

}