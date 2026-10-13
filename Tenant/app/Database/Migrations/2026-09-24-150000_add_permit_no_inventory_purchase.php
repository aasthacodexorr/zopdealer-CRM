<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPermitNoInventoryPurchase extends Migration
{
    public function up()
    {
        $fields = [
            'permit_number' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'        => '',
            ],
        ];

        $this->forge->addColumn('inventory_purchase', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('inventory_purchase', ['permit_number']);
    }
}
