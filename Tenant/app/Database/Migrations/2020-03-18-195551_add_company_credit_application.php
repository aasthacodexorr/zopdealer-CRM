<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCompanyCreditApplication extends Migration
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
            'years_at_present_address'       => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
                'null'           => TRUE,
            ],
            'business_type'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'number_of_employees'       => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
                'null'           => TRUE,
            ],
            'years_in_business'       => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
                'null'           => TRUE,
            ],
            'accountant_firm'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'accountant_personal'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'accountant_phone'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
                'default'   => '',
            ],
            'accountant_phone_ext'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
                'default'   => '',
            ],
            'principal_owner'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'officer_name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'officer_position'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'ownership_percentage'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
            ],
            'officer_address'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'officer_phone'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
                'default'   => '',
            ],
            'officer_phone_ext'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
                'default'   => '',
            ],
            'bank_name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
                'default'   => '',
            ],
            'bank_number'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
                'default'   => '',
            ],
            'bank_account_number'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
                'default'   => '',
            ],
            'bank_account_type'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
                'default'   => '',
            ],
            'bank_branch_number'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
                'default'   => '',
            ],
            'bank_contact_personal'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
                'default'   => '',
            ],
            'bank_phone'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
                'default'   => '',
            ],
            'bank_phone_ext'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
                'default'   => '',
            ],
            'bank_street_address'       => [
                'type'           => 'TEXT',
                'null'   => TRUE,
                'default'   => '',
            ],
            'bank_apt'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
                'default'   => '',
            ],
            'bank_city'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
                'default'   => '',
            ],
            'bank_province'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
                'default'   => '',
            ],
            'bank_postal_code'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
                'default'   => '',
            ],
            'bank_country'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
                'default'   => '',
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
        $this->forge->createTable('customer_company_credit_application', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('customer_company_credit_application');
    
    }
}
