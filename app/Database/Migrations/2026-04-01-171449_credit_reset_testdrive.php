<?php

namespace Cardora\app\Database\Migrations;

use CodeIgniter\Database\Migration;
use DateTime;

class MonthlyCreditResetScheduleForTestDrive extends Migration
{
    public function up()
    {
        $fields = [
            'monthly_credit_reset_testdrive'       => [
                'type'           => 'DATETIME',
                'default'   => (new DateTime())->format('Y-m-d H:i:s'),
                'null'           => TRUE,
            ],
        ];

        $this->forge->addColumn('clients', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('clients', ['monthly_credit_reset_testdrive']); // to drop one single column
    }
}
