<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTimeZoneForTheTenantUser extends Migration
{
    public function up()
    {
        $fields = [
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
        $this->forge->dropColumn('user_metadata','timezone');
    }
}
