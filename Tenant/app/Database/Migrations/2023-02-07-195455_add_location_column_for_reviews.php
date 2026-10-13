<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterReviewTableToIncludeLocationColumn extends Migration
{
    public function up()
    {

        $fields = [
            'location'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => true,
                'default'        => ''
            ],
        ];
        $this->forge->addColumn('reviews', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('reviews', ['location']);
    }
}
