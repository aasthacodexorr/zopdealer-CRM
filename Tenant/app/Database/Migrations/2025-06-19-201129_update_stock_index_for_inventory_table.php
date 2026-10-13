<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStockNumberIndexForInventoryTable extends Migration
{
    public function up()
    {
        $db      = \Config\Database::connect();
        $db->query('ALTER TABLE `inventory` ADD INDEX( `stock_no`)');
    }

    public function down() {}
}
