<?php

namespace App\Repositories;

use App\Models\AuditOutboxModel;
use App\Repositories\CRUDWithAdvanceRepository;


class AuditOutboxRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new AuditOutboxModel());
        $this->entity = 'CodeIgniter\Entity';
        $this->entityLabel = 'Audit Outbox';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'audit_log_id';
    }

    public function getPendingLogs(int $limit): array
    {
        $this->model->where($this->model->table . '.status', 'PENDING');
        return parent::index(null, null, null, null, $limit, '{"id" : "ASC"}', false);
    }
}
