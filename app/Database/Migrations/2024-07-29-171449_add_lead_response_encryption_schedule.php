<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;
use DateTime;

class AddLeadResponseEncryptionScheduleForTheClient extends Migration
{
    public function up()
    {
        $fields = [
            'lead_response_encryption_schedule'       => [
                'type'           => 'DATETIME',
                'default'   => (new DateTime())->format('Y-m-d H:i:s'),
                'null'           => TRUE,
            ],
        ];

        $this->forge->addColumn('clients', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('clients', [ 'lead_response_encryption_schedule']); // to drop one single column
    }
}
