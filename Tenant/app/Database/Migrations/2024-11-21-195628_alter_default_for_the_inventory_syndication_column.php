<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterDefaultForArrayBasedColumnsInInventory extends Migration
{
    public function up()
    {

        $db      = \Config\Database::connect();
        $db->query("ALTER TABLE `inventory` MODIFY `syndication` VARCHAR(255) DEFAULT '[\"facebook\",\"socialconnect\"]'");
        $db->query("ALTER TABLE `inventory` MODIFY `vehicle_tags` VARCHAR(500) DEFAULT '[]'");
    }

    public function down() {}
}
