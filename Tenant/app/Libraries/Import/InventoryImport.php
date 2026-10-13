<?php

namespace Tenant\app\Libraries\Import;

use Tenant\app\Interfaces\ImportInterface;
use Tenant\app\Repositories\InventoryImportRepository;

class InventoryImport extends ImportAbstract
{

    public function __construct()
    {
        $this->repository = new InventoryImportRepository();
    }

    public function process(array $data, array $namespaces = [], array $response = []): array
    {
        $inventory_model = $this->repository->getModel();
        $fields = $inventory_model->__get('allowedFields');
        $keys = array_keys($data);

        $existing_inventory = $this->repository->findByStock($data['stock_no']);
        $inventory_id = $existing_inventory['inventory_id'] ?? null;

        if (count(array_intersect($fields, $keys)) >= 2) {
            if (empty($inventory_id)) {
                $new_inventory = $this->repository->create($data);

                if (!empty($new_inventory['inventory_id'])) {
                    $inventory_id = $new_inventory['inventory_id'];
                }
                $response = $new_inventory;
            } else {
                $data['inventory_id'] = $inventory_id;
                $response = $this->repository->update($inventory_id, $data);
            }
        }

        if (count($namespaces) > 0 && $inventory_id) {
            $data['inventory_id'] = $inventory_id;
            $import_entity_namespace = array_shift($namespaces);
            $import_instance = new $import_entity_namespace();
            return $import_instance->process($data, $namespaces, $response);
        } else {
            return $response;
        }
    }
}
