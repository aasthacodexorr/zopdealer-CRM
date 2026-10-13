<?php

namespace Tenant\app\Libraries\PDFParser;

use CURLFile;

abstract class InventoryPDFParserAbstract implements PDFParserInterface
{
    protected string $api_domain;
    protected string $path = '';

    public function __construct()
    {
        $this->api_domain = getEnv('zopai.host');
    }

    public function sendRequest(CURLFile $curl_file, array $options = []): ?array
    {
        $ch = curl_init();
        $postfields = array('file' => $curl_file);
        curl_setopt($ch, CURLOPT_URL, $this->api_domain . $this->path . '?auction_as_seller=' . (isset($options['auction_as_seller']) ? $options['auction_as_seller'] : 1));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);

        $headers = array();
        $headers[] = 'Content-Type: multipart/form-data';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return [
                'errors' => curl_error($ch)
            ];
        }
        curl_close($ch);

        return json_decode($result, true);
    }
}
