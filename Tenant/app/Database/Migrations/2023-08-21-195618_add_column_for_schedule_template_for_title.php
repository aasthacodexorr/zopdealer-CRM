<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTitleForScheduleTemplate extends Migration
{
    public function up()
    {
        $fields = [
            'title'       => [
                'type'           => 'TEXT',
                'default'        => '',
                'null'           => TRUE,
            ],

        ];

        $this->forge->addColumn('schedule_templates', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('schedule_templates', 'title');
    }
}
