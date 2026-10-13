<?php

namespace Tenant\app\Repositories;
use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\UserSMSConfigurationModel;

class UserSMSConfigurationRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new UserSMSConfigurationModel());
        $this->entity = 'CodeIgniter\Entity';
        $this->entityLabel = 'User SMS Configuration';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'user_sms_configuration_id';
    }
    
    /**
     * findByUserId
     *
     * @param  mixed $user_id
     * @return void
     */
    public function findByUserId($user_id){
        return $this->model->where('user_id', $user_id)->asArray()->first();
    }
}
