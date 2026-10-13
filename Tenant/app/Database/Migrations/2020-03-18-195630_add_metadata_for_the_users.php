<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTitleAndRegistrationNumberForTheUser extends Migration
{
    public function up()
    {
        $fields = [
            'title'	=>[
				'type'           => 'VARCHAR',
				'constraint'     => '255',
				'null'           => TRUE,
            ],
            'registration_number'	=>[
				'type'           => 'VARCHAR',
				'constraint'     => '255',
				'null'           => TRUE,
			],
        ];

        $this->forge->addColumn('user_metadata', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('user_metadata', [ 'title']); 
        $this->forge->dropColumn('user_metadata', [ 'registration_number']);// to drop one single column
    }
}
