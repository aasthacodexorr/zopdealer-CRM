<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSortColumnForInventoryView extends Migration
{
    public function up()
    {
        $fields = [
            'sort'          => [
                'type'   => 'blob',
                'null'   => TRUE,
            ]

            ];

        $this->forge->addColumn('inventory_view', $fields);

    }

    public function down()
    {
        $this->forge->dropColumn('inventory_view','sort');
    }

}