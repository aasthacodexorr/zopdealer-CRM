<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLockColumnToTheDealTable extends Migration
{
    public function up()
    {
        $fields = [
            'lock'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],

            ];

        $this->forge->addColumn('deals', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('deals','lock');
    }
}