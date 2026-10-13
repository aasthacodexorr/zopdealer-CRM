<?php

namespace Tenant\app\Libraries\Import;

use Tenant\app\Models\DealInventoryPivotModel;
use Tenant\app\Repositories\DealRepository;
use Tenant\app\Repositories\InventoryImportRepository;
use Tenant\app\Repositories\InventoryRepository;

class DealInventory extends ImportAbstract
{
    public function __construct()
    {
        $this->repository = new InventoryImportRepository();
    }

    public function validate(array $data, array $namespaces = [], array $errors = []): array
    {
        if (!empty($data['inventory'])) {
            $data['inventory']['stock_no'] = !empty($data['stock_no']) ? $data['stock_no'] : (new InventoryRepository())->getAvailableStockNumber($data['inventory']);
            $entity_error['inventory'] = $this->repository->validate($data['inventory']);

            if (gettype($entity_error['inventory']) == "array") {
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
            if (!empty($data['deal_id']) && !empty($data['inventory'])) {
                $data['inventory']['stock_no'] = !empty($data['stock_no']) ? $data['stock_no'] : (new InventoryRepository())->getAvailableStockNumber($data['inventory']);
                $saved_inventory = $this->repository->create($data['inventory']);
                if (!empty($saved_inventory['inventory_id'])) {
                    $data['stock_no'] = $data['inventory']['stock_no'];
                }

                if (!empty($data['inventory']['odometer'])) {
                    $data['odometer'] = $data['inventory']['odometer'];
                }
            }

            if (!empty($data['stock_no']) && !empty($data['deal_id'])) {

                $odometer = !empty($data['odometer']) ? $data['odometer'] : '';
                $deal_repository = (new DealRepository());
                $deal_repository->setModel(new DealInventoryPivotModel());
                $deal_repository->create([
                    'deal_id' => $data['deal_id'],
                    'stock_no' => $data['stock_no'],
                    'odometer' => $odometer,
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
