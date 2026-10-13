<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterReviewTableToIncludeFeaturedColumn extends Migration
{
    public function up()
    {

        $fields = [
            'featured'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ]
        ];
        $this->forge->addColumn('reviews', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('reviews', ['featured']);
    }
}

