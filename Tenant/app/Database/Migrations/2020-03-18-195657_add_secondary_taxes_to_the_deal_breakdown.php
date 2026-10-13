<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AdditionOfSecondaryTaxesToDealBreakDown extends Migration
{
    public function up()
    {

        $fields = [
            'purchase_tax_type_secondary'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'        => '',
            ],
            'tax_on_purchase_price_secondary'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'purchase_tax_percentage_secondary'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'tax_on_purchase_total'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],

        ];
        $this->forge->addColumn('deal_base_breakdown', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('deal_base_breakdown', [ 'tax_type_secondary']); 
        $this->forge->dropColumn('deal_base_breakdown', [ 'tax_percentage_secondary']); 
        $this->forge->dropColumn('deal_base_breakdown', [ 'tax_amount_secondary']); 
        $this->forge->dropColumn('deal_base_breakdown', [ 'tax_amount_total']); 
    }
}
