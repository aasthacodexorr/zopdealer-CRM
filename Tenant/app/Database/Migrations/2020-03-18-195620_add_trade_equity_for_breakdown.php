<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTradeEquityForTheBreakDown extends Migration
{
    public function up()
    {
        $fields = [
            'trade_equity'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'default'        => 0
            ],
        ];

        $this->forge->addColumn('deal_base_breakdown', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('deal_base_breakdown', 'trade_equity'); // to drop one single column
    }
}
