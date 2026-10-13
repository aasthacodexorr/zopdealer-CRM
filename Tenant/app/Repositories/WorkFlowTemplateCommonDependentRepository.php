<?php

namespace Tenant\app\Repositories;

use App\Exceptions\ModelNotFoundException;
use App\Libraries\WorkFlow\WorkFlowConstant;
use App\Repositories\CRUDWithAdvanceRepository;
use Carbon\Carbon;
use CodeIgniter\Model;
use Tenant\app\Models\ActivityModel;
use Tenant\app\Models\WorkFlowTemplateStepActivityActionModel;
use Tenant\app\Models\WorkFlowTemplateStepEmailActionModel;
use Tenant\app\Models\WorkFlowTemplateStepModel;
use Tenant\app\Models\WorkFlowTemplateStepSMSActionModel;
use Tenant\app\Models\WorkJobModel;
use Tenant\app\Models\WorkJobProductModel;

/**
 * WorkFlowTemplateCommonDependentRepository
 */
class WorkFlowTemplateCommonDependentRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new WorkFlowTemplateStepModel());
        $this->entity = 'CodeIgniter\Entity';
        $this->entityLabel = 'Template Step';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'workflow_template_step_id';
    }

    public function create($data): array
    {
        $action = !empty($data['action']) ? $data['action'] : '';

        $nested_validations = $this->nestedModelValidation($action, $data);

        if (!empty($nested_validations)) {
            return $nested_validations;
        }

        $entity_instance = new $this->entity($data);

        if ($this->model->save($entity_instance) === false) {
            return $this->model->errors();
        } else {

            $insert_id = $this->model->insertID;

            $data['wft_step_id'] = $insert_id;
            $this->nestedModelSave($action, $data);
            return [
                'success' => $this->createSuccessMessage,
                $this->id_field => $insert_id,
            ];
        }
    }

    public function update($id, $data): array
    {
        $action = !empty($data['action']) ? $data['action'] : '';

        $nested_validations = $this->nestedModelValidation($action, $data);

        if (!empty($nested_validations)) {
            return $nested_validations;
        }

        $entity_instance = new $this->entity($data);
        $entity_instance->id = $id;

        if ($this->model->save($entity_instance) === false) {
            return $this->model->errors();
        } else {

            $data['wft_step_id'] = $id;
            unset($data['id']);
            $this->nestedModelSave($action, $data);
            return [
                "success" => $this->updateSuccessMessage,
                $this->id_field => $id,
            ];
        }
    }

    public function show($id): ?array
    {

        $current_entity = $this->model->asArray()->find($id);

        $action = !empty($current_entity['action']) ? $current_entity['action'] : '';

        if (!empty($current_entity['id'])) {
            $nested_entity = $this->nestedModelShow($action, $current_entity['id']);
            if (!empty($nested_entity)) {
                unset($nested_entity['id']);
                return array_merge($current_entity, $nested_entity);
            } else {
                return $current_entity;
            }
        } else {
            return $current_entity;
        }
    }


    /**
     * findStepByWorkFlowTemplateId
     *
     * @param  mixed $workflow_template_id
     * @param  mixed $limit
     * @param  mixed $offset
     * @return array
     */
    public function findStepDetailsByWorkFlowTemplateId($workflow_template_id, $limit = 1, $offset = 0): ?array
    {
        $step = $this->findStepByWorkFlowTemplateId($workflow_template_id, $limit, $offset);
        if (!empty($step)) {
            $step['details'] = $this->nestedModelShow($step['action'], $step['id']);
        }
        return $step;
    }



    /**
     * findStepByWorkFlowTemplateId
     *
     * @param  mixed $workflow_template_id
     * @param  mixed $limit
     * @param  mixed $offset
     * @return array
     */
    public function findStepByWorkFlowTemplateId($workflow_template_id, $limit = 1, $offset = 0): ?array
    {
        if ($this->isStep()) {
            return $this->model->where('workflow_template_id', $workflow_template_id)
                ->orderBy($this->model->table . '.' . 'index', 'ASC')->limit($limit, $offset)->asArray()->first();
        }
        return $this->model->where('workflow_template_id', $workflow_template_id)->limit($limit, $offset)->asArray()->first();
    }


    /**
     * findByWorkFlowTemplateId
     *
     * @param  string $workflow_template_id
     * @return array
     */
    public function findByWorkFlowTemplateId($workflow_template_id): ?array
    {
        if ($this->isStep()) {
            return $this->model->where('workflow_template_id', $workflow_template_id)
                ->orderBy($this->model->table . '.' . 'index', 'ASC')->asArray()->findAll();
        }

        return $this->model->where('workflow_template_id', $workflow_template_id)->asArray()->findAll();
    }


    /**
     * findByWorkFlowTemplateIdWithStepDetails
     *
     * @param  string $workflow_template_id
     * @return array
     */
    public function findByWorkFlowTemplateIdWithStepDetails($workflow_template_id): ?array
    {

        $steps = [];

        if ($this->isStep()) {
            $steps = $this->model->where('workflow_template_id', $workflow_template_id)
                ->orderBy($this->model->table . '.' . 'index', 'ASC')
                ->asArray()->findAll();
        } else {
            $steps = $this->model->where('workflow_template_id', $workflow_template_id)
                ->asArray()->findAll();
        }

        foreach ($steps as $key => $step) {
            $steps[$key]['details'] = $this->nestedModelShow($step['action'], $step['id']);
        }

        return $steps;
    }

    /**
     * findByWorkFlowTemplateStepCount
     *
     * @param  string $workflow_template_id
     * @return array
     */
    public function findByWorkFlowTemplateStepCount($workflow_template_id): ?int
    {
        return $this->model->where('workflow_template_id', $workflow_template_id)->asArray()->countAllResults();
    }

    /**
     * deleteByWorkFlowTemplateId
     *
     * @param  string $workflow_template_id
     * @return void
     */
    public function deleteByWorkFlowTemplateId($workflow_template_id)
    {
        $this->model->where('workflow_template_id', $workflow_template_id)->delete();
    }


    public function calculateDateTimeForWorkFlowTemplateStep(array $workflow_template_step, $current_datetime = NULL)
    {
        if (empty($current_datetime)) {
            $current_datetime = Carbon::now('UTC');
        }
        $calculated_datetime =  $current_datetime;

        $wait_time = intval($workflow_template_step['wait_time']);

        $wait_time_unit = $workflow_template_step['wait_time_unit'];


        switch ($wait_time_unit) {
            case WorkFlowConstant::MINUTES:
                $calculated_datetime = $current_datetime->copy()->addMinutes($wait_time);
                break;

            case WorkFlowConstant::HOURS:
                $calculated_datetime = $current_datetime->copy()->addHours($wait_time);
                break;

            case WorkFlowConstant::DAYS:
                $calculated_datetime = $current_datetime->copy()->addDays($wait_time);
                break;

            default:
                # code...
                break;
        }

        return $calculated_datetime;
    }

    /**
     * updateBatch
     *
     * @param  array $rows
     * @param  string $index
     * @return void
     */
    public function updateBatch(array $rows, string $index): void
    {
        $this->model->updateBatch($rows, $index);
    }

    /**
     * switchWorkFlowTemplateCommonDependentState This simply changes the Model based on the selection.
     *
     * @param  mixed $state
     * @return WorkFlowTemplateCommonDependentRepository
     */
    public function switchWorkFlowTemplateCommonDependentState(String $state): WorkFlowTemplateCommonDependentRepository
    {
        try {
            switch ($state) {
                case 'steps':
                    $this->model = new WorkFlowTemplateStepModel();
                    $this->entityLabel = 'Template Step';
                    $this->id_field = 'workflow_template_step_id';
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

    /**
     * nestedModelValidation
     *
     * @param  string $action
     * @param  array $data
     * @return array
     */
    private function nestedModelValidation(string $action, array $data): ?array
    {
        $errors = [];
        $nested_model = null;

        $current_model = $this->model->cleanRules()->validate($data);
        $current_model_errors = $this->model->cleanRules()->errors();
        //var_dump($current_model_errors);
        if (!empty($current_model_errors)) {
            $errors = array_merge($errors, $current_model_errors);
        }

        $nested_model = $this->getActionModel($action);

        if (!empty($nested_model)) {
            $nested_model->cleanRules()->validate($data);
            $nested_model_errors = $nested_model->cleanRules()->errors();

            if ($nested_model_errors) {
                $errors = array_merge($errors, $nested_model_errors);
            }
        }
        return $errors;
    }

    private function nestedModelSave(string $action, array $data): void
    {
        $nested_model = $this->getActionModel($action);

        if (!empty($nested_model)) {
            $entity_instance = new $this->entity($data);

            if (!empty($data['wft_step_id'])) {
                $existing_entity = $nested_model->where('wft_step_id', $data['wft_step_id'])->asArray()->first();
                if (!empty($existing_entity['id'])) {
                    $entity_instance->id = $existing_entity['id'];
                }
            }

            $nested_model->save($entity_instance);
        }
    }

    private function nestedModelShow(string $action, string $workflow_template_step_id): ?array
    {
        $nested_model = $this->getActionModel($action);
        if (!empty($nested_model)) {
            return $nested_model->where('wft_step_id', $workflow_template_step_id)->asArray()->first();
        } else {
            return null;
        }
    }

    /**
     * getActionModel
     *
     * @param  string $action
     * @return Model
     */
    private function getActionModel(string $action): ?Model
    {

        switch ($action) {
            case 'ACTIVITY':
                return new WorkFlowTemplateStepActivityActionModel();
                break;

            case 'EMAIL':
                return new WorkFlowTemplateStepEmailActionModel();
                break;

            case 'SMS':
                return new WorkFlowTemplateStepSMSActionModel();
                break;

            default:
                return null;
                break;
        }
    }

    private function isStep(): bool
    {
        return get_class($this->model) === "Tenant\app\Models\WorkFlowTemplateStepModel";
    }
}
