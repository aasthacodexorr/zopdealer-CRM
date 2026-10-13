<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\InventoryImportModel;

class InventoryImportRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new InventoryImportModel());
        $this->entity = 'Tenant\app\Entities\InventoryImport';
        $this->entityLabel = 'Inventory';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'inventory_id';
        $this->audit_log_module = 'inventory';
        $this->audit_entity_type = 'inventory';
    }

    public function findByStock($stock_no){
        return $this->model->where('stock_no', $stock_no)->asArray()->first();
    }
}
