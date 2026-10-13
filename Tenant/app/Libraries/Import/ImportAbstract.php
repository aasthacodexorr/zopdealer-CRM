<?php

namespace Tenant\app\Libraries\Import;

use Tenant\app\Interfaces\ImportInterface;

abstract class ImportAbstract implements ImportInterface
{
    /**
     * repository
     *
     * @var mixed
     */
    protected $repository;

    public function validate(array $data, array $namespaces = [], array $errors = []): array
    {
        $entity_error = $this->repository->validate($data);

        if (gettype($entity_error) == "array") {
            $errors = array_merge($entity_error, $errors);
        }

        if (count($namespaces) > 0) {
            $import_entity_namespace = array_shift($namespaces);
            $import_instance = new $import_entity_namespace();
            return $import_instance->validate($data, $namespaces, $errors);
        } else {
            return $errors;
        }
    }
}
