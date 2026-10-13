<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnsForDigitalRetailingInWarrantySettingsTable extends Migration
{
    public function up()
    {

        $fields = [
            'is_mandatory'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'is_variable'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
        ];
        $this->forge->addColumn('warranty_settings', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('warranty_settings', [
            'is_mandatory', 'is_variable'
        ]);
    }
}
