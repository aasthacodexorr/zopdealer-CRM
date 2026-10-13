<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentReportViewToTheUser extends Migration
{
    public function up()
    {
        $fields = [
            'payment_report_view'	=>[
				'type'           => 'INT',
				'default'		 => 1
			],
        ];

        $this->forge->addColumn('user_metadata', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('user_metadata', [ 'payment_report_view']); // to drop one single column
    }
}
