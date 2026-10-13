<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterColumnLengthForSINInIndCreditApplication extends Migration
{
    public function up()
    {

        $db      = \Config\Database::connect();
        $db->query("ALTER TABLE `customer_individual_credit_application` MODIFY `sin` VARCHAR(1000);");
    }

    public function down() {}
}
