<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;
use DateTime;

class DownloadThirdPartyImageScheduleForTheClient extends Migration
{
    public function up()
    {
        $fields = [
            'download_third_party_image_schedule'       => [
                'type'           => 'DATETIME',
                'default'   => (new DateTime())->format('Y-m-d H:i:s'),
                'null'           => TRUE,
            ],
        ];

        $this->forge->addColumn('clients', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('clients', [ 'download_third_party_image_schedule']); // to drop one single column
    }
}
