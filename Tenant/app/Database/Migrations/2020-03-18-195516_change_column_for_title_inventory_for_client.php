<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyInventoryForInventory extends Migration
{
    public function up()
    {
    
    $fields = [
            'title' => [
                    'type' => 'TEXT'
            ]
    ];
    $this->forge->modifyColumn('inventory', $fields);

    }

    public function down()
    {
        $fields = [
            'title' => [
                    'type' => 'VARCHAR'
            ]
    ];
     //$this->forge->modifyColumn('inventory', $fields);

    }

}