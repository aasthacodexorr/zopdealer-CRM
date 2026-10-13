<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVendorsForTheClient extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'company_name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'first_name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'last_name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
            ],
            'rin'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
            ],
            'tax_number'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'   => TRUE,
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
            'phonetic_string'          => [
                'type'   => 'TEXT',
                'null'   => TRUE,
            ],
        ]);

        $this->forge->addKey('id', TRUE);
        $this->forge->addUniqueKey("company_name");

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('vendors', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('vendors');
    }
}
