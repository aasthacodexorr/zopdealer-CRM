<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterVendorTableToAddAdditionalInformationColumn extends Migration
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
        $this->forge->addColumn('vendors', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('vendors', ['additional_information']);
    }
}
