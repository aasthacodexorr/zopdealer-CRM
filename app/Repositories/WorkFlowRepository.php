<?php

namespace App\Repositories;

use App\Libraries\WorkFlow\WorkFlowConstant;
use App\Models\WorkFlowModel;
use Carbon\Carbon;

/**
 * WorkFlowRepository CRUD for wordpressJobView Model
 */
class WorkFlowRepository extends CRUDWithAdvanceRepository
{

    public function __construct()
    {
        parent::__construct(new WorkFlowModel());
        $this->entity = 'CodeIgniter\Entity';
        $this->entityLabel = 'WorkFlow';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'workflow_id';
    }

    public function scheduleActiveWorkFlow()
    {

        $current = Carbon::now('UTC');

        $future_minutes = $current->copy()->addMinutes(0);

        $this->model->where($this->model->table . '.' . 'schedule' . " <= '" . $future_minutes . "' ");

        $conditions = [
            [
                'header' => null,
                'column' => 'status',
                'join' => 'AND',
                'comparator' => "is",
                "type" => "Single Select",
                'value_1' => WorkFlowConstant::ACTIVE,
                'value_2' => null
            ]
        ];

        return parent::index(null, null, null, json_encode($conditions), 20, null, false);
    }

    public function deleteWithoutKey()
    {
        $this->model->delete();
    }
}
