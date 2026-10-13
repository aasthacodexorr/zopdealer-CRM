<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\CustomerInsuranceModel;


class CustomerInsuranceRepository extends CRUDWithAdvanceRepository 
{
    public function __construct()
    {
        parent::__construct(new CustomerInsuranceModel());
        $this->entity = 'Tenant\app\Entities\CustomerInsurance';
        $this->entityLabel = 'Customer insurance';
        $this->createSuccessMessage = $this->entityLabel . ' has been added.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'customer_insurance_id';

    }

    /**
     * findByCustomerId search CustomerInsurance By Customer Id
     *
     * @param  mixed $customer_id
     * @return void
     */
    public function findByCustomerId($customer_id){
        return $this->model->where('customer_id', $customer_id)->asArray()->first();
    }

    public function filterByCustomerId($customer_id){
        $this->model->where('customer_id', $customer_id);
        return $this;
    }

}