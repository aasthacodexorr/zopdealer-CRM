<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterUnsignedAttributesForDealReport extends Migration
{
    public function up()
    {
        $fields = [
            'fees'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'        => 0
            ],
            'fees_primary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'fees_secondary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'fees_total_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
        ];

        $this->forge->modifyColumn('deal_reports', $fields);
    }

    public function down()
    {
        $fields = [
            'fees'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'        => 0
            ],
            'fees_primary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'fees_secondary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
            'fees_total_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
        ];

        $this->forge->modifyColumn('deal_reports', $fields);
    }
}
