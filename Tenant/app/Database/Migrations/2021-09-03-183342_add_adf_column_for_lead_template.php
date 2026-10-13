<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnForLeadRecipientAdfEmail extends Migration
{
    public function up()
    {
        $fields = [
            'recipient_adf_email'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '500',
                'null'           => TRUE,
                'default'        => ''      
            ]
        ];

        $this->forge->addColumn('lead_template_forms', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('lead_template_forms', [ 'recipient_adf_email']); // to drop one single column
    }
}
