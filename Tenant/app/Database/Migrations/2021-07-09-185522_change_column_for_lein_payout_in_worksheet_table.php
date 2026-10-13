<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterLienPayoutColumnNameForDealWorksheet extends Migration
{
    public function up()
    {

        $fields = [
            'lien_fee' => [
                'name' => 'lien_payout',
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0
            ]
        ];
        $this->forge->modifyColumn('deal_base_breakdown', $fields);
    }

    public function down()
    {
        $fields = [
            'lien_payout' => [
                'name' => 'lien_fee',
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0
            ]
        ];
        $this->forge->modifyColumn('deal_base_breakdown', $fields);
    }
}
