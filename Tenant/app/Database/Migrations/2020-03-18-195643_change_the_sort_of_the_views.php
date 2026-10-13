<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChangeTheFieldTypesForSortForAllTheViews extends Migration
{
    public function up()
    {

        $fields = [
            'sort' => [
                'type' => 'TEXT',
            ],

        ];
        $this->forge->modifyColumn('deals_view', $fields);
        $this->forge->modifyColumn('inventory_view', $fields);
        $this->forge->modifyColumn('customers_view', $fields);
        $this->forge->modifyColumn('lead_responses_view', $fields);
        $this->forge->modifyColumn('vendors_view', $fields);
    }

    public function down()
    {
    }
}
