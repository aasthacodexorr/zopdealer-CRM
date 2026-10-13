<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\InventoryWebInspectionModel;
use Carbon\Carbon;

class InventoryWebInspectionRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new InventoryWebInspectionModel());
        $this->entity = 'App\Entities\MasterEntity';
        $this->entityLabel = 'Inventory Vehicle Inspection';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'inventory_vehicle_inspection_id';
    }

    /**
     * findByInventoryId search Inventory Vehicle Inspection By Id
     *
     * @param  mixed $inventory_id
     * @return void
     */
    public function findByInventoryId($inventory_id)
    {
        return $this->model->where('inventory_id', $inventory_id)->orderBy('created_at', 'DESC')->asArray()->first();
    }
}
