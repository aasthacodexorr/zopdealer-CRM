<?php

namespace Tenant\app\Controllers;

use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\DealRepository;
use Tenant\app\Repositories\DealTradeInCommonRepository;
use Tenant\app\Repositories\DealTradeInRepository;

class DealTradeInCommonDependentController extends \App\Controllers\BaseController
{
    /**
     * repository This repository corresponds to the DealTradeInCommonRepository which can be used by any method here.
     *
     * @var DealTradeInCommonRepository
     */
    private $repository;

    /**
     * secondary_repository This is the second repository which holds reference the the joined model.
     *
     * @var mixed
     */
    private $secondary_repository;

    /**
     * deal_repository  This is the repository for the deal.
     *
     * @var mixed
     */
    private $deal_repository;
    private $configuration_repository;

    public function __construct()
    {
        $this->repository = new DealTradeInCommonRepository();
        $this->secondary_repository = new DealTradeInRepository();
        $this->deal_repository = new DealRepository();
        $this->configuration_repository = new ConfigurationRepository();
    }

    public function create($deal_id, $model)
    {
        $deal = $this->deal_repository->show($deal_id);

        $this->existenceOrRightsCheckForDeal($deal);

        $this->isLocked($deal);

        $trade_in_inventory = $this->secondary_repository->findByDealId($deal_id);

        $this->checkIfTradeInExist($trade_in_inventory);

        $data = $this->request->getPost();
        $data["trade_in_id"] = $trade_in_inventory['id'];
        $response = $this->repository->switchDealTradeInCommonDependentState($model)->create($data);

        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function update($deal_id, $model, $id)
    {
        $deal = $this->deal_repository->show($deal_id);

        $this->existenceOrRightsCheckForDeal($deal);

        $this->isLocked($deal);

        $trade_in_inventory = $this->secondary_repository->findByDealId($deal_id);

        $this->checkIfTradeInExist($trade_in_inventory);

        $current_entity = $this->repository->switchDealTradeInCommonDependentState($model)->show($id);

        $this->checkIfDependentComponentExistOrBelongsToTradeIn($current_entity, $trade_in_inventory['id']);

        $data = $this->request->getPost();

        $response = $this->repository->switchDealTradeInCommonDependentState($model)->update($id, $data);
        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }


    public function show($deal_id, $model, $id)
    {

        $deal = $this->deal_repository->show($deal_id);

        $this->existenceOrRightsCheckForDeal($deal);

        $trade_in_inventory = $this->secondary_repository->findByDealId($deal_id);

        $this->checkIfTradeInExist($trade_in_inventory);

        $current_entity = $this->repository->switchDealTradeInCommonDependentState($model)->show($id);

        $this->checkIfDependentComponentExistOrBelongsToTradeIn($current_entity, $trade_in_inventory['id']);

        $response = $this->repository->switchDealTradeInCommonDependentState($model)->show($id);
        if ($response != null) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ucfirst($model) . " couldn't be found for this inventory."])->send();
        }
    }

    public function delete($deal_id, $model, $id)
    {
        $deal = $this->deal_repository->show($deal_id);

        $this->existenceOrRightsCheckForDeal($deal);

        $this->isLocked($deal);

        $trade_in_inventory = $this->secondary_repository->findByDealId($deal_id);

        $this->checkIfTradeInExist($trade_in_inventory);

        $current_entity = $this->repository->switchDealTradeInCommonDependentState($model)->show($id);

        $this->checkIfDependentComponentExistOrBelongsToTradeIn($current_entity, $trade_in_inventory['id']);

        try {
            $this->repository->switchDealTradeInCommonDependentState($model)->delete($id);
            $this->response->setContentType('application/json')->setJSON(["success" => ucfirst($model) . " has been deleted."])->send();
        } catch (\Exception $exception) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "There were some errors in deleting the " . $model . "."])->send();
        }
    }

    public function bulkDelete($deal_id, $model)
    {
        $deal = $this->deal_repository->show($deal_id);

        $this->existenceOrRightsCheckForDeal($deal);

        $this->isLocked($deal);

        $trade_in_inventory = $this->secondary_repository->findByDealId($deal_id);

        $this->checkIfTradeInExist($trade_in_inventory);

        try {
            $this->repository->switchDealTradeInCommonDependentState($model)->filterData('trade_in_id', $trade_in_inventory['id'])->bulkDelete();
            $this->response->setContentType('application/json')->setJSON(["success" => ucfirst($model) . " has been deleted."])->send();
        } catch (\Exception $exception) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "There were some errors in deleting the " . $model . "."])->send();
        }
    }

    public function bulkCreateOrUpdate($deal_id, $model)
    {
        $deal = $this->deal_repository->show($deal_id);

        $this->existenceOrRightsCheckForDeal($deal);

        $this->isLocked($deal);

        $trade_in_inventory = $this->secondary_repository->findByDealId($deal_id);

        $this->checkIfTradeInExist($trade_in_inventory);

        $data = $this->request->getPost();

        $errors = [];

        $this->repository = $this->repository->switchDealTradeInCommonDependentState($model);

        $model_validation_rules = $this->repository->getModel()->getValidationRules(['except' => ['trade_in_id']]);

        //var_dump($data['question']);

        foreach ($data as $key => $entity) {

            if (!empty($data[$key]['id'])) {
                if (!is_numeric(trim($data[$key]['id']))) {
                    unset($data[$key]['id']);
                }
            }

            $data[$key]['trade_in_id'] = $trade_in_inventory['id'];

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

    public function index($deal_id, $model)
    {
        $deal = $this->deal_repository->show($deal_id);

        $this->existenceOrRightsCheckForDeal($deal);

        $trade_in_inventory = $this->secondary_repository->findByDealId($deal_id);

        $this->checkIfTradeInExist($trade_in_inventory);

        $response = $this->repository->switchDealTradeInCommonDependentState($model)->filterByTradeInAsArray($trade_in_inventory['id']);
        if ($response != null) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ucfirst($model) . " couldn't be found for this inventory."])->send();
        }
    }

    /**
     * existenceOrRightsCheckForDeal This function checks if the deal is present or created by 
     *
     * @return void
     */
    protected function existenceOrRightsCheckForDeal($deal)
    {
        if ($deal == null || !isset($deal['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Deal couldn't be found."
            ])->send();
            exit;
        }

        if (!$this->isCurrentUserAuthorizedToViewCurrentDeal($deal)) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "You do not have enough privelages to see the deal."])->send();
            exit;
        }
    }

    protected function checkIfTradeInExist($trade_in_inventory)
    {
        if ($trade_in_inventory == null || !isset($trade_in_inventory['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Trade In Inventory couldn't be found."
            ])->send();
            exit;
        }
    }

    protected function checkIfDependentComponentExistOrBelongsToTradeIn($entity, $trade_in_id)
    {
        if ($entity == null || $entity['trade_in_id'] != $trade_in_id) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Trade In Disclosure couldn't be found."
            ])->send();
            exit;
        }
    }

    /**
     * isLocked This function checks if the deal is locked
     *
     * @return void
     */
    protected function isLocked($deal)
    {
        if ($deal == null || !isset($deal['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Deal couldn't be found."
            ])->send();
            exit;
        }

        if (intval($deal['lock']) === 1) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Deal is locked."
            ])->send();
            exit;
        }
    }


    private function isCurrentUserAuthorizedToViewCurrentDeal($deal)
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            return true;
        }

        $authorize_staff_to_view_all_deals =  $this->configuration_repository->findConfigurationColumnsByKey('authorize_staff_to_view_all_deals', 'value')->value;
        $authorize_staff_to_view_all_deals =  !empty($authorize_staff_to_view_all_deals);

        if ($authorize_staff_to_view_all_deals) {
            return true;
        }

        return $deal['salesperson'] == service('authPolicy')->id;
    }
}
