<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;
use Tenant\app\Repositories\InventoryPurchaseReportViewRepository;

class ResetInventoryPurchaseReportViews extends Migration
{
    public function up()
    {
        $db      = \Config\Database::connect();
        $query = $db->query('select * from inventory_purchase_reports_view');

        foreach ($query->getResult('array') as $row) {
            $view = $row['view'];
            $view = str_replace('"has_lien","component":"yes_or_no"', '"has_lien","component":"yes_no_unselected"', $view);
            $view = str_replace('"lien_has_been_paid","component":"yes_or_no"', '"lien_has_been_paid","component":"yes_no_unselected"', $view);
            (new InventoryPurchaseReportViewRepository())->update(["view" => $view], $row["id"]);
        }
    }

    public function down() {}
}
