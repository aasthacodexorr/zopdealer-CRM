<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTestDrivesCustomerViewColumnToUserMetadata extends Migration
{
    public function up()
    {
        $fields = [
            'test_drives_customers_view' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
                'null'       => true,
            ],
        ];

        $this->forge->addColumn('user_metadata', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('user_metadata', 'test_drives_customers_view');
    }
}
