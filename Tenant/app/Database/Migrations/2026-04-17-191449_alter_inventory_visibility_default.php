<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterInventoryVisibilityDefault extends Migration
{
    public function up()
    {
        $db      = \Config\Database::connect();
        $db->query('ALTER TABLE `inventory` ALTER COLUMN `visibility` SET DEFAULT 1;');
    }

    public function down()
    {
        $db      = \Config\Database::connect();
        $db->query('ALTER TABLE `inventory` ALTER COLUMN `visibility` SET DEFAULT 0;');
    }
}
