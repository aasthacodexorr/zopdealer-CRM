<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class ResetInventoryPurchaseReportsForPermit extends Migration
{
    public function up()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('inventory_purchase_reports_view');
        $builder->set('view', '[{"header":"PURCHASE DATE","column":"purchase_date","component":"date","visibility":"1"},{"header":"YEAR","column":"year","component":"text","visibility":"1"},{"header":"MAKE","column":"make","component":"text","visibility":"1"},{"header":"MODEL","column":"model","component":"text","visibility":"1"},{"header":"TRIM","column":"trim","component":"text","visibility":"1"},{"header":"STOCK NO","column":"stock_no","component":"text","visibility":"1"},{"header":"VIN","column":"vin","component":"text","visibility":"1"},{"header":"VENDOR NAME","column":"vendor_name","component":"text","visibility":"1"},{"header":"VENDOR PHONE","column":"phone","component":"phone","visibility":"1"},{"header":"VENDOR MOBILE","column":"mobile","component":"phone","visibility":"1"},{"header":"VENDOR EMAIL","column":"email","component":"email","visibility":"1"},{"header":"PLATE NUMBER","column":"plate_number","component":"text","visibility":"1"},{"header":"VENDOR COMPANY NAME","column":"company_name","component":"text","visibility":"1"},{"header":"VENDOR APT","column":"apt","component":"text","visibility":"1"},{"header":"VENDOR STREET","column":"street_address","component":"text","visibility":"1"},{"header":"VENDOR CITY","column":"city","component":"text","visibility":"1"},{"header":"VENDOR PROVINCE","column":"province","component":"text","visibility":"1"},{"header":"VENDOR POSTAL CODE","column":"postal_code","component":"text","visibility":"1"},{"header":"VENDOR COUNTRY","column":"country","component":"text","visibility":"1"},{"header":"PURCHASE PRICE","column":"price","component":"currency","visibility":"1"},{"header":"PURCHASE DISCOUNT","column":"discount","component":"currency","visibility":"1"},{"header":"PURCHASE PRIMARY TAX","column":"tax_amount","component":"currency","visibility":"1"},{"header":"PURCHASE SECONDARY TAX","column":"tax_amount_secondary","component":"currency","visibility":"1"},{"header":"PURCHASE TOTAL TAX","column":"tax_amount_total","component":"currency","visibility":"1"},{"header":"PAID STATUS","column":"has_been_paid_for","component":"yes_or_no","visibility":"1"},{"header":"INVENTORY PAID DATE","column":"payment_date","component":"date","visibility":"1"},{"header":"TITLE RECEIVED","column":"has_title_been_received","component":"yes_or_no","visibility":"1"},{"header":"TITLE RECEIVED DATE","column":"title_received_date","component":"date","visibility":"1"},{"header":"TITLE TRANSFER","column":"has_title_been_transferred","component":"yes_or_no","visibility":"1"},{"header":"PERMIT NO","column":"permit_number","component":"text","visibility":"1"},{"header":"TITLE TRANSFER DATE","column":"title_transfer_date","component":"date","visibility":"1"},{"header":"HAS LIEN","column":"has_lien","component":"yes_no_unselected","visibility":"1"},{"header":"LIEN AMOUNT","column":"lien_amount","component":"currency","visibility":"1"},{"header":"LIEN HOLDER NAME","column":"lien_holder_name","component":"text","visibility":"1"},{"header":"HAS LIEN BEEN PAID","column":"lien_has_been_paid","component":"yes_no_unselected","visibility":"1"},{"header":"LIEN PAID DATE","column":"lien_paid_date","component":"date","visibility":"1"}]');
        $builder->update();
    }

    public function down() {}
}
