<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnForTheSINInIndividualCreditApplicationTable extends Migration
{
    public function up()
    {
        $fields = [
            'sin'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
        ];

        $this->forge->addColumn('customer_individual_credit_application', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('customer_individual_credit_application', ['sin']); // to drop one single column
    }
}
