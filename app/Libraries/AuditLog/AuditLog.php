<?php

namespace App\Libraries\AuditLog;

use GuzzleHttp\Client;

class AuditLog
{
    public function record(array $payload): array
    {
        try {
            $client = new Client();

            $response = $client->request('POST', getenv('zop.audit.log.url') . '/api/v1/audit-logs', [
                'json' => $payload,
                'headers' => [
                    'Authorization' => 'Bearer ' . getenv('zop.audit.log.token'),
                    'X-Application-Id' => getenv('zop.audit.log.application_id'),
                ],
                'timeout' => 15,
                'connect_timeout' => 5,
            ]);

            $statusCode = $response->getStatusCode();
            $isSuccess  = ($statusCode >= 200 && $statusCode < 300);
            return [
                'status'   => $isSuccess ? 'SENT' : 'ERROR',
                'response' => $response->getBody(),
            ];
        } catch (\Throwable $e) {
            log_message(
                'error',
                'AuditService API failed: {error}',
                [
                    'error' => $e->getMessage(),
                ]
            );

            log_message('info', json_encode($payload));

            return [
                'status'   => 'ERROR',
                'response' => json_encode(['error' => $e->getMessage()]),
            ];
        }
    }
}
