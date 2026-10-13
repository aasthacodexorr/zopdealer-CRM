<?php

namespace Tenant\app\Repositories;

use App\Helpers\TenantNonPersistantCache;
use App\Repositories\BaseRepository;
use Exception;
use Tenant\app\Models\ImageModel;
use Tenant\app\Entities\Image;
use Tenant\app\Libraries\TenantImage\OverlayImage;
use Tenant\app\Libraries\TenantImage\PlaceholderImage;
use Tenant\app\Libraries\TenantImage\StaticImage;
use Tenant\app\Libraries\TenantImage\TenantImageAbstract;
use Tenant\app\Libraries\TenantImage\TenantImageConstant;

/**
 * ImageRepository CRUD and pagination for the Image Items
 */
class ImageRepository extends BaseRepository
{

    /**
     * tenant_image_type
     *
     * @var TenantImageAbstract
     */
    protected $tenant_image_type;
    public function __construct()
    {
        parent::__construct(new ImageModel());
    }


    public function index(): ?array
    {
        return $this->tenant_image_type->index();
    }

    public function create($data): ?array
    {
        $image = new Image($data);

        if ($this->model->save($image) === false) {
            return $this->model->errors();
        } else {
            $image_id = $this->model->insertID;
            $data['image_id'] = $image_id;
            $post_create_processing = $this->switchImageType($data['type'])->tenant_image_type->postCreateProcessing($data);
            $post_create_processing['image_id'] = $image_id;
            return $post_create_processing;
        }
    }

    public function delete($id): ?array
    {
        $type = $this->model->find($id)->type;
        return $this->switchImageType($type)->tenant_image_type->delete($id);
    }


    public function changeActivation($id, $activation_value)
    {
        $type = $this->model->find($id)->type;
        return $this->switchImageType($type)->tenant_image_type->changeActivation($id, $activation_value);
    }

    public function getPlaceholder(): ?array
    {
        $placeholder = $this->switchImageType(TenantImageConstant::PLACEHOLDER)->tenant_image_type->getActiveImages();
        if (count($placeholder) > 0) {
            return $placeholder[0];
        }

        return null;
    }


    public function getActiveOverlayImages(): ?array
    {
        return $this->switchImageType(TenantImageConstant::OVERLAY)->tenant_image_type->getActiveImages();
    }

    public function getAllActiveStaticImages(): ?array
    {
        return $this->switchImageType(TenantImageConstant::STATIC)->tenant_image_type->getActiveImages();
    }

    public function switchImageType(String $tenant_image_type = ''): self
    {
        switch ($tenant_image_type) {
            case TenantImageConstant::OVERLAY:
                $this->tenant_image_type = new OverlayImage();
                break;

            case TenantImageConstant::STATIC:
                $this->tenant_image_type = new StaticImage();
                break;

            case TenantImageConstant::PLACEHOLDER:
                $this->tenant_image_type = new PlaceholderImage();
                break;

            default:
                throw new Exception("Unkown Image Type", 1);
                break;
        }

        return $this;
    }
}
