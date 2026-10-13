<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOptionForPrivateActivityForTheClient extends Migration
{
    public function up()
    {
        $fields = [
            'is_private'	=>[
				'type'           => 'INT',
				'default'		 => 1
			],
        ];

        $this->forge->addColumn('activities', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('activities', [ 'is_private']); // to drop one single column
    }
}