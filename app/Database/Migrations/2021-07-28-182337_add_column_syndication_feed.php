<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnSyndicationFeed extends Migration
{
    protected $DBGroup = 'default';

    public function up()
    {
        $fields = [
            'url'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
            ]
        ];

        $this->forge->addColumn('syndication_feed', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('syndication_feed', 'url');
    }
}
