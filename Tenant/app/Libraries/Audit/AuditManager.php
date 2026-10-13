<?php

namespace Tenant\app\Libraries\Audit;
class AuditManager
{
    protected AuditContext $context;

    public function __construct(AuditContext $context)
    {
        $this->context = $context;
    }

    public function build(
        string $module,
        string $entity_type,
               $entity_id,
        string $action,
        array  $change_set,
               $sub_module = null
    ): array
    {
        return array_merge(
            $this->context->toArray(),
            [
                'module' => strtolower($module),
                'sub_module' => strtolower($sub_module ?? ''),
                'entity_type' => strtolower($entity_type),
                'entity_id' => (string)$entity_id,
                'event_id' => bin2hex(random_bytes(64)),
                'action' => $action,
                'changes' =>
                    $change_set['changes'] ?? [],
                'changed_fields' =>
                    $change_set['changed_fields'] ?? [],
                'change_types' =>
                    $change_set['change_types'] ?? [],
                'created_at' => gmdate('c'),
                'schema_version' => 1,
            ]
        );
    }

    public function hasChanges(array $change_set): bool
    {
        return !empty($change_set['changes']);
    }
}