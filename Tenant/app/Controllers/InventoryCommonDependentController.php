<?php

namespace Tenant\app\Controllers;

use Tenant\app\Repositories\InventoryCommonDependentRepository;
use Tenant\app\Repositories\InventoryRepository;
use App\Policies\AuthPolicy;

class InventoryCommonDependentController extends \App\Controllers\BaseController
{
    /**
     * repository This repository corresponds to the inventoryCommonDependentRepository which can be used by any method here.
     *
     * @var InventoryCommonDependentRepository
     */
    private $repository;

    /**
     * secondary_repository This is the second repository which holds reference the the joined model.
     *
     * @var mixed
     */
    private $secondary_repository;

    public function __construct()
    {
        $this->repository = new InventoryCommonDependentRepository();
        $this->secondary_repository = new InventoryRepository();
    }

    public function create($inventory_id, $model)
    {
        $inventory = $this->secondary_repository->show($inventory_id);

        $this->checkIfInventoryExist($inventory);

        $data = $this->request->getPost();
        $data["inventory_id"] = $inventory_id;
        $response = $this->repository->switchInventoryCommonDependentState($model)->create($data);

        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function update($inventory_id, $model, $id)
    {
        $inventory = $this->secondary_repository->show($inventory_id);

        $this->checkIfInventoryExist($inventory);

        $data = $this->request->getPost();

        $response = $this->repository->switchInventoryCommonDependentState($model)->update($id, $data);
        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }


    public function show($inventory_id, $model, $id)
    {

        $inventory = $this->secondary_repository->show($inventory_id);

        $this->checkIfInventoryExist($inventory);

        $response = $this->repository->switchInventoryCommonDependentState($model)->show($id);
        if ($response != null) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ucfirst($model) . " couldn't be found for this inventory."])->send();
        }
    }

    public function delete($inventory_id, $model, $id)
    {
        $inventory = $this->secondary_repository->show($inventory_id);

        $this->checkIfInventoryExist($inventory);

        try {
            $this->repository->switchInventoryCommonDependentState($model)->delete($id);
            $this->response->setContentType('application/json')->setJSON(["success" => ucfirst($model) . " has been deleted."])->send();
        } catch (\Exception $exception) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "There were some errors in deleting the " . $model . "."])->send();
        }
    }

    public function bulkDelete($inventory_id, $model)
    {
        $inventory = $this->secondary_repository->show($inventory_id);

        $this->checkIfInventoryExist($inventory);

        try {
            $this->repository->switchInventoryCommonDependentState($model)->filterData('inventory_id', $inventory_id)->bulkDelete();
            $this->response->setContentType('application/json')->setJSON(["success" => ucfirst($model) . " has been deleted."])->send();
        } catch (\Exception $exception) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "There were some errors in deleting the " . $model . "."])->send();
        }
    }

    public function bulkCreateOrUpdate($inventory_id, $model)
    {
        $inventory = $this->secondary_repository->show($inventory_id);

        $this->checkIfInventoryExist($inventory);

        $data = $this->request->getPost();

        $errors = [];

        $this->repository = $this->repository->switchInventoryCommonDependentState($model);

        $model_validation_rules = $this->repository->getModel()->getValidationRules(['except' => ['inventory_id']]);

        foreach ($data as $key => $entity) {

            if (!empty($data[$key]['id'])) {
                if (!is_numeric(trim($data[$key]['id']))) {
                    unset($data[$key]['id']);
                }
            }

            $data[$key]['inventory_id'] = $inventory['inventory_id'];

            $validation =  \Config\Services::validation();
            $validation->setRules($model_validation_rules);
            if (!$validation->run($data[$key])) {
                if (!empty($validation->getErrors())) {
                    $errors[$key] = $validation->getErrors();
                }
            }
            $validation->reset();
        }

        if (empty($errors)) {
            //Create or Update Logic
            foreach ($data as $key => $entity) {
                if (empty($entity[$this->repository->getModel()->__get("primaryKey")])) {
                    $this->repository->create($entity);
                } else {
                    $this->repository->update($entity['id'], $entity);
                }
            }

            $this->response->setContentType('application/json')->setJSON(['success' => ucfirst($model) . " Updated successfully."])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($errors)->send();
        }
    }

    public function index($inventory_id, $model)
    {
        $inventory = $this->secondary_repository->show($inventory_id);

        $this->checkIfInventoryExist($inventory);

        $response = $this->repository->switchInventoryCommonDependentState($model)->findByInventoryId($inventory_id);
        if ($response != null) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ucfirst($model) . " couldn't be found for this inventory."])->send();
        }
    }

    public function updateDigitalRetailing($inventory_id): void
    {
        $inventory = $this->secondary_repository->show($inventory_id);
        $data = $this->request->getPost();

        // var_dump($data);
        $has_errors = false;
        $errors = [];

        $this->checkIfInventoryExist($inventory);

        $this->repository = $this->repository->switchInventoryCommonDependentState('digital-retailing');

        $model_validation_rules = $this->repository->getModel()->getValidationRules(['except' => ['inventory_id']]);

        $data['inventory_id'] = $inventory['inventory_id'];

        $validation =  \Config\Services::validation();
        $validation->setRules($model_validation_rules);
        if (!$validation->run($data)) {
            if (!empty($validation->getErrors())) {
                $has_errors = true;
                $errors = $validation->getErrors();
            }
        }

        $validation->reset();

        if (!empty($data['finance_terms'])) {
            foreach ($data['finance_terms'] as $key => $row) {

                $validation->setRules([
                    'term'  => [
                        "label" => 'Term',
                        "rules" => 'required|is_natural|less_than_equal_to[120]'
                    ],
                    'downpayment'  => [
                        "label" => 'Downpayment',
                        "rules" => 'required|is_natural'
                    ],
                    'interest_rate'  => [
                        "label" => 'Interest Rate',
                        "rules" => 'required|decimal|less_than_equal_to[100]'
                    ]
                ]);

                if (!$validation->run($row)) {
                    $has_errors = true;
                    $errors[$key] = $validation->getErrors();
                } else {
                    $errors[$key] = [];
                }

                $validation->reset();
            }
        }



        if (!$has_errors) {
            //Switch the model, delete existing and recreate

            $data['finance_terms'] = !empty($data['finance_terms']) ? json_encode($data['finance_terms']) : [];
            $this->repository->switchInventoryCommonDependentState('digital-retailing')->filterData('inventory_id', $inventory_id)->bulkDelete();
            $this->repository->switchInventoryCommonDependentState('digital-retailing')->create($data);


            $this->response->setContentType('application/json')->setJSON(['success' => 'e-Retail data have been saved.'])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($errors)->send();
        }
    }

    protected function checkIfInventoryExist($inventory)
    {
        if ($inventory == null || !isset($inventory['inventory_id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Inventory couldn't be found."
            ])->send();
            exit;
        }
    }
}
