<?php

namespace Tenant\app\Libraries\Import;

use Tenant\app\Models\InventoryImportImageModel;
use Tenant\app\Repositories\InventoryImportRepository;
use Tenant\app\Repositories\InventoryRepository;

class InventoryImageImport extends ImportAbstract
{

    public function __construct()
    {
        $this->repository = new InventoryImportRepository();
        $this->repository->setModel(new InventoryImportImageModel());
    }

    public function process(array $data, array $namespaces = [], array $response = []): array
    {
        if (!empty($data['inventory_id']) && !empty($data['image_urls'])) {

            $separator = '|';

            $images = [];

            //import new images
            foreach (explode($separator, $data['image_urls']) as $key => $image_url) {

                $image_url = trim($image_url);

                if ($image_url === '') {
                    continue;
                }

                $images[] = [
                    'inventory_id' => $data['inventory_id'],
                    'image_url'     => $image_url,
                    'sort_rank'     => count($images) + 1,
                    'active'        => 1,
                ];
            }

            if (!empty($images)) {
                (new InventoryRepository())->deleteImagesPathByInventoryID($data['inventory_id']);
                $this->repository->getModel()->insertBatch($images);
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
