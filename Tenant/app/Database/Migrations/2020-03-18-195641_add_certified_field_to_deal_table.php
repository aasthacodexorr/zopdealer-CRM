<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCertifiedForTheDeal extends Migration
{
    public function up()
    {
        $fields = [
            'certified'	=>[
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
        $this->forge->dropColumn('deals', [ 'certified']); 
    }
}
