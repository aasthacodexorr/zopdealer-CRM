<?php

namespace Tenant\app\Libraries\Import;

use Tenant\app\Repositories\DealCommonDependentRepository;

class DealFinance extends ImportAbstract
{
    public function __construct()
    {
        $this->repository = new DealCommonDependentRepository();
        $this->repository->switchDealCommonDependentState('finance');
    }

    public function validate(array $data, array $namespaces = [], array $errors = []): array
    {
        if ($this->hasFinance($data)) {
            $entity_error['finance'] = $this->repository->validate($data['finance']);

            if (gettype($entity_error['finance']) == "array") {
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
        if (!empty($data['deal_id']) && $this->hasFinance($data)) {
            $data['finance']['deal_id'] = $data['deal_id'];
            $this->repository->create($data['finance']);
        }

        if (count($namespaces) > 0 && !empty($data['deal_id'])) {
            $import_entity_namespace = array_shift($namespaces);
            $import_instance = new $import_entity_namespace();
            return $import_instance->process($data, $namespaces, $response);
        } else {
            return $response;
        }
    }

    private function hasFinance(array $data = []): bool
    {
        if (!empty($data['finance']) && !empty($data['type'])) {
            if (is_array($data['finance']) && $data['type'] === "Financed") {
                return true;
            }
        }

        return false;
    }
}
