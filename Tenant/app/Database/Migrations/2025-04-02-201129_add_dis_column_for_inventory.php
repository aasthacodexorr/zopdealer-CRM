<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDISForInventory extends Migration
{
    public function up()
    {
        $fields = [
            'days_in_stock'       => [
                'type'           => 'INT',
                'constraint'     => '11',
                'default'        => 0
            ],
        ];

        $this->forge->addColumn('inventory', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('inventory', 'days_in_stock');
    }
}
