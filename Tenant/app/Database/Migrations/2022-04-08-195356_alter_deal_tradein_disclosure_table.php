<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterDealTradeInDisclosureTable extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('deal_trade_in_disclosures', ['answer_flag']);

        $fields = [
            'type'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => TRUE,
            ],
            'options'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '500',
                'null'           => TRUE,
            ],
            'has_additional_information'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'additional_information'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'       => ''
            ],
            'additional_information_placeholder'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '300',
                'null'           => TRUE,
                'default'       => ''
            ],
        ];
        $this->forge->addColumn('deal_trade_in_disclosures', $fields);
    }

    public function down()
    {
        $fields = [
            'answer_flag'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ]
        ];
        $this->forge->addColumn('deal_trade_in_disclosures', $fields);
        $this->forge->dropColumn('deal_trade_in_disclosures', ['type', 'options', 'has_additional_information', 'additional_information', 'additional_information_placeholder']);
    }
}
