<?php

namespace Tenant\app\Libraries\OCR;

use CodeIgniter\HTTP\Files\UploadedFile;

class AzureDocumentAnalyzer
{
    protected string $endpoint;
    protected string $apiKey;
    protected string $model;

    public function __construct()
    {
        $this->endpoint = getenv('ocr.azure.endpoint') ?: '';
        $this->apiKey = getenv('ocr.azure.key') ?: '';
        $this->model = getenv('ocr.azure.model') ?: '';
    }

    public function parse(array $data): array
    {
        if (empty($data['file']) || !($data['file'] instanceof UploadedFile)) {
            return ['status' => 'error', 'errors' => ['file' => 'A valid uploaded file is required.']];
        }

        $file = $data['file'];

        if (!$file->isValid() || $file->hasMoved()) {
            return ['status' => 'error', 'errors' => ['file' => 'Unable to read the uploaded file.']];
        }

        $originalPath = $file->getTempName();
        $originalName = $file->getName();

        $analysisResponse = $this->analyzeFile($originalPath);
        if (empty($analysisResponse['status'])) {
            return $analysisResponse;
        }

        $payload = [
            'api-id' => $analysisResponse['apimRequestId'] ?? '',
            'status' => $analysisResponse['status'],
            'parsed_at' => date('c'),
            'file_name' => $originalName,
            'result' => $analysisResponse['result'] ?? [],
        ];

        if (!empty($analysisResponse['responseLocation'])) {
            $payload['responseLocation'] = $analysisResponse['responseLocation'];
        }

        return $payload;
    }

    public function getParsedResult(string $apiId): array
    {
        if (empty($apiId)) {
            return ['status' => 'error', 'errors' => ['api-id' => 'Parsed result not found or invalid operation id.']];
        }

        $baseEndpoint = rtrim($this->endpoint, '/');
        if (str_contains($baseEndpoint, '/formrecognizer')) {
            $baseEndpoint = str_replace('/formrecognizer', '', $baseEndpoint);
        }
        $responseLocation = $baseEndpoint . '/documentintelligence/documentModels/' . $this->model . '/analyzeResults/' . $apiId . '?api-version=2024-11-30';

        return $this->pollResponseLocation($responseLocation);
    }

    protected function pollResponseLocation(string $responseLocation): array
    {
        $headers = [
            'ocp-apim-subscription-key: ' . $this->apiKey,
            'Accept: application/json',
        ];

        $attempt = 0;
        $maxAttempts = 5;
        $waitSeconds = 5;

        while ($attempt < $maxAttempts) {
            $attempt++;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $responseLocation);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $body = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);

            if ($err) {
                return ['status' => 'error', 'errors' => ['curl' => $err]];
            }

            $decoded = json_decode($body, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return ['status' => 'error', 'errors' => ['response' => 'Unable to parse Azure poll response.', 'body' => $body]];
            }

            // If Azure returns a direct error (e.g. expired key, invalid endpoint, model mismatch)
            // stop polling immediately instead of waiting for timeout.
            if (isset($decoded['error'])) {
                return ['status' => 'error', 'errors' => ['azure_error' => $decoded['error']]];
            }

            $status = strtolower($decoded['status'] ?? $decoded['analyzeResult']['status'] ?? '');
            if ($status === 'succeeded') {
                return ['status' => 'success', 'result' => $decoded['analyzeResult'] ?? $decoded];
            }
            if ($status === 'failed' || $status === 'cancelled') {
                return ['status' => 'error', 'errors' => ['azure_status' => $decoded]];
            }

            usleep($waitSeconds * 1000000);
        }

        return ['status' => 'error', 'errors' => ['timeout' => 'Azure analysis did not complete in time.']];
    }

    protected function analyzeFile(string $localPath): array
    {
        if (empty($this->endpoint) || empty($this->apiKey) || empty($this->model)) {
            return ['status' => 'error', 'errors' => ['configuration' => 'Azure Document Analyzer is not configured.']];
        }

        $apiVersion = '2024-11-30';
        $baseEndpoint = rtrim($this->endpoint, '/');
        if (str_contains($baseEndpoint, '/formrecognizer')) {
            $baseEndpoint = str_replace('/formrecognizer', '', $baseEndpoint);
        }

        $url = $baseEndpoint . '/documentintelligence/documentModels/' . $this->model . ':analyze?api-version=' . $apiVersion;

        $body = @file_get_contents($localPath);
        if ($body === false) {
            return ['status' => 'error', 'errors' => ['file' => 'Unable to read uploaded file for sending to Azure.']];
        }
        $mime = @mime_content_type($localPath) ?: 'application/octet-stream';

        $headers = [
            'ocp-apim-subscription-key: ' . $this->apiKey,
            'Content-Type: ' . $mime,
            'Content-Length: ' . strlen($body),
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        if ($error) {
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            return ['status' => 'error', 'errors' => ['curl' => $error, 'http_code' => $httpCode]];
        }

        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $responseHeaders = substr($response, 0, $headerSize);
        $responseBody = substr($response, $headerSize);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $apimRequestId = null;
        $opLocation = null;
        foreach (explode("\r\n", $responseHeaders) as $h) {
            if (stripos($h, 'apim-request-id:') === 0) {
                $apimRequestId = trim(substr($h, strlen('apim-request-id:')));
            }
            if (stripos($h, 'operation-location:') === 0) {
                $opLocation = trim(substr($h, strlen('operation-location:')));
            } elseif (stripos($h, 'operation-location') !== false && preg_match('/operation-location:\s*(\S+)/i', $h, $m)) {
                $opLocation = $m[1];
            }
        }

        // Fallback: extract UUID from operation-location if apim-request-id is missing
        if (empty($apimRequestId) && !empty($opLocation)) {
            if (preg_match('/\/analyzeResults\/([a-f0-9-]+)/i', $opLocation, $matches)) {
                $apimRequestId = $matches[1];
            }
        }

        $decoded = json_decode($responseBody, true);
        if (json_last_error() === JSON_ERROR_NONE && isset($decoded['error'])) {
            return ['status' => 'error', 'errors' => ['azure_error' => $decoded['error']]];
        }

        if (!empty($apimRequestId) || !empty($opLocation)) {
            return [
                'status' => 'pending',
                'apimRequestId' => $apimRequestId ?: '',
                'responseLocation' => $opLocation,
                'result' => isset($decoded['analyzeResult']) ? $decoded['analyzeResult'] : null,
            ];
        }

        if (json_last_error() === JSON_ERROR_NONE && isset($decoded['analyzeResult'])) {
            return [
                'status' => 'success',
                'apimRequestId' => $apimRequestId ?: '',
                'result' => $decoded['analyzeResult']
            ];
        }

        return ['status' => 'error', 'errors' => ['response' => 'No operation-location or apim-request-id header returned by Azure.', 'http_code' => $httpCode, 'body' => $decoded ?? $responseBody]];
    }

    protected function sanitizeId(string $id): string
    {
        return preg_replace('/[^a-zA-Z0-9_-]/', '', $id);
    }
}
