<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;
use DateTime;

class ThirdPartyAPIBasedImportScheduleForClient extends Migration
{
    public function up()
    {
        $fields = [
            'third_party_api_based_inventory_import_schedule'       => [
                'type'           => 'DATETIME',
                'default'   => (new DateTime())->format('Y-m-d H:i:s'),
                'null'           => TRUE,
            ],
        ];

        $this->forge->addColumn('clients', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('clients', ['third_party_api_based_inventory_import_schedule']); // to drop one single column
    }
}
