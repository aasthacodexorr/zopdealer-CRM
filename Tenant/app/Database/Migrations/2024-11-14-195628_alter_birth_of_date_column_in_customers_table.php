<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterBirthOfDateColumnInCustomersTable extends Migration
{
    public function up()
    {

        $db      = \Config\Database::connect();
        $db->query("ALTER TABLE `customers` CHANGE `date_of_birth` `date_of_birth` VARCHAR(100) NULL DEFAULT NULL;");
    }

    public function down()
    {
        $db      = \Config\Database::connect();
        $db->query("ALTER TABLE `customers` CHANGE `date_of_birth` `date_of_birth` DATE NULL DEFAULT NULL;");
    }
}