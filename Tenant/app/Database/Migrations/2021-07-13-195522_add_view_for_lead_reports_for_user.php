<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLeadReportViewToTheUser extends Migration
{
    public function up()
    {
        $fields = [
            'lead_report_view'	=>[
				'type'           => 'INT',
				'default'		 => 3
			],
        ];

        $this->forge->addColumn('user_metadata', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('user_metadata', [ 'lead_report_view']); // to drop one single column
    }
}
