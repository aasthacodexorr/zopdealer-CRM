<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTestDriveCustomerTable extends Migration
{
    public function up()
    {
        // Test Drives Customers Table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'first_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'last_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'driver_license' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Encrypted base64 driver license data',
            ],
            'license_front' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'license_back' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'license_expiry' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'street_address' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
            ],
            'apt' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'province' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'postal_code' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'country' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'search_keyword'  => [
                'type'   => 'TEXT',
                'null'   => TRUE,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('email');
        $this->forge->addKey('phone');
        $this->forge->addKey(['first_name', 'last_name']);
        $this->forge->createTable('test_drives_customers');
    }

    public function down()
    {
        $this->forge->dropTable('test_drives_customers', true);
    }
}

