<?php

namespace Tenant\app\Libraries\Import;

use Tenant\app\Repositories\DealCommonDependentRepository;

class DealBaseBreakdown extends ImportAbstract
{
    public function __construct()
    {
        $this->repository = new DealCommonDependentRepository();
        $this->repository->switchDealCommonDependentState('dealbreakdown');
    }

    public function validate(array $data, array $namespaces = [], array $errors = []): array
    {
        if (is_array($data['base_breakdown'])) {
            $entity_error['base_breakdown'] = $this->repository->validate($data['base_breakdown']);

            if (gettype($entity_error['base_breakdown']) == "array") {
                $errors = array_merge($entity_error, $errors);
            }
        }

        if (count($namespaces) > 0) {
            $import_entity_namespace = array_shift($namespaces);
            $import_instance = new $import_entity_namespace();
            return $import_instance->validate($data, $namespaces, $errors);
        } else {
            return $errors;
        }
    }

    public function process(array $data, array $namespaces = [], array $response = []): array
    {
        if (!empty($data['deal_id']) && is_array($data['base_breakdown'])) {
            $data['base_breakdown']['deal_id'] = $data['deal_id'];
            $this->repository->create($data['base_breakdown']);
        }

        if (count($namespaces) > 0 && !empty($data['deal_id'])) {
            $import_entity_namespace = array_shift($namespaces);
            $import_instance = new $import_entity_namespace();
            return $import_instance->process($data, $namespaces, $response);
        } else {
            return $response;
        }
    }
}
