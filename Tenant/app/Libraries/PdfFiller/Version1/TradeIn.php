<?php

namespace Tenant\app\Libraries\PdfFiller\Version1;

use App\Helpers\Utility;
use Tenant\app\Libraries\PdfFiller\PDFFillerInterface;
use mikehaertl\pdftk\Pdf;

class TradeIn extends BillOfSaleRetail implements PDFFillerInterface
{
    public function __construct(String $template_name)
    {
        $this->template_name = $template_name;
        $this->pdf_driver = new Pdf($template_name);
        $this->company_as_name = false;
    }
}
