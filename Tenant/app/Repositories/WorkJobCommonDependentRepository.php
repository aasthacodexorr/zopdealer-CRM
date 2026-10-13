<?php

namespace Tenant\app\Repositories;

use App\Exceptions\ModelNotFoundException;
use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\WorkJobModel;
use Tenant\app\Models\WorkJobProductModel;
use Tenant\app\Models\WorkOrderCustomerPivotModel;
use Tenant\app\Models\WorkOrderInventoryPivotModel;
use Tenant\app\Models\WorkOrderModel;

/**
 * WorkJobCommonDependentRepository
 */
class WorkJobCommonDependentRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new WorkJobModel());
        $this->entity = 'CodeIgniter\Entity';
        $this->entityLabel = 'Work Job';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'work_job_id';
    }

    /**
     * findByWorkJobId
     *
     * @param  string $work_job_id
     * @return array
     */
    public function findByWorkJobId($work_job_id): ?array
    {
        return $this->model->where('work_job_id', $work_job_id)->asArray()->findAll();
    }

    /**
     * deleteByWorkJobId
     *
     * @param  string $work_job_id
     * @return void
     */
    public function deleteByWorkJobId($work_job_id)
    {
        $this->model->where('work_job_id', $work_job_id)->delete();
    }

    /**
     * switchWorkJobCommonDependentState This simply changes the Model based on the selection.
     *
     * @param  mixed $state
     * @return WorkJobCommonDependentRepository
     */
    public function switchWorkJobCommonDependentState(String $state): WorkJobCommonDependentRepository
    {
        try {
            switch ($state) {
                case 'products':
                    $this->model = new WorkJobProductModel();
                    $this->entityLabel = 'Work Job Product';
                    $this->id_field = 'work_job_product_id';
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
