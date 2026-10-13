<?php

namespace Tenant\app\Libraries\Import;

use Tenant\app\Repositories\DealRepository;

class DealImport extends ImportAbstract
{

    public function __construct()
    {
        $this->repository = new DealRepository();
    }


    public function process(array $data, array $namespaces = [], array $response = []): array
    {

        $response = $this->repository->create($data);

        if (!empty($response['deal_id'])) {
            $data['deal_id'] = $response['deal_id'];
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
