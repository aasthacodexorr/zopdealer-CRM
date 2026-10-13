<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;
use DateTime;

class DownloadZopStudioScheduleForTheClient extends Migration
{
    public function up()
    {
        $fields = [
            'download_zop_studio_processed_image_schedule'       => [
                'type'           => 'DATETIME',
                'default'   => (new DateTime())->format('Y-m-d H:i:s'),
                'null'           => TRUE,
            ],
        ];

        $this->forge->addColumn('clients', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('clients', ['download_zop_studio_processed_image_schedule']); // to drop one single column
    }
}
