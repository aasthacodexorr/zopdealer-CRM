<?php

namespace Tenant\app\Controllers;

use App\Libraries\AuditLog\AuditLog;
use App\Repositories\AuditOutboxRepository;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Controller;
set_time_limit(0);

class AuditOutboxController extends Controller
{
    private AuditOutboxRepository $repository;
    private AuditLog $auditLog;

    public function __construct()
    {
        $this->repository = new AuditOutboxRepository();
        $this->auditLog   = new AuditLog();
    }

    public function add(...$arg)
    {
        $payload = json_decode(base64_decode($arg[0]), true);

        $this->repository->create([
            'database'     => $payload['database'] ?? '',
            'database_uid' => $payload['dealership_uid'] ?? '',
            'event_id'     => $payload['event_id'] ?? '',
            'payload'      => json_encode($payload),
            'status'       => 'PENDING',
        ]);
    }

    public function process()
    {
        $logs = $this->repository->getPendingLogs(100);

        foreach ($logs['data_list'] as $log) {
            try {
                $payload = json_decode($log['payload'], true);
                $result  = $this->auditLog->record($payload);

                $this->repository->updates($log['id'], [
                    'status'   => $result['status'],
                    'response' => $result['response'] ?? null,
                ]);
            } catch (\Throwable $ex) {
                CLI::write($ex->getMessage());
                $this->repository->updates($log['id'], [
                    'status'   => 'ERROR',
                    'response' => json_encode(['error' => $ex->getMessage()]),
                ]);
            }
        }
    }
}
