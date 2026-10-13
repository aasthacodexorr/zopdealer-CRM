<?php

namespace Tenant\app\Libraries\Import;

use Tenant\app\Models\DealCustomerPivotModel;
use Tenant\app\Repositories\CustomerRepository;
use Tenant\app\Repositories\DealRepository;

class DealCustomer extends ImportAbstract
{
    public function __construct()
    {
        $this->repository = new CustomerRepository();
    }

    public function validate(array $data, array $namespaces = [], array $errors = []): array
    {
        if (!empty($data['primary_customer'])) {
            $entity_error['primary_customer'] = $this->repository->validate($data['primary_customer']);

            if (gettype($entity_error['primary_customer']) == "array") {
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
        try {
            if (!empty($data['deal_id']) && !empty($data['primary_customer'])) {

                $saved_customer = $this->repository->create($data['primary_customer']);
                if (!empty($saved_customer['customer_id'])) {
                    $data['customer_id'] = $saved_customer['customer_id'];
                }
            }

            if (!empty($data['customer_id']) && !empty($data['deal_id'])) {
                $deal_repository = (new DealRepository());
                $deal_repository->setModel(new DealCustomerPivotModel());
                $deal_repository->create([
                    'deal_id' => $data['deal_id'],
                    'customer_id' => $data['customer_id'],
                    'customer_type' => 'primary_customer'
                ]);
            }
        } catch (\Exception $ex) {
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
