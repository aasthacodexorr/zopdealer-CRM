<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterInventoryPurchaseTableToAddAdditionalInformationForCompany extends Migration
{
    public function up()
    {

        $fields = [
            'mvda'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'default'       => '',
            ],

            'sales_person_registration'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'       => '',
            ],
        ];
        $this->forge->addColumn('inventory_purchase', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('inventory_purchase', ['mvda','sales_person_registration']);
    }
}
