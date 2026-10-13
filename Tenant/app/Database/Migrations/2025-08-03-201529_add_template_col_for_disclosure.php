<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDisclosureTemplateColumn extends Migration
{
    public function up()
    {

        $fields = [
            'prefilled_templates'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'       => ''
            ],
        ];
        $this->forge->addColumn('inventory_disclosures', $fields);
        $this->forge->addColumn('deal_trade_in_disclosures', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('inventory_disclosures', ['prefilled_templates']);
        $this->forge->dropColumn('deal_trade_in_disclosures', ['prefilled_templates']);
    }
}
