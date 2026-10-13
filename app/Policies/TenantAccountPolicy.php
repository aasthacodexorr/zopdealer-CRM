<?php

namespace App\Policies;

use Tenant\app\Repositories\ConfigurationRepository;

class TenantAccountPolicy
{
    /**
     * configuration_repository
     *
     * @var ConfigurationRepository
     */
    private $configuration_repository;

    public function __construct()
    {
        $this->configuration_repository = new ConfigurationRepository();
    }

    public function isAccountActive() {
        $is_account_active = false;
        $is_account_active__configuration_array = $this->configuration_repository->findConfigurationColumnsByKeyAsArray('is_account_active', ['value']);
        if(!empty($is_account_active__configuration_array['value'])){
            $is_account_active = true;
        }
        return $is_account_active;
    }
}
