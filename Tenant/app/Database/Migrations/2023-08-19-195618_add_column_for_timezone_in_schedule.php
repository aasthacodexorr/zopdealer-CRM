<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTimeZoneColumForSchedule extends Migration
{
    public function up()
    {
        $fields = [
            'timezone'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
            ],

        ];

        $this->forge->addColumn('schedule_templates', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('schedule_templates', 'timezone');
    }
}
