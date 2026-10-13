<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\AccessorySettingModel;
use Tenant\app\Models\CostSettingModel;
use Tenant\app\Models\FeeSettingModel;
use Tenant\app\Models\GuaranteeSettingModel;
use Tenant\app\Models\InsuranceSettingModel;
use Tenant\app\Models\WarrantySettingModel;

/**
 * CostSettingRepository CRUD and pagination for the Cost Setting
 */
class CostSettingRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new CostSettingModel());
        $this->entity = 'Tenant\app\Entities\CostSetting';
        $this->entityLabel = 'Cost';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'cost_setting_id';
    }


    /**
     * switchCostType This method is used to swtich between various types of fees.
     *
     * @param  String $type
     * @return CostSettingRepository
     */
    public function switchCostType(String $type)
    {

        switch ($type) {

            case 'accessories':
                $this->model = new AccessorySettingModel();
                $this->entityLabel = 'Deal Accessory';
                $this->createSuccessMessage = $this->entityLabel . ' has been created.';
                $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
                $this->id_field = 'accessory_setting_id';
                $this->entity = 'Tenant\app\Entities\DealAccessory';
                break;

            case 'fees':
                $this->entity = 'Tenant\app\Entities\DealFee';
                $this->entityLabel = 'Fee';
                $this->createSuccessMessage = $this->entityLabel . ' has been created.';
                $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
                $this->id_field = 'fee_setting_id';
                $this->model = new FeeSettingModel();
                break;

            case 'warranty':
                $this->entity = 'Tenant\app\Entities\DealWarranty';
                $this->entityLabel = 'Warranty';
                $this->createSuccessMessage = $this->entityLabel . ' has been created.';
                $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
                $this->id_field = 'warranty_setting_id';
                $this->model = new WarrantySettingModel();
                break;

            case 'guarantee':
                $this->entity = 'Tenant\app\Entities\DealWarranty';
                $this->entityLabel = 'Guarantee';
                $this->createSuccessMessage = $this->entityLabel . ' has been created.';
                $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
                $this->id_field = 'guarantee_setting_id';
                $this->model = new GuaranteeSettingModel();
                break;

            case 'insurances':
                $this->model = new InsuranceSettingModel();
                $this->entityLabel = 'Insurance';
                $this->createSuccessMessage = $this->entityLabel . ' has been created.';
                $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
                $this->id_field = 'insurance_setting_id';
                $this->entity = 'Tenant\app\Entities\DealInsurance';
                break;

            default:
                $this->entity = 'Tenant\app\Entities\CostSetting';
                $this->entityLabel = 'Cost';
                $this->createSuccessMessage = $this->entityLabel . ' has been created.';
                $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
                $this->id_field = 'cost_setting_id';
                $this->model = new CostSettingModel();
                break;
        }

        return $this;
    }

    public function onlyMandatory(){
        $this->model->where("is_mandatory", "1");
        return $this;
    }

    public function excludeOnlyFinance(){
        $this->model->where("only_finance", "0");
        return $this;
    }
}
