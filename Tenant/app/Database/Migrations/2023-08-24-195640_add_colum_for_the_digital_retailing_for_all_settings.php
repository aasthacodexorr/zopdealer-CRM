<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnsForDigitalRetailingInAllSettingsTableForFinance extends Migration
{
    public function up()
    {

        $fields = [
            'only_finance'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ]
        ];
        $this->forge->addColumn('guarantee_settings', $fields);
        $this->forge->addColumn('warranty_settings', $fields);
        $this->forge->addColumn('insurance_settings', $fields);
        $this->forge->addColumn('fee_settings', $fields);

    }

    public function down()
    {
        $this->forge->dropColumn('guarantee_settings', [
            'only_finance'
        ]);
        $this->forge->dropColumn('warranty_settings', [
            'only_finance'
        ]);
        $this->forge->dropColumn('insurance_settings', [
            'only_finance'
        ]);
        $this->forge->dropColumn('fee_settings', [
            'only_finance'
        ]);
    }
}
