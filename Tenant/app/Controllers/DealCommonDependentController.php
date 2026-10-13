<?php

namespace Tenant\app\Controllers;

use Tenant\app\Repositories\DealCommonDependentRepository;
use Tenant\app\Repositories\DealRepository;
use Tenant\app\Repositories\ConfigurationRepository;

class DealCommonDependentController extends \App\Controllers\BaseController
{
    /**
     * repository This repository corresponds to the DealCommonDependentRepository which can be used by any method here.
     *
     * @var DealCommonDependentRepository
     */
    private $repository;

    /**
     * secondary_repository This is the second repository which holds reference the the joined model.
     *
     * @var mixed
     */
    private $secondary_repository;

    private $configuration_repository;

    public function __construct()
    {
        $this->repository = new DealCommonDependentRepository();
        $this->secondary_repository = new DealRepository();
        $this->configuration_repository = new ConfigurationRepository();
    }

    public function create($deal_id, $model)
    {
        $deal = $this->secondary_repository->show($deal_id);

        $this->existenceOrRightsCheckForDeal($deal, $model);

        $this->isLocked($deal);

        $data = $this->request->getPost();
        $data["deal_id"] = $deal_id;
        $response = $this->repository->switchDealCommonDependentState($model)->create($data);

        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function update($deal_id, $model, $id)
    {
        $deal = $this->secondary_repository->show($deal_id);

        $this->existenceOrRightsCheckForDeal($deal, $model);

        $this->isLocked($deal);

        $data = $this->request->getPost();

        $response = $this->repository->switchDealCommonDependentState($model)->update($id, $data);
        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }


    public function show($deal_id, $model, $id)
    {

        $deal = $this->secondary_repository->show($deal_id);

        $this->existenceOrRightsCheckForDeal($deal, $model);

        $response = $this->repository->switchDealCommonDependentState($model)->show($id);
        if ($response != null) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ucfirst($model) . " couldn't be found for this deal."])->send();
        }
    }

    public function delete($deal_id, $model, $id)
    {
        $deal = $this->secondary_repository->show($deal_id);

        $this->existenceOrRightsCheckForDeal($deal, $model);

        $this->isLocked($deal);

        try {
            $this->repository->switchDealCommonDependentState($model)->delete($id);
            $this->response->setContentType('application/json')->setJSON(["success" => ucfirst($model) . " has been deleted."])->send();
        } catch (\Exception $exception) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "There were some errors in deleting the " . $model . "."])->send();
        }
    }

    public function index($deal_id, $model)
    {
        $deal = $this->secondary_repository->show($deal_id);

        $this->existenceOrRightsCheckForDeal($deal, $model);

        $response = $this->repository->switchDealCommonDependentState($model)->findByDealId($deal_id);
        if ($response != null) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ucfirst($model) . " couldn't be found for this deal."])->send();
        }
    }

    public function onlyValidate($model)
    {

        $data = $this->request->getPost();
        $response = $this->repository->switchDealCommonDependentState($model)->validate($data);

        if (empty($response)) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    /**
     * existenceOrRightsCheckForDeal This function checks if the deal is present or created by 
     *
     * @return void
     */
    protected function existenceOrRightsCheckForDeal($deal, $model = "")
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

        if ($model === 'sale-commissions' && !service('authPolicy')->hasElevatedRoles()) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "You do not have enough privelages to see the deal."])->send();
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
