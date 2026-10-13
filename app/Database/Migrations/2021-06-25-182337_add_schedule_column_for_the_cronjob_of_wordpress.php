<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddScheduleColumnForWordpress extends Migration
{
    protected $DBGroup = 'default';

    public function up()
    {
        $fields = [
            'schedule'       => [
                'type'           => 'DATETIME',
                'null'           => TRUE,
            ]
        ];

        $this->forge->addColumn('wordpress_jobs', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('wordpress_jobs', 'name');
    }
}
