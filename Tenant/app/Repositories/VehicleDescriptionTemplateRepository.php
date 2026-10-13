<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\VehicleDescriptionTemplateModel;

class VehicleDescriptionTemplateRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new VehicleDescriptionTemplateModel());
        $this->entity = 'CodeIgniter\Entity';
        $this->entityLabel = 'Vehicle Description Template';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'vehicle_description_template_id';
    }
}
