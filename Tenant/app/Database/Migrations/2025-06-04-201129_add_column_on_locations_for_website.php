<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnForWebsiteOnLocationsTable extends Migration
{
    public function up()
    {
        $fields = [
            'show_on_website'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'address_bar'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'        => ''
            ],
            'address_map_url'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'        => ''
            ]
        ];

        $this->forge->addColumn('locations', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('locations', ['show_on_website', 'address_bar', 'address_map_url']); // to drop one single column
    }
}
