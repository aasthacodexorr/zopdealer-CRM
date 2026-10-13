<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;
use DateTime;

class ThreeDSpinnerVideoSchedule extends Migration
{
    public function up()
    {
        $fields = [
            'vehicle_three_d_spinner_video_schedule'       => [
                'type'           => 'DATETIME',
                'default'   => (new DateTime())->format('Y-m-d H:i:s'),
                'null'           => TRUE,
            ],
        ];

        $this->forge->addColumn('clients', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('clients', [ 'vehicle_three_d_spinner_video_schedule']); // to drop one single column
    }
}
