<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDefaultCostSetting extends Migration
{
    public function up()
    {

        $fields = [
            'include_cost_by_default'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
        ];
        $this->forge->addColumn('cost_settings', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('cost_settings', [
            'include_cost_by_default',
        ]);
    }
}
