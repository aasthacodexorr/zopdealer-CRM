<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class InventoryPurchaseForTheClient extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'purchase_date'       => [
                'type'           => 'DATE',
                'null'           => TRUE,
            ],
            'purchase_from_flag'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'company_name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'first_name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'last_name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
            ],
            'rin'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
            ],
            'tax_number'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'   => TRUE,
            ],
            'street_address'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
            ],
            'apt'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
            ],
            'city'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'   => TRUE,
            ],
            'province'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'   => TRUE,
            ],
            'postal_code'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'   => TRUE,
            ],
            'country'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'   => TRUE,
            ],
            'phone'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
            ],
            'fax'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
            ],
            'mobile'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
            ],
            'email'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
            ],
            'driver_licence'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
            ],
            'plate_number'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
            ],
            'rin_or_dl_flag'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'tax_override_flag'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'price'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'discount'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'tax_type'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'        => '',
            ],
            'tax_percentage'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'tax_amount'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
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
            'inventory_id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
            ],
        ]);

        $this->forge->addKey('id', TRUE);
        $this->forge->addUniqueKey("inventory_id");
        $this->forge->addForeignKey('inventory_id','inventory','inventory_id','CASCADE','CASCADE');

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('inventory_purchase', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('inventory_purchase');
    }
}