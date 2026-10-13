<?php

namespace Tenant\app\Repositories;

use App\Exceptions\ModelNotFoundException;
use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\DealTradeInDisclosureModel;
use Tenant\app\Models\InventoryDisclosureModel;

class DealTradeInCommonRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new DealTradeInDisclosureModel());
        $this->entity = 'Tenant\app\Entities\DealTradeInDisclosure';
        $this->entityLabel = 'Trade In Disclosure';
        $this->createSuccessMessage = $this->entityLabel . ' has been added.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'deal_trade_in_disclosure_id';
    }

    public function filterByTradeInId($trade_in_id)
    {
        $this->model->where('trade_in_id', $trade_in_id);
        return $this;
    }

    public function deleteWithoutKey()
    {
        $this->model->delete();
    }

    public function filterByTradeInAsArray($trade_in_id)
    {
        return $this->model->where('trade_in_id', $trade_in_id)->asArray()->findAll();
    }

    /**
     * switchDealTradeInCommonDependentState This simply changes the Model based on the selection.
     *
     * @param  mixed $state
     * @return DealTradeInCommonRepository
     */
    public function switchDealTradeInCommonDependentState(String $state): DealTradeInCommonRepository
    {
        try {
            switch ($state) {
                case 'disclosures':
                    $this->model = new DealTradeInDisclosureModel();
                    $this->entityLabel = 'Trade In Disclosure';
                    $this->id_field = 'deal_trade_in_disclosure_id';
                    $this->entity = 'Tenant\app\Entities\DealTradeInDisclosure';
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

    public function bulkDelete()
    {
        $this->model->delete();
    }
}
