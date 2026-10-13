<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use CodeIgniter\Model;
use Tenant\app\Models\DealReplicatorAccessoryModel;
use Tenant\app\Models\DealReplicatorFeeModel;
use Tenant\app\Models\DealReplicatorInsuranceModel;
use Tenant\app\Models\DealReplicatorModel;
use Tenant\app\Models\DealReplicatorPaymentModel;
use Tenant\app\Models\DealReplicatorWarrantyModel;
use app\Exceptions\ModelNotFoundException;
use Tenant\app\Models\DealReplicatorGuaranteeModel;

class DealReplicatorCommonDependentRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new Model());
        $this->entity = 'Tenant\app\Entities\Deal';
        $this->entityLabel = 'Model Deal';
        $this->createSuccessMessage = $this->entityLabel . ' has been added.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'deal_replicator_id';
    }

    public function findByDealReplicatorId($deal_replicator_id)
    {
        return $this->model->where('deal_replicator_id', $deal_replicator_id)->findAll();
    }

    public function filterByDealReplicatorId($deal_replicator_id)
    {
        $this->model->where('deal_replicator_id', $deal_replicator_id);
        return $this;
    }

    public function filterByDealReplicatorIdAsArray($deal_replicator_id){
        return $this->model->where('deal_replicator_id', $deal_replicator_id)->asArray()->findAll();
    }
    
    /**
     * switchDealReplicatorCommonDependentState This simply changes the Model based on the selection.
     *
     * @param  mixed $state
     * @return DealReplicatorCommonDependentRepository
     */
    public function switchDealReplicatorCommonDependentState(String $state) : DealReplicatorCommonDependentRepository
    {
        try {
            switch ($state) {
                case 'accessories':
                    $this->model = new DealReplicatorAccessoryModel();
                    $this->entityLabel = 'Deal Replicator Accessory';
                    $this->id_field = 'deal_replicator_accessory_id';
                    $this->entity = 'Tenant\app\Entities\DealAccessory';
                    break;

                case 'fees':
                    $this->model = new DealReplicatorFeeModel();
                    $this->entityLabel = 'Deal Replicator Fee';
                    $this->id_field = 'deal_replicator_fee_id';
                    $this->entity = 'Tenant\app\Entities\DealFee';
                    break;

                case 'insurances':
                    $this->model = new DealReplicatorInsuranceModel();
                    $this->entityLabel = 'Deal Replicator Insurance';
                    $this->id_field = 'deal_replicator_insurance_id';
                    $this->entity = 'Tenant\app\Entities\DealInsurance';
                    break;

                case 'payments':
                    $this->model = new DealReplicatorPaymentModel();
                    $this->entityLabel = 'Deal Replicator Payment';
                    $this->id_field = 'deal_replicator_payment_id';
                    $this->entity = 'Tenant\app\Entities\DealPayment';
                    break;

                case 'warranties':
                    $this->model = new DealReplicatorWarrantyModel();
                    $this->entityLabel = 'Deal Replicator Warranty';
                    $this->id_field = 'deal_replicator_warranty_id';
                    $this->entity = 'Tenant\app\Entities\DealWarranty';
                    break;
                
                case 'guarantee':
                    $this->model = new DealReplicatorGuaranteeModel();
                    $this->entityLabel = 'Deal Replicator Guarantee';
                    $this->id_field = 'deal_replicator_guarantee_id';
                    $this->entity = 'Tenant\app\Entities\DealWarranty';
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
}
