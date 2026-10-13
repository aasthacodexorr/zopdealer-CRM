<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdfEmailColumnForClientInSuperTenant extends Migration
{
    public function up()
    {
        $fields = [
            'adf_email'          => [
                'type'           => 'VARCHAR',
				'constraint'     => '255',
                'default'        => ''
            ]

            ];

        $this->forge->addColumn('clients', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('clients','adf_email');
    }
}
