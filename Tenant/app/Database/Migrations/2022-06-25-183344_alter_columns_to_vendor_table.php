<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterVendorTableToAddAdditionalInformation extends Migration
{
    public function up()
    {

        $fields = [
            'mvda'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'default'       => '',
            ],

            'sales_person_registration'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'       => '',
            ],
        ];
        $this->forge->addColumn('vendors', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('vendors', ['mvda','sales_person_registration']);
    }
}
