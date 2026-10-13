<?php

namespace Tenant\app\Libraries\Import;

use Tenant\app\Models\InventoryPurchaseModel;
use Tenant\app\Repositories\AttachmentRepository;
use Tenant\app\Repositories\InventoryPurchaseRepository;

class InventoryPurchaseImport extends ImportAbstract
{
    public function __construct()
    {
        $this->repository = new InventoryPurchaseRepository();
        $this->repository->setModel(new InventoryPurchaseModel());
    }

    public function validate(array $data, array $namespaces = [], array $errors = []): array
    {
        if (!empty($data['purchase']) && is_array($data['purchase'])) {
            $entity_error['purchase'] = $this->repository->validate($data['purchase']);

            if (gettype($entity_error['purchase']) == "array") {
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

        if (!empty($data['inventory_id']) && !empty($data['purchase']) && is_array($data['purchase'])) {
            $this->repository->deleteByInventoryId($data['inventory_id']);

            $data['purchase']['inventory_id'] = $data['inventory_id'];
            $saved_purchase = $this->repository->create($data['purchase']);

            if (!empty($saved_purchase['inventory_purchase_id']) && !empty($data['purchase']['url'])) {
                (new AttachmentRepository())->create(['entity_id' => $saved_purchase['inventory_purchase_id'], 'module' => "INVENTORY", "sub_module" => "PURCHASE", "url" => $data['purchase']['url']]);
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
