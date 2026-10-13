<?php

namespace Tenant\app\Libraries\TenantImage;

use Tenant\app\Repositories\InventoryRepository;

class StaticImage extends TenantImageAbstract
{
    protected $current_type = TenantImageConstant::STATIC;

    public function postCreateProcessing(array $data = []): ?array
    {
        if (!empty($data['active'])) {
            $this->deleteCache();
            (new InventoryRepository())->updateTypesenseIndexService([]);
        }
        return parent::postCreateProcessing($data);
    }

    public function delete($id): ?array
    {
        $static_entity = $this->image_repository->getModel()->asArray()->find($id);
        if (!empty($static_entity['active'])) {
            $this->image_repository->getModel()->delete($id);
            $this->deleteCache();
            (new InventoryRepository())->updateTypesenseIndexService([]);
        }

        return parent::delete($id);
    }

    public function changeActivation($id, $activation_value): ?array
    {
        $overlay_entity = $this->image_repository->getModel()->asArray()->find($id);
        $is_active = !empty($activation_value);

        if (intval($overlay_entity['active']) !== intval($activation_value)) {
            $this->image_repository->getModel()->where('id', $id)->set(['active' => $activation_value])->update();
            $this->deleteCache();
            (new InventoryRepository())->updateTypesenseIndexService([]);
        }

        return parent::changeActivation($id, $activation_value);
    }
}
