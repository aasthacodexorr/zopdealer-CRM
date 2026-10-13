<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLocationColumnToCraigslist extends Migration
{
    public function up()
    {
        $fields = [
            'location'	=>[
				'type'           => 'VARCHAR',
				'constraint'     => '255',
                'null'           => TRUE,
                'default'        => '',
            ]
            
        ];

        $this->forge->addColumn('craigslist', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('craigslist', [ 'location']); 
    }
}
