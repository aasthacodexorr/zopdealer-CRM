<?php

namespace Tenant\app\Controllers;

use App\Helpers\Utility;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\DealRepository;
use Tenant\app\Repositories\DealTradeInRepository;
use Tenant\app\Repositories\InventoryRepository;

class DealTradeInController extends \App\Controllers\BaseController
{
    /**
     * repository This repository corresponds to the DealTradeInRepository which can be used by any method here.
     *
     * @var DealTradeInRepository
     */
    private $repository;

    /**
     * secondary_repository This is the second repository which holds reference the the joined model.
     *
     * @var mixed
     */
    private $secondary_repository;

    private $configuration_repository;
        
    /**
     * inventory_repository
     *
     * @var InventoryRepository
     */
    private $inventory_repository;

    public function __construct()
    {
        $this->repository = new DealTradeInRepository();
        $this->secondary_repository = new DealRepository();
        $this->configuration_repository = new ConfigurationRepository();
        $this->inventory_repository = new InventoryRepository();
    }

    public function create($deal_id)
    {
        $deal = $this->secondary_repository->show($deal_id);

        $this->existenceOrRightsCheckForDeal($deal);

        $this->isLocked($deal);

        $data = $this->request->getPost();

        $data['stock_no'] = !empty($data['stock_no']) ? $data['stock_no'] : $this->inventory_repository->getAvailableStockNumber($data);
        $data["deal_id"] = $deal_id;
        $response = $this->repository->create($data);

        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function update($deal_id, $id)
    {
        $deal = $this->secondary_repository->show($deal_id);

        $this->existenceOrRightsCheckForDeal($deal);

        $this->isLocked($deal);

        $data = $this->request->getPost();

        $response = $this->repository->update($id, $data);
        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }


    public function show($deal_id, $id)
    {

        $deal = $this->secondary_repository->show($deal_id);

        $this->existenceOrRightsCheckForDeal($deal);

        if ($deal == null || !isset($deal['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Deal couldn't be found."
            ])->send();
            exit;
        }

        $response = $this->repository->show($id);

        if ($response != null) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "Trade In details couldn't be found for this deal."])->send();
        }
    }

    public function delete($deal_id, $id)
    {
        $deal = $this->secondary_repository->show($deal_id);

        $this->existenceOrRightsCheckForDeal($deal);

        $this->isLocked($deal);

        if ($deal == null || !isset($deal['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Deal couldn't be found."
            ])->send();
            exit;
        }

        try {
            $this->repository->delete($id);
            $this->response->setContentType('application/json')->setJSON(["success" => "Trade In has been deleted."])->send();
        } catch (\Exception $exception) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "There were some errors in deleting the Trade In."])->send();
        }
    }

    public function index($deal_id)
    {
        $deal = $this->secondary_repository->show($deal_id);

        $this->existenceOrRightsCheckForDeal($deal);

        if ($deal == null || !isset($deal['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Deal couldn't be found."
            ])->send();
            exit;
        }

        $response = $this->repository->findByDealId($deal_id);

        if ($response != null) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "Trade In couldn't be found for this deal."])->send();
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
