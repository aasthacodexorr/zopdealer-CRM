<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddInventoryImagesOverlayColumnForTheClient extends Migration
{
    public function up()
    {
        $fields = [
            'image_with_overlay_url'          => [
                'type'   => 'TEXT',
                'null'           => TRUE,
            ]

            ];

        $this->forge->addColumn('inventory_image', $fields);

    }

    public function down()
    {
        $this->forge->dropColumn('inventory_image','image_with_overlay_url');
    }

}