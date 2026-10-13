<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChangeTestDrivesInventoryIdToVarchar extends Migration
{
	public function up()
	{
        $this->forge->modifyColumn('test_drives', [
            'inventory_id' => [
                'name'       => 'inventory_id',
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false, // Change to false if the column is NOT NULL
            ],
        ]);
    }

	//--------------------------------------------------------------------

	public function down()
	{
        $this->forge->modifyColumn('test_drives', [
            'inventory_id' => [
                'name'       => 'inventory_id',
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false, // Match the original definition
            ],
        ]);
	}
}
