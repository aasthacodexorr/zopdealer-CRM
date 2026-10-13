<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLienInformationForInventoryPurchase extends Migration
{
    public function up()
    {
        $fields = [
            'has_lien' => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'lien_amount' => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'        => 0,
            ],
            'lien_holder_name' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'        => '',
            ],
            'lien_has_been_paid' => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'lien_paid_date' => [
                'type'           => 'DATE',
                'null'           => TRUE,
                'default'        => null
            ],
        ];

        $this->forge->addColumn('inventory_purchase', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('inventory_purchase', [
            'has_lien',
            'lien_amount',
            'lien_holder_name',
            'lien_has_been_paid',
            'lien_paid_date'
        ]);
    }
}
