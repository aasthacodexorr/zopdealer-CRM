<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddValueColumnInVoucherTable extends Migration
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

        $this->forge->addColumn('vouchers', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('vouchers', ['value']); // to drop one single column
    }
}
