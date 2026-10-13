<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\InventoryModel;

/**
 * InventoryRepositoryV2 CRUD and pagination for the Inventory
 */
class InventoryRepositoryV2 extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new InventoryModel());
        $this->entity = 'Tenant\app\Entities\Inventory';
        $this->entityLabel = 'Inventory';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'inventory_id';
        $this->audit_log_module = 'inventory';
        $this->audit_entity_type = 'inventory';
    }

    /**
     * multipleUpdates Update multiple lead responses by ids.
     *
     * @param mixed $ids
     * @param mixed $data
     * @return void
     * @throws \ReflectionException
     */
    public function multipleUpdates(array $ids, $data)
    {
        $keys = array_keys($data);
        $keys = array_filter($keys, function ($v, $k) {
            return $k != 'inventory_ids';
        }, ARRAY_FILTER_USE_BOTH);
        $keys[] = $this->id_field;

        $this->selectAttributes($keys);
        $original_data = $this->model->asArray()->find(array_values($ids));
        if ($this->model->update($ids, $data)) {
            $this->selectAttributes($keys);
            $updated_data = $this->model->asArray()->find(array_values($ids));
            foreach ($updated_data as $key => $updated_entity_value) {

                if ($original_data[$key][$this->id_field] === $updated_entity_value[$this->id_field]) {
                    $this->addLogOnUpdate($updated_entity_value[$this->id_field], $original_data[$key], $updated_data[$key], 'bulk.updated');
                }
            }
        }
    }

    /**
     * multipleDeletes Deletes multiple lead responses.
     *
     * @param mixed $ids
     * @return void
     */
    public function multipleDeletes(array $ids): void
    {
        $this->model->delete($ids);
        if (is_array($ids)) {
            foreach ($ids as $id) {
                $this->addLogOnCreateOrDelete($id, 'bulk.deleted');
            }
        }
        return;
    }

    private function selectAttributes(array $fields): self
    {
        $this->model->select($fields);
        return $this;
    }
}
