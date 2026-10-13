<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDealViewForTheUser extends Migration
{
    public function up()
    {
        $fields = [
            'deal_view'	=>[
				'type'           => 'INT',
				'default'		 => 1
			],
        ];

        $this->forge->addColumn('user_metadata', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('user_metadata', [ 'deal_view']); // to drop one single column
    }
}
