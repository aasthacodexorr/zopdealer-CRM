<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;
use DateTime;

class AddDayInStockScheduleForInventory extends Migration
{
    public function up()
    {
        $fields = [
            'inventory_dis_schedule'       => [
                'type'           => 'DATETIME',
                'default'   => (new DateTime())->format('Y-m-d H:i:s'),
                'null'           => TRUE,
            ],
        ];

        $this->forge->addColumn('clients', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('clients', [ 'inventory_dis_schedule']); // to drop one single column
    }
}