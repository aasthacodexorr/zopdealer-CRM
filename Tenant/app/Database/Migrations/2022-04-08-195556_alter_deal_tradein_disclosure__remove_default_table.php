<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterDealTradeInDefaultDisclosureColumnInTable extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('deal_trade_in_disclosures', ['default']);
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
        $this->forge->addColumn('deal_trade_in_disclosures', $fields);
    }
}
