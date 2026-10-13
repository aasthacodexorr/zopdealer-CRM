<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddKijijiNpvCategoryFieldInInventoryTable extends Migration
{
    public function up()
    {
        $fields = [
            'kijijinpv_category'	=>[
				'type'           => 'VARCHAR',
				'constraint'     => '255',
                'null'           => TRUE,
                'default'        => '',
            ]            
        ];

        $this->forge->addColumn('inventory', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('inventory', [ 'kijijinpv_category']);
    }
}
