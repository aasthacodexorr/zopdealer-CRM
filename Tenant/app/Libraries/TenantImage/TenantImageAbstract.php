<?php

namespace Tenant\app\Libraries\TenantImage;

use App\Helpers\TenantNonPersistantCache;
use Tenant\app\Repositories\ImageRepository;

abstract class TenantImageAbstract implements TenantImageInterface
{

    protected $current_type = '';

    protected ImageRepository $image_repository;

    public function __construct()
    {
        $this->image_repository = new ImageRepository();
    }

    public function index(): ?array
    {
        $items = 3;
        $this->image_repository->getModel()->orderBy('id', 'DESC');
        $this->image_repository->getModel()->where('type', $this->current_type);

        return  [
            'images' => $this->image_repository->getModel()->asObject()->paginate($items),
            'pager' => $this->image_repository->getModel()->pager->getDetails(),
        ];
    }

    public function postCreateProcessing(array $data = []): ?array
    {
        $this->deleteCache();
        return [
            'success' => ucfirst($this->current_type) . ' image has been added'
        ];
    }

    public function delete($id): ?array
    {
        $this->deleteCache();
        $this->image_repository->getModel()->delete($id);
        return  [
            'success' => ucfirst($this->current_type) . ' image has been deleted'
        ];
    }

    public function changeActivation($id, $activation_value): ?array
    {

        $this->deleteCache();
        $this->image_repository->getModel()->where('id', $id)->set(['active' => $activation_value])->update();

        $activation_message = !empty($activation_value) ? 'enabled' : 'disabled';
        return  [
            'success' => ucfirst($this->current_type) . ' image has been ' . $activation_message
        ];
    }

    public function getActiveImages(): ?array
    {
        $cached_config = TenantNonPersistantCache::get($this->current_type);

        if (empty($cached_config)) {
            $images = $this->image_repository->getModel()
                ->orderBy('index', 'ASC')
                ->where('type', $this->current_type)
                ->where('active', TenantImageConstant::ACTIVE)
                ->asArray()->findAll();

            TenantNonPersistantCache::save($this->current_type, $images, 300);
        }

        return TenantNonPersistantCache::get($this->current_type);
    }

    protected function deleteCache(): void
    {
        try {
            if (!empty($this->current_type)) {
                TenantNonPersistantCache::delete($this->current_type);
            }
        } catch (\Exception $ex) {
            log_message('notice', $ex->getMessage());
        }
    }
}
