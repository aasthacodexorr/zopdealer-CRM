<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsConvertFlagForLead extends Migration
{
    public function up()
    {
        $fields = [
            'is_converted'       => [
                'type'           => 'INT',
                'constraint'     => '11',
                'default'        => 0
            ],
        ];

        $this->forge->addColumn('lead_responses', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('lead_responses', 'is_converted');
    }
}
