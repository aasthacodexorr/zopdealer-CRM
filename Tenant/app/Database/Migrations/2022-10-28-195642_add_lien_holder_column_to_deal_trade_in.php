<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLienHolderColumnInDealTradeInTable extends Migration
{
    public function up()
    {
        $fields = [
            'lien_holder'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
        ];

        $this->forge->addColumn('deal_trade_in', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('deal_trade_in', ['lien_holder']); // to drop one single column
    }
}
