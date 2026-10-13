<?php

namespace Tenant\app\Libraries\Import;

use Tenant\app\Repositories\DealTradeInRepository;
use Tenant\app\Repositories\InventoryRepository;

class DealTradeIn extends ImportAbstract
{
    public function __construct()
    {
        $this->repository = new DealTradeInRepository();
    }

    public function validate(array $data, array $namespaces = [], array $errors = []): array
    {
        if (intval($data['retail_or_wholesale_flag']) === 0 && !empty($data['trade_in'])) {
            $entity_error['trade_in'] = $this->repository->validate($data['trade_in']);

            if (gettype($entity_error['trade_in']) == "array") {
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
        if (intval($data['retail_or_wholesale_flag']) === 0 && !empty($data['trade_in'])) {
            if (!empty($data['deal_id']) && is_array($data['trade_in'])) {
                $data['trade_in']['deal_id'] = $data['deal_id'];
                $data['trade_in']['stock_no'] = !empty($data['stock_no']) ? $data['stock_no'] : (new InventoryRepository())->getAvailableStockNumber($data['trade_in']);
                $this->repository->create($data['trade_in']);
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
