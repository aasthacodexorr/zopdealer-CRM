<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDealFinance extends Migration
{

    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'deal_id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE
            ],
            'total_amount'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'        => 0
            ],
            'finance_commission'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'        => 0
            ],
            'override_finance_calculation'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'interest_rate'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'        => 0
            ],
            'interest_rate_type'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'        => '',
            ],
            'term'       => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
                'null'           => TRUE,
                'default'        => 0
            ],
            'payment_frequency'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => TRUE,
                'default'        => '',
            ],
            'interest_amount'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'        => 0
            ],
            'emi_amount'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'        => 0
            ],
            'first_payment_date'       => [
                'type'           => 'DATE',
                'null'           => TRUE,
            ],
            'lien_holder_name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'        => '',
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
        $this->forge->addUniqueKey("deal_id");
        $this->forge->addForeignKey('deal_id', 'deals', 'id', 'CASCADE', 'CASCADE');

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('deal_finance', FALSE, $attributes);
    }


    public function down()
    {
        $this->forge->dropTable('deal_finance');
    }
}
