<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNoOfKeysColumnForTheInventoryPurchase extends Migration
{
    public function up()
    {
        $fields = [
            'key_count'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'default'        => '',
                'null'           => TRUE,
            ],

        ];

        $this->forge->addColumn('inventory_purchase', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('inventory_purchase', 'key_count');
    }
}
