<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;
use Tenant\app\Repositories\DealReportViewRepository;

class ResetDealReportViewsForDealId extends Migration
{
    public function up()
    {
        $db      = \Config\Database::connect();
        $query = $db->query('select * from deal_reports_view');

        foreach ($query->getResult('array') as $row) {
            $view = $row['view'];
            $view = str_replace('"id"', '"deal_id"', $view);
            $view = str_replace('"payment_details","component":"currency"', '"payment_details","component":"text"', $view);
            (new DealReportViewRepository())->update(["view" => $view], $row["id"]);
        }
    }

    public function down() {}
}
