<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;
use DateTime;

class AddFBMPStatusUpdateScheduleForClientTable extends Migration
{
    public function up()
    {
        $fields = [
            'fbmp_status_update_schedule'       => [
                'type'           => 'DATETIME',
                'default'   => (new DateTime())->format('Y-m-d H:i:s'),
                'null'           => TRUE,
            ],
        ];

        $this->forge->addColumn('clients', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('clients', [ 'fbmp_status_update_schedule']); // to drop one single column
    }
}
