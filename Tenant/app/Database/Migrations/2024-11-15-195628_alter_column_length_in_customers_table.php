<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterColumnLengthForEncryptedFieldInCustomersTable extends Migration
{
    public function up()
    {

        $db      = \Config\Database::connect();
        $db->query("ALTER TABLE `customers` MODIFY `driver_licence` VARCHAR(1000);");
        $db->query("ALTER TABLE `customers` MODIFY `date_of_birth` VARCHAR(1000);");
    }

    public function down() {}
}
