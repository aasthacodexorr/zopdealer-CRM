<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCustomLessColumnForTheLeadTemplate extends Migration
{

    public function up()
    {
        $fields = [
            'custom_less'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'       => ''
            ],
        ];

        $this->forge->addColumn('lead_template_forms', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('lead_template_forms', 'custom_less');
    }
}
