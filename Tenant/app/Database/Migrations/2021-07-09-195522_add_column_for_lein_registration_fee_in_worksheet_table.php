<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAColumnForLienRegistrationColumnToDealWorksheet extends Migration
{
    public function up()
    {
        $fields = [
            'lien_registration_fee'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'       => 0,
            ],
        ];

        $this->forge->addColumn('deal_base_breakdown', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('deal_base_breakdown', [ 'lien_registration_fee']); // to drop one single column
    }
}
