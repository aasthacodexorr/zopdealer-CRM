<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEncryptionColumnToCustomerCreditApplicationTable extends Migration
{
    public function up()
    {
        $fields = [
            'is_encrypted'    => [
                'type'           => 'INT',
                'default'         => 0
            ],
        ];

        $this->forge->addColumn('customer_individual_credit_application', $fields);
        $this->forge->addColumn('customer_company_credit_application', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('customer_individual_credit_application', ['is_encrypted']); // to drop one single column
        $this->forge->dropColumn('customer_company_credit_application', ['is_encrypted']); // to drop one single column

    }
}
