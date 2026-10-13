<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCustomerTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'first_name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'       => '',
            ],
            'last_name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
            ],
            'rin_or_dl_flag'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'driver_licence'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
            ],
            'rin'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
            ],
            'customer_type'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'driver_licence_expiry_date'       => [
                'type'           => 'DATE',
                'null'           => TRUE,
            ],
            'date_of_birth'       => [
                'type'           => 'DATE',
                'null'           => TRUE,
            ],
            'company_name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'       => '',
            ],
            'mvda'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'default'       => '',
            ],
            'year_end'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'default'       => '',
            ],
            'tax_number'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'       => '',
            ],
            'sales_person_registration'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'       => '',
            ],


            'street_address'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
            ],
            'apt'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
            ],
            'city'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'   => TRUE,
            ],
            'province'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'   => TRUE,
            ],
            'postal_code'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'   => TRUE,
            ],
            'country'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'   => TRUE,
            ],
            'phone'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
            ],
            'fax'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
            ],
            'mobile'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
            ],
            'email'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
            ],

            'phonetic_string'          => [
                'type'   => 'TEXT',
                'null'   => TRUE,
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

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];


        $this->forge->createTable('customers', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('customers');
    }
}
