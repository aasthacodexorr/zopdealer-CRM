<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCustomerReportViewToTheUser extends Migration
{
    public function up()
    {
        $fields = [
            'customer_report_view'	=>[
				'type'           => 'INT',
				'default'		 => 1
			],
        ];

        $this->forge->addColumn('user_metadata', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('user_metadata', [ 'customer_report_view']); // to drop one single column
    }
}
