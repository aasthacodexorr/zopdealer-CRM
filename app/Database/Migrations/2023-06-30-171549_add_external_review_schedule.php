<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;
use DateTime;

class AddExternalReviewSchedule extends Migration
{
    public function up()
    {
        $fields = [
            'external_review_schedule'       => [
                'type'           => 'DATETIME',
                'default'   => (new DateTime())->format('Y-m-d H:i:s'),
                'null'           => TRUE,
            ],
        ];

        $this->forge->addColumn('clients', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('clients', [ 'external_review_schedule']); // to drop one single column
    }
}
