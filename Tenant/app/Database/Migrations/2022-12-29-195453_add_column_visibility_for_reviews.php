<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterReviewTableToIncludeVisibilityColumn extends Migration
{
    public function up()
    {

        $fields = [
            'visibility'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 1,
            ]
        ];
        $this->forge->addColumn('reviews', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('reviews', ['visibility']);
    }
}