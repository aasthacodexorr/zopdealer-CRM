<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddReportStatusToTheDealTable extends Migration
{
    public function up()
    {

        $fields = [
            'report_status'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => TRUE,
                'default'        => 'TO BE ADDED'
            ],
        ];
        $this->forge->addColumn('deals', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('deals', [
            'report_status'
        ]);
    }
}
