<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProductViewForTheUser extends Migration
{
    public function up()
    {
        $fields = [
            'product_view'	=>[
				'type'           => 'INT',
				'default'		 => 1
			],
        ];

        $this->forge->addColumn('user_metadata', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('user_metadata', [ 'product_view']); // to drop one single column
    }
}
