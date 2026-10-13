<?php

namespace Tenant\app\Libraries\PdfFiller\Version1;

use App\Helpers\Utility;
use Carbon\Carbon;
use Tenant\app\Libraries\PdfFiller\PDFFillerAbstract;
use mikehaertl\pdftk\Pdf;
use Tenant\app\Libraries\PdfFiller\PDFFillerInterface;

class PaymentReceipt extends PDFFillerAbstract implements PDFFillerInterface
{
    public function __construct(String $template_name)
    {
        $this->template_name = $template_name;
        $this->pdf_driver = new Pdf($template_name);
    }

    public function dataMap(array $data)
    {

        $this->data['receipt_number'] = $data['receipt_number'];
        $this->data['name'] =   $data['name'];
        $this->data['transaction_id'] = $data['transaction_id'];
        $this->data['type'] =   $data['type'];
        $this->data['reason'] = $data['reason'];

        $this->data['amount'] = number_format((float) $data['amount'], 2, '.', ',');
        $this->data['amount_1'] = number_format((float) $data['amount'], 2, '.', ',');


        if (strtotime($data['date'])) {
            $this->data['date'] =   Carbon::parse($data['date'])->toFormattedDateString(); 
        }


        return $this;
    }
}
