<?php

namespace Tenant\app\Repositories;

use App\Libraries\CloudStorage\CloudStorageFactory;
use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\DigitalVehicleInspectionItemAttachmentModel;
use Tenant\app\Models\DigitalVehicleInspectionItemModel;

class DigitalVehicleInspectionItemRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new DigitalVehicleInspectionItemModel());
        $this->entity = 'CodeIgniter\Entity';
        $this->entityLabel = 'DVI Template Item';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'digital_vehicle_inspection_item_id';
    }

    public function saveAttachment($data): array
    {
        $entity_instance = new $this->entity($data);
        $model = new DigitalVehicleInspectionItemAttachmentModel();

        if ($model->save($entity_instance) === false) {
            return $model->errors();
        } else {

            $insert_id = $model->insertID;
            return [
                'success' => 'Attachment has been saved.',
                'dvi_item_attachment_id' => $insert_id,
            ];
        }
    }

    public function getAllAttachments($item_id): ?array
    {
        $model = new DigitalVehicleInspectionItemAttachmentModel();
        return $model->where('dvi_item_id', $item_id)->orderBy('id', 'ASC')->asArray()->findAll();
    }

    public function deleteAttachment($attachment_id)
    {
        $model = new DigitalVehicleInspectionItemAttachmentModel();
        $entity = $model->asArray()->find($attachment_id);

        if (!empty($entity['url'])) {
            CloudStorageFactory::getInstance()->delete($entity['url']);
        }

        return $model->delete($attachment_id);
    }
}
