<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnForDVI extends Migration
{
    protected $DBGroup = 'default';

    public function up()
    {
        $fields = [
            'issues_count'       => [
                'type'           => 'INT',
                'default'        => 0
            ],
        ];

        $this->forge->addColumn('digital_vehicle_inspections', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('digital_vehicle_inspections', 'issues_count');
    }
}
