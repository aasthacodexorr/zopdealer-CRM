<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AdditionOfBusinessNameForTheLocation extends Migration
{
    public function up()
    {
        $fields = [
            'business_name'	=>[
				'type'           => 'VARCHAR',
				'constraint'     => '255',
                'null'           => TRUE,
                'default'        => '',
            ]
            
        ];

        $this->forge->addColumn('locations', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('locations', [ 'business_name']); 
    }
}
