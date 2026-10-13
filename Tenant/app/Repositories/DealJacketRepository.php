<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\DealJacketDocumentModel;

class DealJacketRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new DealJacketDocumentModel());
        $this->entity = 'CodeIgniter\Entity';
        $this->entityLabel = 'Deal Jacket Document';
        $this->createSuccessMessage = $this->entityLabel . ' has been added.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'deal_jacket_document_id';
    }

    public function show($id): ?array
    {
        return $this->model->asArray()->find($id);
    }

    public function findByDealId($deal_id)
    {
        return $this->model->where('deal_id', $deal_id)->asArray()->findAll();
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
