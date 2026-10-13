<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\DigitalVehicleInspectionTemplateItemModel;

class DigitalVehicleInspectionTemplateItemRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new DigitalVehicleInspectionTemplateItemModel());
        $this->entity = 'CodeIgniter\Entity';
        $this->entityLabel = 'DVI Template Item';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'digital_vehicle_inspection_template_item_id';
    }

    public function multipleDeletes(array $ids)
    {
        return $this->model->delete($ids);
    }
}
