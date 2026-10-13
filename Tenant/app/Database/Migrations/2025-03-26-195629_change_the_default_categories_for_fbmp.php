<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterDefaultFBMPCategory extends Migration
{
    public function up()
    {

        $db      = \Config\Database::connect();
        $db->query("UPDATE `configurations` SET `value` = '[{\"name\":\"Car/Truck\",\"value\":\"Car/Truck\"},{\"name\":\"Trailer\",\"value\":\"Trailer\"},{\"name\":\"RV/Camper\",\"value\":\"RV/Camper\"},{\"name\":\"Commercial/Industrial\",\"value\":\"Commercial/Industrial\"},{\"name\":\"Motorcycle\",\"value\":\"Motorcycle\"},{\"name\":\"Powersport\",\"value\":\"Powersport\"},{\"name\":\"Boat\",\"value\":\"Boat\"},{\"name\":\"Other\",\"value\":\"Other\"}]'
WHERE `key` = 'all_facebook_categories' LIMIT 1;");
    }

    public function down() {}
}
