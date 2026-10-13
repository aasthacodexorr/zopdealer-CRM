<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAutoAssignBooleanForTheUser extends Migration
{
    public function up()
    {
        $fields = [
            'lead_auto_assign'	=>[
				'type'           => 'INT',
				'default'		 => 1
			],
        ];

        $this->forge->addColumn('user_metadata', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('user_metadata', [ 'lead_auto_assign']); // to drop one single column
    }
}