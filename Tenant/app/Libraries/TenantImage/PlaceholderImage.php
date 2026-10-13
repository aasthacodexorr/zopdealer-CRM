<?php

namespace Tenant\app\Libraries\TenantImage;

class PlaceholderImage extends TenantImageAbstract
{
    protected $current_type = TenantImageConstant::PLACEHOLDER;

    public function postCreateProcessing(array $data = []): ?array
    {
        if (!empty($data['active']) && !empty($data['image_id'])) {

            $this->image_repository->getModel()->where('type', $this->current_type)->where('id !=', $data['image_id'])->set(['active' => '0'])->update();
        }
        return parent::postCreateProcessing($data);
    }

    public function delete($id): ?array
    {
        $overlay_entity = $this->image_repository->getModel()->asArray()->find($id);
        if (!empty($overlay_entity['active'])) {
            return
                ["error" => 'Default Placeholder cannot be deleted.'];
        }

        return parent::delete($id);
    }

    public function changeActivation($id, $activation_value): ?array
    {
        $overlay_entity = $this->image_repository->getModel()->asArray()->find($id);

        if (intval($overlay_entity['active']) !== intval($activation_value)) {
            if (!empty($overlay_entity['active'])) {
                return
                    ["error" => 'Default Placeholder cannot be deactivated. Enable another image as placeholder.'];
            }

            if (!empty($activation_value)) {
                $this->image_repository->getModel()->where('type', $this->current_type)->where('id !=', $id)->set(['active' => '0'])->update();
                $this->image_repository->getModel()->where('id', $id)->set(['active' => $activation_value])->update();
            }
        }

        return parent::changeActivation($id, $activation_value);
    }
}
