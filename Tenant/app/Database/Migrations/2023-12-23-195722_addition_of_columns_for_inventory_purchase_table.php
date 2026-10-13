<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AdditionalInformationAboutInventoryAboutPurchase extends Migration
{
    public function up()
    {

        $fields = [
            'has_been_paid_for'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'has_title_been_received'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'has_title_been_transferred'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'payment_date'       => [
                'type'           => 'DATE',
                'null'           => TRUE,
                'default'       => null
            ],
            'title_transfer_date'       => [
                'type'           => 'DATE',
                'null'           => TRUE,
                'default'       => null
            ],
            'title_received_date'       => [
                'type'           => 'DATE',
                'null'           => TRUE,
                'default'       => null
            ],
        ];

        $this->forge->addColumn('inventory_purchase', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('inventory_purchase', ['has_been_paid_for']);
        $this->forge->dropColumn('inventory_purchase', ['payment_date']);
        $this->forge->dropColumn('inventory_purchase', ['has_title_been_received']);
        $this->forge->dropColumn('inventory_purchase', ['has_title_been_transferred']);
        $this->forge->dropColumn('inventory_purchase', ['title_transfer_date']);
        $this->forge->dropColumn('inventory_purchase', ['title_received_date']);
    }
}
