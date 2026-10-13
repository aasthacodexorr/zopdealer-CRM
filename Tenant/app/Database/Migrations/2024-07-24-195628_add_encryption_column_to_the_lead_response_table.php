<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEncryptionColumnToLeadResponseTable extends Migration
{
    public function up()
    {
        $fields = [
            'is_encrypted'	=>[
				'type'           => 'INT',
				'default'		 => 0
			],
        ];

        $this->forge->addColumn('lead_responses', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('lead_responses', [ 'is_encrypted']); // to drop one single column
    }
}
