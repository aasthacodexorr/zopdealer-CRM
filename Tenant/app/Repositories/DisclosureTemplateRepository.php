<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\DisclosureTemplateModel;

class DisclosureTemplateRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new DisclosureTemplateModel());
        $this->entity = 'CodeIgniter\Entity';
        $this->entityLabel = 'Disclosure Template';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'disclosure_template_id';
    }
}
