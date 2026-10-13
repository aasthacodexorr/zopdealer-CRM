<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIndividualCreditApplication extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'customer_id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE
            ],
            'salutation'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'gender'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'marital_status'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'residence_ownership'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'market_value'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'        => 0
            ],
            'mortgage_amount'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'        => 0
            ],
            'monthly_payment'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'        => 0
            ],
            'mortgage_bank'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'years_at_present_address'       => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
                'null'           => TRUE,
            ],
            'property_owner'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'bankruptcy_declared'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'bankruptcy_information'       => [
                'type'           => 'TEXT',
                'null'   => TRUE,
                'default'   => '',
            ],
            'previous_write_off'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'previous_write_off_information'       => [
                'type'           => 'TEXT',
                'null'   => TRUE,
                'default'   => '',
            ],
            'financial_institution'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'desired_monthly_payment'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'        => 0
            ],
            'employment_type'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'job_title'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'occupation'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'employer_name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'employer_work_phone'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
                'default'   => '',
            ],
            'employer_work_phone_ext'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
                'default'   => '',
            ],
            'employment_period_years'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
                'default'   => '',
            ],
            'employment_period_months'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
                'default'   => '',
            ],
            'employer_address'       => [
                'type'           => 'TEXT',
                'null'   => TRUE,
                'default'   => '',
            ],
            'employer_city'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'employer_province'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'employer_postal_code'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'employer_country'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'rate_per_hour'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'hours_per_week'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'null'           => TRUE,
                'default'       => 0
            ],
            'monthly_gross'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'annual_gross'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'previous_employment_type'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'previous_job_title'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'previous_occupation'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'previous_employer_name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'previous_employer_work_phone'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
                'default'   => '',
            ],
            'previous_employer_work_phone_ext'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
                'default'   => '',
            ],
            'previous_employment_period_years'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
                'default'   => '',
            ],
            'previous_employment_period_months'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
                'default'   => '',
            ],
            'previous_employer_address'       => [
                'type'           => 'TEXT',
                'null'   => TRUE,
                'default'   => '',
            ],
            'previous_employer_city'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'previous_employer_province'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'previous_employer_postal_code'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'previous_employer_country'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'previous_rate_per_hour'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'previous_hours_per_week'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'null'           => TRUE,
                'default'       => 0
            ],
            'previous_monthly_gross'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'previous_annual_gross'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'other_income_source'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'other_rate_per_hour'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'other_hours_per_week'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'null'           => TRUE,
                'default'       => 0
            ],
            'other_monthly_gross'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'other_annual_gross'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],

            'created_at'       => [
                'type'           => 'DATETIME',
                'null'           => TRUE,
            ],
            'updated_at'       => [
                'type'           => 'DATETIME',
                'null'           => TRUE,
            ],
            'deleted_at'       => [
                'type'           => 'DATETIME',
                'null'           => TRUE,
            ],  
        ]);

        $this->forge->addKey('id', TRUE);
        $this->forge->addUniqueKey("customer_id");
        $this->forge->addForeignKey('customer_id','customers','id','CASCADE','CASCADE');

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('customer_individual_credit_application', FALSE, $attributes);

    }

    public function down()
    {
        $this->forge->dropTable('customer_individual_credit_application');   
    }
}