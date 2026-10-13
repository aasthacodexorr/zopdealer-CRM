<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSectionTypeForDealJacketDocument extends Migration
{
    public function up()
    {

        $fields = [
            'section_type'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'        => '',
            ],
        ];
        $this->forge->addColumn('deal_jacket_documents', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('deal_jacket_documents', [
            'section_type',
        ]);
    }
}
