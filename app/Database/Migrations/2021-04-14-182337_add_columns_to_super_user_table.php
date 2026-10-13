<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFieldsToSuperUserTable extends Migration
{
    protected $DBGroup = 'default';
    
    public function up()
    {
        $fields = [
            'name'	=>[
				'type'           => 'VARCHAR',
				'constraint'     => '255',
				'null'           => TRUE,
			],
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
            'timezone'	=>[
				'type'           => 'VARCHAR',
				'constraint'     => '255',
                'null'           => TRUE,
                'default'        => 'Canada/Eastern',
			],
            


            ];

        $this->forge->addColumn('user_metadata', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('user_metadata','name');
        $this->forge->dropColumn('user_metadata','phone');
        $this->forge->dropColumn('user_metadata','email');
        $this->forge->dropColumn('user_metadata','timezone');
    }
}
