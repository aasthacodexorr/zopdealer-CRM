<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLeadToTestdrive extends Migration
{
	public function up()
	{
        $fields = [
            'module' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'module_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
        ];


        $this->forge->addColumn('test_drives', $fields);
	}



	public function down()
	{
        $this->forge->dropColumn('test_drives', 'module');
        $this->forge->dropColumn('test_drives', 'module_id');
	}
}
