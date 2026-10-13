<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBrandForTheDeal extends Migration
{
    public function up()
    {
        $fields = [
            'brand'	=>[
				'type'           => 'VARCHAR',
				'constraint'     => '255',
                'null'           => TRUE,
                'default'   => ''
            ],
        ];

        $this->forge->addColumn('deals', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('deals', [ 'brand']); 
    }
}
