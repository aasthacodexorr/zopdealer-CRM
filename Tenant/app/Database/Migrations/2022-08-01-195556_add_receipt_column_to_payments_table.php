<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddReceiptNumberFieldInStripePaymentTable extends Migration
{
    public function up()
    {
        $fields = [
            'receipt_number'          => [
                'type'   => 'TEXT',
                'null'   => TRUE,
            ]

            ];

        $this->forge->addColumn('payments', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('payments','receipt_number');
    }
}
