<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AdditionOfSecondaryTaxesToExpenses extends Migration
{
    public function up()
    {

        $fields = [
            'tax_type_secondary'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'        => '',
            ],
            'tax_percentage_secondary'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'tax_amount_secondary'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'tax_amount_total'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],

        ];
        $this->forge->addColumn('inventory_costs', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('inventory_costs', [ 'tax_type_secondary']); 
        $this->forge->dropColumn('inventory_costs', [ 'tax_percentage_secondary']); 
        $this->forge->dropColumn('inventory_costs', [ 'tax_amount_secondary']); 
        $this->forge->dropColumn('inventory_costs', [ 'tax_amount_total']); 
    }
}
