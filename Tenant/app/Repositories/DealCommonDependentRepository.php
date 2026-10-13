<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use CodeIgniter\Model;
use Tenant\app\Models\DealAccessoryModel;
use Tenant\app\Models\DealFeeModel;
use Tenant\app\Models\DealInsuranceModel;
use Tenant\app\Models\DealPaymentModel;
use Tenant\app\Models\DealWarrantyModel;
use app\Exceptions\ModelNotFoundException;
use Tenant\app\Models\DealBaseBreakDownModel;
use Tenant\app\Models\DealCustomerPivotModel;
use Tenant\app\Models\DealDealertrackLogModel;
use Tenant\app\Models\DealDeliveryChecklistModel;
use Tenant\app\Models\DealFinanceModel;
use Tenant\app\Models\DealGuaranteeModel;
use Tenant\app\Models\DealInventoryPivotModel;
use Tenant\app\Models\DealSaleCommissionModel;
use Tenant\app\Models\DealTradeInModel;

class DealCommonDependentRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new Model());
        $this->entity = 'Tenant\app\Entities\Deal';
        $this->entityLabel = 'Deal';
        $this->createSuccessMessage = $this->entityLabel . ' has been added.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'deal_id';
    }

    public function findByDealId($deal_id)
    {
        return $this->model->where('deal_id', $deal_id)->findAll();
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

    /**
     * switchDealCommonDependentState This simply changes the Model based on the selection.
     *
     * @param  mixed $state
     * @return DealCommonDependentRepository
     */
    public function switchDealCommonDependentState(String $state): DealCommonDependentRepository
    {
        try {
            switch ($state) {
                case 'accessories':
                    $this->model = new DealAccessoryModel();
                    $this->entityLabel = 'Deal Accessory';
                    $this->id_field = 'deal_accessory_id';
                    $this->entity = 'Tenant\app\Entities\DealAccessory';
                    break;

                case 'fees':
                    $this->model = new DealFeeModel();
                    $this->entityLabel = 'Deal Fee';
                    $this->id_field = 'deal_fee_id';
                    $this->entity = 'Tenant\app\Entities\DealFee';
                    break;

                case 'insurances':
                    $this->model = new DealInsuranceModel();
                    $this->entityLabel = 'Deal Insurance';
                    $this->id_field = 'deal_insurance_id';
                    $this->entity = 'Tenant\app\Entities\DealInsurance';
                    break;

                case 'payments':
                    $this->model = new DealPaymentModel();
                    $this->entityLabel = 'Deal Payment';
                    $this->id_field = 'deal_payment_id';
                    $this->entity = 'Tenant\app\Entities\DealPayment';
                    break;

                case 'warranties':
                    $this->model = new DealWarrantyModel();
                    $this->entityLabel = 'Deal Warranty';
                    $this->id_field = 'deal_warranty_id';
                    $this->entity = 'Tenant\app\Entities\DealWarranty';
                    break;

                case 'guarantee':
                    $this->model = new DealGuaranteeModel();
                    $this->entityLabel = 'Deal Guarantee';
                    $this->id_field = 'deal_guarantee_id';
                    $this->entity = 'Tenant\app\Entities\DealWarranty';
                    break;

                case 'finance':
                    $this->model = new DealFinanceModel();
                    $this->entityLabel = 'Deal Finance';
                    $this->id_field = 'deal_finance_id';
                    $this->entity = 'Tenant\app\Entities\DealFinance';
                    break;

                case 'dealbreakdown':
                    $this->model = new DealBaseBreakDownModel();
                    $this->entityLabel = 'Deal Break Down';
                    $this->id_field = 'deal_break_down_id';
                    $this->entity = 'Tenant\app\Entities\DealBaseBreakDown';
                    break;

                case 'delivery-checklist':
                    $this->model = new DealDeliveryChecklistModel();
                    $this->entityLabel = 'Delivery Checklist';
                    $this->id_field = 'delivery_checklist_id';
                    $this->entity = 'CodeIgniter\Entity';
                    break;

                case 'sale-commissions':
                    $this->model = new DealSaleCommissionModel();
                    $this->entityLabel = 'Deal Sale Commission';
                    $this->id_field = 'deal_sale_commission_id';
                    $this->entity = 'Tenant\app\Entities\DealSaleCommission';
                    break;

                case 'deal-customer':
                    $this->model = new DealCustomerPivotModel();
                    $this->entityLabel = 'Deal Customer';
                    $this->id_field = 'deal_customer_id';
                    $this->entity = 'CodeIgniter\Entity';
                    break;

                case 'deal-inventory':
                    $this->model = new DealInventoryPivotModel();
                    $this->entityLabel = 'Deal Inventory';
                    $this->id_field = 'deal_inventory_id';
                    $this->entity = 'CodeIgniter\Entity';
                    break;

                case 'tradein':
                    $this->model = new DealTradeInModel();
                    $this->entityLabel = 'Trade In';
                    $this->id_field = 'deal_trade_in';
                    $this->entity = 'Tenant\app\Entities\Inventory';
                    break;

                case 'dealertrack':
                    $this->model = new DealDealertrackLogModel();
                    $this->entityLabel = 'Deal Dealer Track Log';
                    $this->id_field = 'deal_dealerTrack_log_id';
                    $this->entity = 'CodeIgniter\Entity';
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

    public function switchDealCommonDependentStateAndPurge(String $state, String $deal_id): void
    {
        if (is_numeric($deal_id)) {
            $this->switchDealCommonDependentState($state);
            $this->model->where('deal_id', $deal_id)->delete();
        }
    }
}
