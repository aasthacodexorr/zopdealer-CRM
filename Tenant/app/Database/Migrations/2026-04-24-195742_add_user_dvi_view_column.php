<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDVIViewForTheUser extends Migration
{
    public function up()
    {
        $fields = [
            'digital_vehicle_inspection_view'	=>[
				'type'           => 'INT',
				'default'		 => 1
			],
        ];

        $this->forge->addColumn('user_metadata', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('user_metadata', [ 'digital_vehicle_inspection_view']); // to drop one single column
    }
}
