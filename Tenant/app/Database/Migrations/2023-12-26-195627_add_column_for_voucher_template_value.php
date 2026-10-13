<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddValueColumnInVoucherTemplate extends Migration
{
    public function up()
    {
        $fields = [
            'value'    => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => TRUE,
                'default'        => 0
            ],
        ];

        $this->forge->addColumn('voucher_templates', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('voucher_templates', ['value']); // to drop one single column
    }
}
