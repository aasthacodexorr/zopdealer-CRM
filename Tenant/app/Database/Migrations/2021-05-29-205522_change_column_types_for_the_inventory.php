<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChangeTheFieldTypesForDescriptionAndCustomFeaturesInInventory extends Migration
{
    public function up()
    {

        $fields = [
            'vehicle_description' => [
                'type' => 'TEXT',
            ],
            'custom_features' => [
                'type' => 'TEXT',
            ],
        ];
        $this->forge->modifyColumn('inventory', $fields);
    }

    public function down()
    {
    }
}
