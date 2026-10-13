<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterInventoryDefaultDisclosureColumnInTable extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('inventory_disclosures', ['default']);
    }

    public function down()
    {
        $fields = [
            'default'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ]
        ];
        $this->forge->addColumn('inventory_disclosures', $fields);
    }
}
