<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateTextColumnsForLocations extends Migration
{
    public function up()
    {
        $db      = \Config\Database::connect();
        $db->query('UPDATE locations
        SET address_bar = "",
        address_map_url = ""
        ');
    }

    public function down() {}
}
