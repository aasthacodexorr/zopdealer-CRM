<?php

namespace Tenant\app\Libraries\Import;

use Tenant\app\Models\InventoryImportFileModel;
use Tenant\app\Repositories\InventoryImportRepository;
use Tenant\app\Repositories\InventoryRepository;

class InventoryFileImport extends ImportAbstract
{
    public function __construct()
    {
        $this->repository = new InventoryImportRepository();
        $this->repository->setModel(new InventoryImportFileModel());
    }

    public function process(array $data, array $namespaces = [], array $response = []): array
    {

        if (!empty($data['inventory_id']) && !empty($data['vehicle_history_report'])) {
            (new InventoryRepository())->deleteImportFilePath('Vehicle History Report', $data['inventory_id']);
            $this->repository->create([
                'inventory_id' => $data['inventory_id'],
                'file_url'  => trim($data['vehicle_history_report']),
                'header'  => 'Vehicle History Report',
                'file'  => 0
            ]);
        }

        if (!empty($data['inventory_id']) && !empty($data['video_url'])) {
            (new InventoryRepository())->deleteImportFilePath('Video Url', $data['inventory_id']);
            $this->repository->create([
                'inventory_id' => $data['inventory_id'],
                'file_url'  => trim($data['video_url']),
                'header'  => 'Video Url',
                'file'  => 0
            ]);
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
