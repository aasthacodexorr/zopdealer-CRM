<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;

use Tenant\app\Models\DealTradeInModel;

class DealTradeInRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new DealTradeInModel());
        $this->entity = 'Tenant\app\Entities\Inventory';
        $this->entityLabel = 'Trade In';
        $this->createSuccessMessage = $this->entityLabel . ' has been added.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'deal_trade_in';
    }

    public function findByDealId($deal_id)
    {
        return $this->model->where('deal_id', $deal_id)->asArray()->first();
    }

    public function filterByDealId($deal_id)
    {
        $this->model->where('deal_id', $deal_id);
        return $this;
    }

    public function filterByDealIdAsArray($deal_id)
    {
        return $this->model->where('deal_id', $deal_id)->asArray()->findAll();
    }
}
