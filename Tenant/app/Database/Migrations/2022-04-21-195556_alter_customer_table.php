<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterCustomerTableToAddAdditionalInformation extends Migration
{
    public function up()
    {

        $fields = [
            'additional_information'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
                'default'       => ''
            ]
        ];
        $this->forge->addColumn('customers', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('customers', ['additional_information']);
    }
}
