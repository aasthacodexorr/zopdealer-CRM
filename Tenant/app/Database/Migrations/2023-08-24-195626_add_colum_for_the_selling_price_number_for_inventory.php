<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnsForNumericConversionForSellingPriceForTheInventoryTable extends Migration
{
    public function up()
    {

        $fields = [
            'selling_price_numeric'       => [
                'type'           => 'INT',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
        ];
        $this->forge->addColumn('inventory', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('inventory', [
            'selling_price_numeric'
        ]);
    }
}
