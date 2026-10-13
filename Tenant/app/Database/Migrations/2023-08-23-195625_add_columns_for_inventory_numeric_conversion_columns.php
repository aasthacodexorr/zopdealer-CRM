<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnsForNumericConversionForTheInventoryTable extends Migration
{
    public function up()
    {

        $fields = [
            'odometer_numeric'       => [
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
            'odometer_numeric'
        ]);
    }
}
