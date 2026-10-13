<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;
use DateTime;

class AddVoucherExpiryScheduleForTheClient extends Migration
{
    public function up()
    {
        $fields = [
            'voucher_expiry_schedule'       => [
                'type'           => 'DATETIME',
                'default'   => (new DateTime())->format('Y-m-d H:i:s'),
                'null'           => TRUE,
            ],
        ];

        $this->forge->addColumn('clients', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('clients', [ 'voucher_expiry_schedule']); // to drop one single column
    }
}
