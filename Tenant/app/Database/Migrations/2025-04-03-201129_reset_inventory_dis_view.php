<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class ResetDISQueryInView extends Migration
{

    public function up()
    {
        $db      = \Config\Database::connect();
        $db      = \Config\Database::connect();
        $db->query("UPDATE `inventory_view` SET `query` = '[{\"header\":\"Days in Stock\",\"column\":\"days_in_stock\",\"join\":\"AND\",\"comparator\":\"greater than\",\"type\":\"Numeric Comparision\",\"value_1\":\"90\",\"value_2\":\"\"},{\"header\":\"Status\",\"column\":\"status\",\"join\":\"AND\",\"comparator\":\"is\",\"type\":\"Multi Select\",\"value_1\":[\"Instock\"],\"value_2\":\"\"}]' WHERE `inventory_view`.`header` = 'Inventory Age: 90+' 
        LIMIT 1;");
        
    }

    public function down() {}
}
