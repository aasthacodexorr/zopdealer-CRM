<?php

namespace Tenant\app\Libraries\PdfFiller;

/***
 * PDFFillerInterface defines the structure of the PDFFiller.
 */
interface PDFFillerInterface
{
    /**
     * output This method outputs the Filled PDF to the Browser
     *
     * @return void
     */
    public function output(): void;

    /**
     * save This method takes the PDF File that is supposed to be saved.
     *
     * @param  String $file_name
     * @return void
     */
    public function save(String $file_name): void;

    /**
     * dataMap This method maps the data from the Deal details to PDF.
     *
     * @param  array $data
     */
    function dataMap(array $data);

    /**
     * getData
     *
     * @return array
     */
    function getData(): array;
}
