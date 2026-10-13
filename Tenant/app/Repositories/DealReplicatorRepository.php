<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\DealReplicatorModel;
use Tenant\app\Models\DealReportModel;

class DealReplicatorRepository extends CRUDWithAdvanceRepository 
{
    public function __construct()
    {
        parent::__construct(new DealReplicatorModel());
        $this->entity = 'Tenant\app\Entities\Deal';
        $this->entityLabel = 'Deal Template';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'deal_replicator_id';

    }

    public function findByDealReplicatorId($deal_replicator_id){
        return $this->model->where('deal_replicator_id', $deal_replicator_id)->findAll();
    }

    public function filterByDealReplicatorId($deal_replicator_id){
        $this->model->where('deal_replicator_id', $deal_replicator_id);
        return $this;
    }

    public function deleteWithoutKey(){
        $this->model->delete();
    }    
}