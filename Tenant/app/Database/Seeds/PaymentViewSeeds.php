<?php

namespace Tenant\app\Database\Seeds;

use Tenant\app\Models\PaymentViewModel;

class PaymentViewSeeds extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $arrays = [
            [
                'header' => 'Charges',
                'view' => '[{"header":"DATE","column":"date","component":"date","visibility":"1"},{"header":"NAME","column":"name","component":"text","visibility":"1"},{"header":"PHONE","column":"phone","component":"phone","visibility":"1"},{"header":"EMAIL","column":"email","component":"email","visibility":"1"},{"header":"TRANS ID","column":"transaction_id","component":"text","visibility":"1"},{"header":"EVENT ID","column":"event_id","component":"text","visibility":"0"},{"header":"RECEIPT ID","column":"receipt_number","component":"text","visibility":"1"},{"header":"AMOUNT","column":"amount","component":"currency","visibility":"1"},{"header":"TYPE","column":"type","component":"badge","visibility":"0"},{"header":"MODE","column":"mode","component":"badge","visibility":"0"},{"header":"STATUS","column":"status","component":"badge","visibility":"1"},{"header":"SUB STATUS","column":"sub_status","component":"badge","visibility":"1"},{"header":"MODULE","column":"module","component":"module","visibility":"1"},{"header":"RECEIPT EMAIL","column":"receipt_email","component":"email","visibility":"0"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"}]',
                'query' => '[{"header":"Status","column":"status","join":"AND","comparator":"is","type":"Single Select","value_1":"Charge","value_2":""}]',
                'default' => '1',
            ],
            [
                'header' => 'Refunds',
                'view' => '[{"header":"DATE","column":"date","component":"date","visibility":"1"},{"header":"NAME","column":"name","component":"text","visibility":"1"},{"header":"PHONE","column":"phone","component":"phone","visibility":"1"},{"header":"EMAIL","column":"email","component":"email","visibility":"1"},{"header":"TRANS ID","column":"transaction_id","component":"text","visibility":"1"},{"header":"EVENT ID","column":"event_id","component":"text","visibility":"0"},{"header":"RECEIPT ID","column":"receipt_number","component":"text","visibility":"1"},{"header":"AMOUNT","column":"amount","component":"currency","visibility":"1"},{"header":"TYPE","column":"type","component":"badge","visibility":"0"},{"header":"MODE","column":"mode","component":"badge","visibility":"0"},{"header":"STATUS","column":"status","component":"badge","visibility":"1"},{"header":"SUB STATUS","column":"sub_status","component":"badge","visibility":"1"},{"header":"MODULE","column":"module","component":"module","visibility":"1"},{"header":"RECEIPT EMAIL","column":"receipt_email","component":"email","visibility":"0"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"}]',
                'query' => '[{"header":"Status","column":"status","join":"AND","comparator":"is","type":"Single Select","value_1":"Refund","value_2":""}]',
                'default' => '1',
            ],
            [
                'header' => 'All Payments',
                'view' => '[{"header":"DATE","column":"date","component":"date","visibility":"1"},{"header":"NAME","column":"name","component":"text","visibility":"1"},{"header":"PHONE","column":"phone","component":"phone","visibility":"1"},{"header":"EMAIL","column":"email","component":"email","visibility":"1"},{"header":"TRANS ID","column":"transaction_id","component":"text","visibility":"1"},{"header":"EVENT ID","column":"event_id","component":"text","visibility":"0"},{"header":"RECEIPT ID","column":"receipt_number","component":"text","visibility":"1"},{"header":"AMOUNT","column":"amount","component":"currency","visibility":"1"},{"header":"TYPE","column":"type","component":"badge","visibility":"0"},{"header":"MODE","column":"mode","component":"badge","visibility":"0"},{"header":"STATUS","column":"status","component":"badge","visibility":"1"},{"header":"SUB STATUS","column":"sub_status","component":"badge","visibility":"1"},{"header":"MODULE","column":"module","component":"module","visibility":"1"},{"header":"RECEIPT EMAIL","column":"receipt_email","component":"email","visibility":"0"},{"header":"CREATION DATE","column":"created_at","component":"date","visibility":"1"},{"header":"MODIFIED DATE","column":"updated_at","component":"date","visibility":"1"}]',
                'query' => '[]',
                'default' => '1',
            ],
        ];

        $paymentViewModel = new PaymentViewModel();
        $paymentViewModel->protect(false);
        foreach ($arrays as $array) {
            $paymentViewModel->ignore(true)->insert($array);
        }
        $paymentViewModel->protect(true);
    }
}