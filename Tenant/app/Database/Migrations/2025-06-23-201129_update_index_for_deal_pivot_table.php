<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIndexesForDealPivotTable extends Migration
{
    public function up()
    {
        $db      = \Config\Database::connect();
        $db->query('ALTER TABLE `deal_customer_pivot_table` ADD INDEX( `deal_id`, `customer_id`)');
        $db->query('ALTER TABLE `deal_inventory_pivot_table` ADD INDEX( `deal_id`, `stock_no`)');
    }

    public function down() {}
}
