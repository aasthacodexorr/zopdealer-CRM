<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEncryptionColumnToCustomerTable extends Migration
{
    public function up()
    {
        $fields = [
            'is_encrypted'	=>[
				'type'           => 'INT',
				'default'		 => 0
			],
        ];

        $this->forge->addColumn('customers', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('customers', [ 'is_encrypted']); // to drop one single column
    }
}
