<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSecondarySubTotalForTheBreakDown extends Migration
{
    public function up()
    {
        $fields = [
            'secondary_subtotal'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'default'        => 0
            ],
        ];

        $this->forge->addColumn('deal_base_breakdown', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('deal_base_breakdown', ['secondary_subtotal']); // to drop one single column
    }
}
