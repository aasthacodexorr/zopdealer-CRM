<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class ResetAIStudioBG extends Migration
{
    public function up()
    {
        $db      = \Config\Database::connect();
        $db->query("DELETE from configurations where `key` = 'zop_studio_available_background_images' LIMIT 1");
    }

    public function down() {}
}
