<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIndexToStockNoToReferenceTables extends Migration
{
    public function up()
    {
        $db      = \Config\Database::connect();
        $db->query("ALTER TABLE facebook_marketplace ADD INDEX(stock_no);");
        $db->query("ALTER TABLE craigslist ADD INDEX(stock_no);");
        $db->query("ALTER TABLE social_connect ADD INDEX(stock_no);");
        $db->query("ALTER TABLE carfax_order_logs ADD INDEX(stock_no);");
        $db->query("ALTER TABLE work_order_inventory_pivot_table ADD INDEX(stock_no);");
         $db->query("ALTER TABLE deal_reports ADD INDEX(stock_no);");
    }

    public function down()
    {
    }
}
