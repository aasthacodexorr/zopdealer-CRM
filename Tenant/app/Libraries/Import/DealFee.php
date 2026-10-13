<?php

namespace Tenant\app\Libraries\Import;

use Tenant\app\Repositories\DealCommonDependentRepository;

class DealFee extends ImportAbstract
{
    public function __construct()
    {
        $this->repository = new DealCommonDependentRepository();
        $this->repository->switchDealCommonDependentState('fees');
    }

    public function validate(array $data, array $namespaces = [], array $errors = []): array
    {
        if (!empty($data['fees']) && is_array($data['fees'])) {
            foreach ($data['fees'] as $fee_key => $value) {
                $entity_error = $this->repository->validate($value);

                if (gettype($entity_error) == "array") {
                    $errors['fees']["fee_" . $fee_key] = $entity_error;
                }
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
        if (!empty($data['deal_id']) && !empty($data['fees']) && is_array($data['fees'])) {
            foreach ($data['fees'] as $fee_key => $fee) {
                $fee['deal_id'] = $data['deal_id'];
                $this->repository->create($fee);
            }
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
