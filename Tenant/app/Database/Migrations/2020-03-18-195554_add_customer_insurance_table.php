<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCustomerInsurance extends Migration
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
            'company_name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'agent_name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'policy_number'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'policy_expiry_date'       => [
                'type'           => 'DATE',
                'null'           => TRUE,
            ],
            'phone'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
            ],
            'phone_ext'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
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
        $this->forge->addForeignKey('customer_id', 'customers', 'id', 'CASCADE', 'CASCADE');

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('customer_insurance', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('customer_insurance');
    }
}
