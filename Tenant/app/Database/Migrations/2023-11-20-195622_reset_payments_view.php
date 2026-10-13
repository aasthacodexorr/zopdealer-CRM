<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class ResetPaymentViewToAddReason extends Migration
{

    public function up()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('payments_view');
        $builder->set('view', '[{"header":"DATE","column":"date","component":"date","visibility":"1"},{"header":"NAME","column":"name","component":"text","visibility":"1"},{"header":"PHONE","column":"phone","component":"phone","visibility":"1"},{"header":"EMAIL","column":"email","component":"email","visibility":"1"},{"header":"TRANS ID","column":"transaction_id","component":"text","visibility":"1"},{"header":"EVENT ID","column":"event_id","component":"text","visibility":"0"},{"header":"RECEIPT ID","column":"receipt_number","component":"text","visibility":"1"},{"header":"AMOUNT","column":"amount","component":"currency","visibility":"1"},{"header":"TYPE","column":"type","component":"badge","visibility":"0"},{"header":"MODE","column":"mode","component":"badge","visibility":"0"},{"header":"STATUS","column":"status","component":"badge","visibility":"1"},{"header":"SUB STATUS","column":"sub_status","component":"badge","visibility":"1"},{"header":"MODULE","column":"module","component":"module","visibility":"1"},{"header":"REASON","column":"reason","component":"text","visibility":"1"},{"header":"RECEIPT EMAIL","column":"receipt_email","component":"email","visibility":"0"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"}]');
        $builder->update();
    }

    public function down()
    {
    }
}