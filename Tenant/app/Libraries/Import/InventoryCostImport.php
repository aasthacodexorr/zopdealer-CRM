<?php

namespace Tenant\app\Libraries\Import;

use Tenant\app\Models\InventoryCostModel;
use Tenant\app\Repositories\InventoryCostRepository;

class InventoryCostImport extends ImportAbstract
{
    public function __construct()
    {
        $this->repository = new InventoryCostRepository();
        $this->repository->setModel(new InventoryCostModel());
    }

    public function validate(array $data, array $namespaces = [], array $errors = []): array
    {
        if (!empty($data['expenses']) && is_array($data['expenses'])) {
            foreach ($data['expenses'] as $expense_key => $value) {

                $this->repository->setModel(new InventoryCostModel());
                $entity_error = $this->repository->validate($value);

                if (gettype($entity_error) == "array") {
                    $errors['expenses']["expense_" . $expense_key] = $entity_error;
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

        if (!empty($data['inventory_id']) && !empty($data['expenses']) && is_array($data['expenses'])) {

            $this->repository->deleteByInventoryId($data['inventory_id']);
            
            foreach ($data['expenses'] as $expense_key => $expense) {
                $expense['inventory_id'] = $data['inventory_id'];
                $this->repository->setModel(new InventoryCostModel());
                $this->repository->create($expense);
            }
        }

        if (count($namespaces) > 0 && !empty($data['inventory_id'])) {
            $import_entity_namespace = array_shift($namespaces);
            $import_instance = new $import_entity_namespace();
            return $import_instance->process($data, $namespaces, $response);
        } else {
            return $response;
        }
    }
}
