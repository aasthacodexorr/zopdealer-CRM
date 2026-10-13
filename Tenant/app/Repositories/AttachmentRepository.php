<?php

namespace Tenant\app\Repositories;

use App\Libraries\CloudStorage\CloudStorageFactory;
use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\AttachmentModel;

class AttachmentRepository extends CRUDWithAdvanceRepository
{

    public function __construct()
    {
        parent::__construct(new AttachmentModel());
        $this->entity = 'CodeIgniter\Entity';
        $this->entityLabel = 'Attachment';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'attachment_id';
    }

    /**
     * findByEntityDetails
     *
     * @param  mixed $module
     * @param  mixed $sub_module
     * @param  mixed $entity_id
     * @return array
     */
    public function findByEntityDetails(String $module, String $sub_module = "", String $entity_id): ?array
    {
        $this->filterData("module", $module);
        if (!empty($sub_module)) {
            $this->filterData("sub_module", $sub_module);
        }

        $this->filterData("entity_id", $entity_id);
        return $this->model->asArray()->first();
    }

    public function deleteByUrl(String $url)
    {
        $this->filterData("url", $url);
        $entity = $this->model->asArray()->first();

        if (!empty($entity['url']) && !empty($entity['id']) ) {
            if (gettype($entity['url']) == "string") {
                CloudStorageFactory::getInstance()->delete($entity['url']);
                $this->model->delete($entity['id']);
            }
        }
    }
}
