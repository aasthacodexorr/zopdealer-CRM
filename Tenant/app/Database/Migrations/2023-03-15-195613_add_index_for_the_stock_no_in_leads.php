<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIndexToStockNoToLeadResponse extends Migration
{
    public function up()
    {
        $db      = \Config\Database::connect();
        $db->query("ALTER TABLE lead_responses ADD INDEX(stock_no);");
    }

    public function down()
    {
    }
}
