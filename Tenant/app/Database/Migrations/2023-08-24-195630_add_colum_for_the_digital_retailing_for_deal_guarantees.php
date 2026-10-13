<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnsForDigitalRetailingInGuaranteeSettingsTable extends Migration
{
    public function up()
    {

        $fields = [
            'is_mandatory'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ]
        ];
        $this->forge->addColumn('guarantee_settings', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('guarantee_settings', [
            'is_mandatory'
        ]);
    }
}
