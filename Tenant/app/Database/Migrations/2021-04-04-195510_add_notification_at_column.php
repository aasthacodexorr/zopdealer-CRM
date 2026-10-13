<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AdditionOfTheColumnForNotificationAtInActivities extends Migration
{
    public function up()
    {
        $fields = [
            'notification_at'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
        ];

        $this->forge->addColumn('activities', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('activities', ['notification_at']); // to drop one single column
    }
}
