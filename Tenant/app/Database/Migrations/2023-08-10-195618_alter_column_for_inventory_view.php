<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterColumnForInventoryViewColumForLead extends Migration
{
    public function up()
    {
        $db      = \Config\Database::connect();
        $db->query('UPDATE inventory_view
        SET sort = NULL
        WHERE sort LIKE "%lead_count%";');
    }

    public function down()
    {
    }
}
