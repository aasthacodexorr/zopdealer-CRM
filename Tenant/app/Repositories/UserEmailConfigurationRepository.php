<?php

namespace Tenant\app\Repositories;
use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\UserEmailConfigurationModel;

class UserEmailConfigurationRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new UserEmailConfigurationModel());
        $this->entity = 'Tenant\app\Entities\TenantUser';
        $this->entityLabel = 'User Email Configuration';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'user_email_configuration_id';
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
