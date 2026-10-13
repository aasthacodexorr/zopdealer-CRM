<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDealReportViewToTheUser extends Migration
{
    public function up()
    {
        $fields = [
            'deal_report_view'	=>[
				'type'           => 'INT',
				'default'		 => 4
			],
        ];

        $this->forge->addColumn('user_metadata', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('user_metadata', [ 'deal_report_view']); // to drop one single column
    }
}
