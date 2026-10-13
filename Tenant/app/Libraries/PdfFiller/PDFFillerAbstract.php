<?php

namespace Tenant\app\Libraries\PdfFiller;

use mikehaertl\pdftk\Pdf;

abstract class PDFFillerAbstract implements PDFFillerInterface
{
    protected $template_name;

    protected $pdf_driver;

    protected $company_as_name = false;

    protected $data = [];

    public function output(): void
    {
        $this->pdf_driver->fillForm($this->data)->needAppearances()
            ->send();
    }

    public function save(String $template_name): void
    {
        $this->pdf_driver->fillForm($this->data)->needAppearances()
            ->saveAs($template_name);
    }

    public function getData(): array
    {
        return $this->data;
    }
}
