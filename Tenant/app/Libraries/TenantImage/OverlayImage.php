<?php

namespace Tenant\app\Libraries\TenantImage;

use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\InventoryRepository;

class OverlayImage extends TenantImageAbstract
{
    protected $current_type = TenantImageConstant::OVERLAY;

    public function postCreateProcessing(array $data = []): ?array
    {
        if (!empty($data['active']) && !empty($data['file_name'])) {
            $database = db_connect()->database;
            $folder = 'image';
            $file_name = $data['file_name'];
            $unprocessed_flag = "1";

            if (!empty($data['image_id'])) {
                $this->image_repository->getModel()->where('type', $this->current_type)->where('id !=', $data['image_id'])->set(['active' => '0'])->update();
            }

            (new InventoryRepository())->purgeDeleteInventoryImagesWithOverlay(false);
            exec("php index.php imageQueue send $database $folder $file_name $unprocessed_flag");
        }
        return parent::postCreateProcessing($data);
    }

    public function delete($id): ?array
    {
        $overlay_entity = $this->image_repository->getModel()->asArray()->find($id);
        if (!empty($overlay_entity['active'])) {
            (new InventoryRepository())->purgeDeleteInventoryImagesWithOverlay();
        }

        return parent::delete($id);
    }

    public function changeActivation($id, $activation_value): ?array
    {
        $overlay_entity = $this->image_repository->getModel()->asArray()->find($id);
        $is_active = !empty($activation_value);

        if ($is_active && $this->isZopSudioEnabled()) {
            return [
                'error' => 'Zop Studio is enabled. Overlay cannot be activated.'
            ];
        }

        if (intval($overlay_entity['active']) !== intval($activation_value)) {
            (new InventoryRepository())->purgeDeleteInventoryImagesWithOverlay(!$is_active);

            if ($is_active) {
                $this->image_repository->getModel()->where('type', $this->current_type)->where('id !=', $id)->set(['active' => '0'])->update();
                $this->image_repository->getModel()->where('id', $id)->set(['active' => $activation_value])->update();

                $database = db_connect()->database;
                $folder = 'image';
                $image_url = $overlay_entity['image_url'];
                $pos = strrpos($image_url, '/', -1);
                $file_name = substr($image_url, $pos + 1);
                $unprocessed_flag = "1";

                exec("php index.php imageQueue send $database $folder $file_name $unprocessed_flag");
            }
        }

        return parent::changeActivation($id, $activation_value);
    }

    private function isZopSudioEnabled(): bool
    {
        $enable_zop_studio_config = (new ConfigurationRepository())->findConfigurationColumnsByKeyAsArray('enable_zop_studio', ['value']);
        return !empty($enable_zop_studio_config['value']);
    }
}
