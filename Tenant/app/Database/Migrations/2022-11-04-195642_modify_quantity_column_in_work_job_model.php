<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChangeTheQuantityModelDataTypeInWorkJob extends Migration
{
    public function up()
    {

        $fields = [
            'quantity' => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
            ],

        ];
        $this->forge->modifyColumn('work_job_products', $fields);
    }

    public function down()
    {
    }
}
