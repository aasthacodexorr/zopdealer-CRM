<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnForSubTotalAndSortForWorkJobTable extends Migration
{
    public function up()
    {
        $fields = [
            'sub_total'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'cost_sub_total'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'sort_rank'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'default' => null
            ]
        ];

        $this->forge->addColumn('work_job_products', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('work_job_products', ['sub_total', 'cost_sub_total', 'sort_rank']); // to drop one single column
    }
}
