<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFieldsToIndividualCreditApplicationForm extends Migration
{
    public function up()
    {
        $fields = [
            'previous_address'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'previous_address_city'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'           => TRUE,
                'default'   => '',
            ],
            'previous_address_province'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'           => TRUE,
                'default'   => '',
            ],
            'previous_address_postal_code'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'           => TRUE,
                'default'   => '',
            ],
            'previous_residence_address_period_years'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => TRUE,
                'default'   => '',
            ],
            'previous_residence_period_months'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => TRUE,
                'default'   => '',
            ],
            'previous_residence_monthly_payment'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'        => 0
            ],
            
        ];

        $this->forge->addColumn('customer_individual_credit_application', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('customer_individual_credit_application', [ 'previous_address']); 
        $this->forge->dropColumn('customer_individual_credit_application', [ 'previous_address_city']); 
        $this->forge->dropColumn('customer_individual_credit_application', [ 'previous_address_province']); 
        $this->forge->dropColumn('customer_individual_credit_application', [ 'previous_address_postal_code']); 
        $this->forge->dropColumn('customer_individual_credit_application', [ 'previous_residence_address_period_years']); 
        $this->forge->dropColumn('customer_individual_credit_application', [ 'previous_residence_period_months']); 
        $this->forge->dropColumn('customer_individual_credit_application', [ 'previous_residence_monthly_payment']); 
    }
}
