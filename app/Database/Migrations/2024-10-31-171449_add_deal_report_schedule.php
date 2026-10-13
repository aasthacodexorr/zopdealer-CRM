<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;
use DateTime;

class AddDealReportScheduleForTheClient extends Migration
{
    public function up()
    {
        $fields = [
            'reset_deal_status_schedule'       => [
                'type'           => 'DATETIME',
                'default'   => (new DateTime())->format('Y-m-d H:i:s'),
                'null'           => TRUE,
            ],
            'update_deal_report_schedule'       => [
                'type'           => 'DATETIME',
                'default'   => (new DateTime())->format('Y-m-d H:i:s'),
                'null'           => TRUE,
            ],
        ];

        $this->forge->addColumn('clients', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('clients', ['reset_deal_status_schedule', 'update_deal_report_status_schedule']); // to drop schedule column
    }
}
