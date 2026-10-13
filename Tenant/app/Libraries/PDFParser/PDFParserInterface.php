<?php

namespace Tenant\app\Libraries\PDFParser;

use CURLFile;

/***
 * PDFParserInterface defines the structure of the PDFParser.
 */
interface PDFParserInterface
{    
    /**
     * sendRequest  This function accepts curl file to send the request
     *
     * @param  CURLFile $curl_file
     * @param  array    $options
     * @return array
     */
    public function sendRequest(CURLFile $curl_file, array $options = []): ?array;
    
    /**
     * mapResponse  This function accepts the data received for PDF Parser and formats it as per the entity.
     *
     * @param  array $data
     * @return array
     */
    public function mapResponse(array $data): array;
}
