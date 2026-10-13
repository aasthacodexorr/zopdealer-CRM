<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymenReferenceForExpense extends Migration
{
    public function up()
    {

        $fields = [
            'payment_reference'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'        => '',
            ],

        ];
        $this->forge->addColumn('inventory_costs', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('inventory_costs', [ 'payment_reference']); 
    }
}
