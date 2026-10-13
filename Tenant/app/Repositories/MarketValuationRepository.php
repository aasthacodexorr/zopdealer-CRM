<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\MarketValuationModel;

class MarketValuationRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new MarketValuationModel());
        $this->entity = 'Tenant\app\Entities\MarketValuation';
        $this->entityLabel = 'Market Valuation';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'market_valuation_id';
    }
}
