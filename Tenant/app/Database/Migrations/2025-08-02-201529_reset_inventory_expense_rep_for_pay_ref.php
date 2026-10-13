<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class ResetInventoryExpenseRepViewForPayRef extends Migration
{

    public function up()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('inventory_cost_reports_view');
        $builder->set('view', '[{"header":"EXPENSE DATE","column":"purchase_date","component":"date","visibility":"1"},{"header":"YEAR","column":"year","component":"text","visibility":"1"},{"header":"MAKE","column":"make","component":"text","visibility":"1"},{"header":"MODEL","column":"model","component":"text","visibility":"1"},{"header":"TRIM","column":"trim","component":"text","visibility":"1"},{"header":"STOCK NO","column":"stock_no","component":"text","visibility":"1"},{"header":"VIN","column":"vin","component":"text","visibility":"1"},{"header":"NAME","column":"name","component":"text","visibility":"1"},{"header":"INVOICE","column":"invoice","component":"text","visibility":"1"},{"header":"PAYMENT REF","column":"payment_reference","component":"text","visibility":"1"},{"header":"VENDOR NAME","column":"vendor_name","component":"text","visibility":"1"},{"header":"PRICE","column":"price","component":"currency","visibility":"1"},{"header":"QUANTITY","column":"quantity","component":"text","visibility":"1"},{"header":"DISCOUNT","column":"discount","component":"currency","visibility":"1"},{"header":"PRIMARY TAX","column":"tax_amount","component":"currency","visibility":"1"},{"header":"SECONDARY TAX","column":"tax_amount_secondary","component":"currency","visibility":"1"},{"header":"TOTAL TAX","column":"tax_amount_total","component":"currency","visibility":"1"},{"header":"LINE TOTAL","column":"sub_total","component":"currency","visibility":"1"}]');
        $builder->update();
    }

    public function down() {}
}
