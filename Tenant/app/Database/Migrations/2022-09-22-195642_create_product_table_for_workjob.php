<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProductForWorkjobTableForTheClient extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'item_code'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'        => ''
            ],

            'description'       => [
                'type'           => 'TEXT',
                'default'        => '',
                'null'           => TRUE,
            ],

            'category'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'        => ''
            ],
            'type'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'        => ''
            ],

            'vendor_code'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'        => ''
            ],
            'vendor_name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'        => ''
            ],
            'unit'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'        => ''
            ],

            'quantity'          => [
                'type'   => 'INT',
                'unsigned'      => TRUE,
                'default'       => 0,
            ],

            'price'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'discount'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],

            'tax_type'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'        => '',
            ],
            'tax_percentage'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'tax_amount'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],

            'tax_type_secondary'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'        => '',
            ],
            'tax_percentage_secondary'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'tax_amount_secondary'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'tax_amount_total'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],

            'cost'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'        => 0
            ],
            'cost_tax_type'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'        => '',
            ],
            'cost_tax_percentage'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'cost_tax_amount'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'cost_tax_type_secondary'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'        => '',
            ],
            'cost_tax_amount_secondary'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'cost_tax_percentage_secondary'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'cost_tax_amount_total'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],

            'additional_information'       => [
                'type'           => 'TEXT',
                'default'        => '',
                'null'           => TRUE,
            ],
            'job_card_information'       => [
                'type'           => 'TEXT',
                'default'        => '',
                'null'           => TRUE,
            ],
            'warranty'       => [
                'type'           => 'TEXT',
                'default'        => '',
                'null'           => TRUE,
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
            'work_job_id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE
            ]
        ]);

        $this->forge->addKey('id', TRUE);
        $this->forge->addForeignKey('work_job_id', 'work_jobs', 'id', 'CASCADE', 'CASCADE');

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];

        $this->forge->createTable('work_job_products', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('work_job_products');
    }
}
