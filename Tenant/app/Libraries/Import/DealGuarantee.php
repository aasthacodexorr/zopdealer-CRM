<?php

namespace Tenant\app\Libraries\Import;

use Tenant\app\Repositories\DealCommonDependentRepository;

class DealGuarantee extends ImportAbstract
{
    public function __construct()
    {
        $this->repository = new DealCommonDependentRepository();
        $this->repository->switchDealCommonDependentState('guarantee');
    }

    public function validate(array $data, array $namespaces = [], array $errors = []): array
    {
        try {
            if (intval($data['retail_or_wholesale_flag']) === 0) {
                if (!empty($data['guarantee']) && is_array($data['guarantee'])) {
                    foreach ($data['guarantee'] as $guarantee_key => $value) {
                        $entity_error = $this->repository->validate($value);

                        if (gettype($entity_error) == "array") {
                            $errors['guarantee']["guarantee_" . $guarantee_key] = $entity_error;
                        }
                    }
                }
            }
        } catch (\Exception $ex) {
            log_message('notice', $ex->getMessage());
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
        try {
            if (intval($data['retail_or_wholesale_flag']) === 0) {
                if (!empty($data['deal_id']) && is_array($data['guarantee'])) {
                    foreach ($data['guarantee'] as $guarantee_key => $guarantee) {
                        $guarantee['deal_id'] = $data['deal_id'];
                        $this->repository->create($guarantee);
                    }
                }
            }
        } catch (\Exception $ex) {
            log_message('notice', $ex->getMessage());
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
