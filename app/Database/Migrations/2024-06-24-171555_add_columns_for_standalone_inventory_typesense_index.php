<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;
use DateTime;

class UpdateInventoryTypesenseIndexForTheClient extends Migration
{
    public function up()
    {
        $fields = [
            'update_inventory_typesense_index_schedule'       => [
                'type'           => 'DATETIME',
                'default'   => (new DateTime())->format('Y-m-d H:i:s'),
                'null'           => TRUE,
            ],
        ];

        $this->forge->addColumn('clients', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('clients', [ 'update_inventory_typesense_index_schedule']); // to drop one single column
    }
}