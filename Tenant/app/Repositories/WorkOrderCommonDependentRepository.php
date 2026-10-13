<?php

namespace Tenant\app\Repositories;

use App\Exceptions\ModelNotFoundException;
use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\WorkOrderCustomerPivotModel;
use Tenant\app\Models\WorkOrderInventoryPivotModel;
use Tenant\app\Models\WorkOrderModel;

/**
 * WorkOrderCommonDependentRepository
 */
class WorkOrderCommonDependentRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new WorkOrderModel());
        $this->entity = 'Tenant\app\Entities\WorkOrder';
        $this->entityLabel = 'Work Order';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'work_order_id';
    }

    /**
     * findByWorkOrderId
     *
     * @param  string $work_order_id
     * @return array
     */
    public function findByWorkOrderId($work_order_id): ?array
    {
        return $this->model->where('work_order_id', $work_order_id)->asArray()->findAll();
    }

    /**
     * deleteByWorkOrderId
     *
     * @param  string $work_order_id
     * @return void
     */
    public function deleteByWorkOrderId($work_order_id)
    {
        $this->model->where('work_order_id', $work_order_id)->delete();
    }

    /**
     * switchWorkOrderCommonDependentState This simply changes the Model based on the selection.
     *
     * @param  mixed $state
     * @return WorkOrderCommonDependentRepository
     */
    public function switchWorkOrderCommonDependentState(String $state): WorkOrderCommonDependentRepository
    {
        try {
            switch ($state) {
                case 'inventory':
                    $this->model = new WorkOrderInventoryPivotModel();
                    $this->entityLabel = 'Work Order Inventory';
                    $this->id_field = 'work_order_inventory_id';
                    $this->entity = 'CodeIgniter\Entity';
                    break;

                case 'customer':
                    $this->model = new WorkOrderCustomerPivotModel();
                    $this->entityLabel = 'Work Order Customer';
                    $this->id_field = 'work_order_customer_id';
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
}
