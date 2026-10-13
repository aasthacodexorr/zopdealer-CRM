<?php

namespace App\Repositories;

use App\Repositories\AdvanceRepository;
use App\Repositories\CRUDRepositoryInterface;

/**
 * CRUDWithAdvanceRepository This class has the CRUD classes for creating the CRUD with Filters.
 */
abstract class CRUDWithAdvanceRepository extends AdvanceRepository implements CRUDRepositoryInterface
{
    /**
     * entity This variable contains the path of entity class.
     *
     * @var mixed
     */
    protected $entity;

    /**
     * entityLabel label used for the response.
     *
     * @var mixed
     */
    protected $entityLabel;

    /**
     * createSuccessMessage This is returned on successful creation of entry for the entity.
     *
     * @var mixed
     */
    protected $createSuccessMessage;

    /**
     * updateSuccessMessage This is returned on successful updation of entry for the entity.
     *
     * @var mixed
     */
    protected $updateSuccessMessage;

    /**
     * id_field This is used as the fields name when the id is retrieved.
     *
     * @var mixed
     */
    protected $id_field;
    protected ?string $audit_log_module = null;
    protected ?string $audit_sub_module = null;
    protected ?string $audit_entity_type = null;


    /**
     * Set the value of id_field
     *
     * @return  self
     */
    public function setId_field($id_field)
    {
        $this->id_field = $id_field;

        return $this;
    }


    /**
     * Set updateSuccessMessage This is returned on successful updation of entry for the entity.
     *
     * @param  mixed  $updateSuccessMessage  updateSuccessMessage This is returned on successful updation of entry for the entity.
     *
     * @return  self
     */
    public function setUpdateSuccessMessage($updateSuccessMessage)
    {
        $this->updateSuccessMessage = $updateSuccessMessage;

        return $this;
    }

    /**
     * Set createSuccessMessage This is returned on successful creation of entry for the entity.
     *
     * @param  mixed  $createSuccessMessage  createSuccessMessage This is returned on successful creation of entry for the entity.
     *
     * @return  self
     */
    public function setCreateSuccessMessage($createSuccessMessage)
    {
        $this->createSuccessMessage = $createSuccessMessage;

        return $this;
    }

    /**
     * show This shows single instance of entity
     *
     * @param  mixed $id
     * @return array
     */
    public function show($id): ?array
    {

        return $this->model->asArray()->find($id);
    }

    /**
     * create new entity.
     *
     * @param  mixed $data
     * @return array
     */
    public function create($data): array
    {
        $entity_instance = new $this->entity($data);

        if ($this->model->save($entity_instance) === false) {
            return $this->model->errors();
        } else {

            $insert_id = $this->model->insertID;
            $this->addLogOnCreateOrDelete($insert_id);
            return [
                'success' => $this->createSuccessMessage,
                $this->id_field => $insert_id,
            ];
        }
    }

    /**
     * update Updates the Inventory Purchase data with $id
     *
     * @param  mixed $data
     * @param  mixed $id
     * @return array
     */
    public function update($id, $data): array
    {
        $original_data = $this->show($id);
        $entity_instance = new $this->entity($data);
        $entity_instance->id = $id;

        if ($this->model->save($entity_instance) === false) {
            return $this->model->errors();
        } else {
            $updated_data = $this->show($id);
            $this->addLogOnUpdate($id, $original_data, $updated_data);
            return [
                "success" => $this->updateSuccessMessage,
                $this->id_field => $id,
            ];
        }
    }

    /**
     * delete Delete an inventory.
     *
     * @param  mixed $id $id passed as a string deletes single entity and if passed as array deletes multiple.
     * @return void
     */
    public function delete($id)
    {
        if ($this->model->delete($id)) {
            $this->addLogOnCreateOrDelete($id);
        }
        return;
    }

    /**
     * updates Update multiple entries. First parameter is a string or an array. Another is columns.
     *
     * @param  mixed $ids
     * @param  mixed $columns
     * @return void
     */
    public function updates($ids, $columns)
    {
        return $this->model->update($ids, $columns);
    }

    /**
     * validate This method accepts the data and simply validates the error and returns the errors.
     * 
     * @param  mixed $data
     * @return array|null
     */
    public function validate($data): ?array
    {
        if (! $this->model->validate($data)) {
            $errors = $this->model->errors();
            return $errors;
        }

        return NULL;
    }

    protected function addLogOnUpdate($id, $original_data, $updated_data, $log_action = "updated"): void
    {

        if (!$this->hasAuditConfiguration()) {
            return;
        }

        $payload = service('changeDetector')->detect(
            strtoupper($this->audit_log_module),
            $original_data,
            $updated_data
        );

        $action = $this->audit_log_module;
        if (!empty($this->audit_sub_module)) {
            $action .= '.' . $this->audit_sub_module;
        }
        $action .= '.' . $log_action;

        if ((service('auditManager'))->hasChanges($payload)) {
            $audit_log = (service('auditManager'))->build(
                $this->audit_log_module,
                $this->audit_entity_type,
                $id,
                $action,
                $payload,
                $this->audit_sub_module
            );

            $this->sendToOutbox($audit_log);
        }
    }

    protected function addLogOnCreateOrDelete($id, $log_action = "created"): void
    {

        if (!$this->hasAuditConfiguration()) {
            return;
        }

        $action = $this->audit_log_module;
        if (!empty($this->audit_sub_module)) {
            $action .= '.' . $this->audit_sub_module;
        }
        $action .= '.' . $log_action;

        $audit_log = (service('auditManager'))->build(
            $this->audit_log_module,
            $this->audit_entity_type,
            $id,
            $action,
            [],
            $this->audit_sub_module
        );

        $this->sendToOutbox($audit_log);
    }

    protected function sendToOutbox(array $audit_log): void
    {
        try {
            $database = db_connect()->getDatabase();
            $audit_log['database'] = $database;
            $stringified_payload = base64_encode(json_encode($audit_log));
            exec("php index.php AuditOutboxController add $stringified_payload > /dev/null 2>&1 &");
        } catch (\Throwable $e) {
            log_message('critical', "Audit Log Payload Failed" . $e->getMessage());
        }
    }

    protected function hasAuditConfiguration(): bool
    {
        return !empty($this->audit_log_module) && !empty($this->audit_entity_type);
    }
}
