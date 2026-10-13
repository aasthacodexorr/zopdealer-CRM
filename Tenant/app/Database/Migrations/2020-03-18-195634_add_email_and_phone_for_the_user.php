<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhoneAndEmailForTheUser extends Migration
{
    public function up()
    {
        $fields = [
            'phone'	=>[
				'type'           => 'VARCHAR',
				'constraint'     => '50',
                'null'           => TRUE,
                'default'        => '',
            ],
            'email'	=>[
				'type'           => 'VARCHAR',
				'constraint'     => '255',
                'null'           => TRUE,
                'default'        => '',
			],
        ];

        $this->forge->addColumn('user_metadata', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('user_metadata', [ 'phone']); 
        $this->forge->dropColumn('user_metadata', [ 'email']);// to drop one single column
    }
}
