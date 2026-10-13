<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIndexForScheduleTable extends Migration
{
    public function up()
    {
        $db      = \Config\Database::connect();
        $db->query("ALTER TABLE schedule ADD INDEX(unique_index,`database`,module);");
    }

    public function down()
    {
    }
}
