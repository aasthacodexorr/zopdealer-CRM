<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnToCustomerTableForTheOtherDataInJSON extends Migration
{
    public function up()
    {

        $fields = [
            'other_data'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'       => ''
            ]
        ];
        $this->forge->addColumn('customers', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('customers', ['other_data']);
    }
}
