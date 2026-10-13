<?php

namespace Tenant\app\Libraries\Import;

use Tenant\app\Repositories\DealCommonDependentRepository;

class DealInsurance extends ImportAbstract
{
    public function __construct()
    {
        $this->repository = new DealCommonDependentRepository();
        $this->repository->switchDealCommonDependentState('insurances');
    }

    public function validate(array $data, array $namespaces = [], array $errors = []): array
    {
        try {
            if (intval($data['retail_or_wholesale_flag']) === 0) {
                if (!empty($data['insurances']) && is_array($data['insurances'])) {
                    foreach ($data['insurances'] as $insurance_key => $value) {
                        $entity_error = $this->repository->validate($value);

                        if (gettype($entity_error) == "array") {
                            $errors['insurances']["insurance_" . $insurance_key] = $entity_error;
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
                if (!empty($data['deal_id']) && is_array($data['insurances'])) {
                    foreach ($data['insurances'] as $insurance_key => $insurance) {
                        $insurance['deal_id'] = $data['deal_id'];
                        $this->repository->create($insurance);
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
