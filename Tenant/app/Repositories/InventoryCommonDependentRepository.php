<?php

namespace Tenant\app\Repositories;

use App\Exceptions\ModelNotFoundException;
use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\InventoryDigitalRetailingModel;
use Tenant\app\Models\InventoryDisclosureModel;
use Tenant\app\Models\InventoryInternalInspectionModel;

class InventoryCommonDependentRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new InventoryDisclosureModel());
        $this->entity = 'Tenant\app\Entities\DealTradeInDisclosure';
        $this->entityLabel = 'Inventory Disclosure';
        $this->createSuccessMessage = $this->entityLabel . ' has been added.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'inventory_disclosure_id';
    }

    public function findByInventoryId($inventory_id)
    {
        return $this->model->where('inventory_id', $inventory_id)->findAll();
    }

    public function filterByInventoryId($inventory_id)
    {
        $this->model->where('inventory_id', $inventory_id);
        return $this;
    }

    public function deleteWithoutKey()
    {
        $this->model->delete();
    }

    public function filterByInventoryIdAsArray($inventory_id)
    {
        return $this->model->where('inventory_id', $inventory_id)->asArray()->findAll();
    }

    /**
     * switchInventoryCommonDependentState This simply changes the Model based on the selection.
     *
     * @param  mixed $state
     * @return InventoryCommonDependentRepository
     */
    public function switchInventoryCommonDependentState(String $state): InventoryCommonDependentRepository
    {
        try {
            switch ($state) {
                case 'disclosures':
                    $this->model = new InventoryDisclosureModel();
                    $this->entityLabel = 'Inventory Disclosure';
                    $this->id_field = 'inventory_disclosure_id';
                    $this->entity = 'Tenant\app\Entities\DealTradeInDisclosure';
                    break;

                case 'internal-inspection':
                    $this->model = new InventoryInternalInspectionModel();
                    $this->entityLabel = 'Internal Inspection';
                    $this->id_field = 'inventory_internal_inspection_id';
                    $this->entity = 'CodeIgniter\Entity';
                    break;

                case 'digital-retailing':
                    $this->model = new InventoryDigitalRetailingModel();
                    $this->entityLabel = 'Digital Retail';
                    $this->id_field = 'inventory_digital_retailing_id';
                    $this->entity = 'CodeIgniter\Entity';
                    break;

                default:
                    throw new ModelNotFoundException();
                    break;
            }
        } catch (ModelNotFoundException $e) {
            echo $e->errorMessage();
        }

        $this->createSuccessMessage = $this->entityLabel . ' has been added.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        return $this;
    }

    public function bulkDelete()
    {
        $this->model->delete();
    }
}
