<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIndexToStockNoToDVI extends Migration
{
    public function up()
    {
        $db      = \Config\Database::connect();
        $db->query("ALTER TABLE digital_vehicle_inspections ADD INDEX(stock_no);");
    }

    public function down() {}
}
