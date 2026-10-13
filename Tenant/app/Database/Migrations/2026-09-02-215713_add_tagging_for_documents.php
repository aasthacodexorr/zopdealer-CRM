<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTaggingForDealJacketDocument extends Migration
{
    public function up()
    {

        $fields = [
            'has_tagging' => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            'tagging'          => [
                'type'   => 'TEXT',
                'null'   => TRUE,
                'default'   => ''
            ]
        ];
        $this->forge->addColumn('deal_jacket_documents', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('deal_jacket_documents', [
            'has_tagging',
            'tagging'
        ]);
    }
}
