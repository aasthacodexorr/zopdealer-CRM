<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class ResetSearchColumnToRepopulateSearchKeyWordsWithSellingPrice extends Migration
{
    public function up()
    {
        $db      = \Config\Database::connect();
        $db->query("UPDATE inventory SET search_keyword = '';");

    }

    public function down()
    {
    }
}