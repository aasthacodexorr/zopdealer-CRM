<?php

namespace Tenant\app\Libraries\UserAssignment;

use Tenant\app\Repositories\TenantUserMetaDataRepository;

abstract class AbstractUserAssignment implements UserAssignmentInterface
{
    /**
     * tenant_user_repository
     *
     * @var TenantUserMetaDataRepository
     */
    protected $tenant_user_repository;

    protected function getUsersWithAutoAssignEnabled(): array
    {
        $this->tenant_user_repository = new TenantUserMetaDataRepository();
        $all_user_object_array = $this->tenant_user_repository->findAllUsers(['users.id', 'user_metadata.lead_auto_assign']);
        $all_user_object_array = array_filter($all_user_object_array, function ($user_object) {
            return (int) $user_object->lead_auto_assign === 1;
        });
        $all_users = array_map(function ($user_object) {
            return (int) $user_object->id;
        },  $all_user_object_array);
        $all_users = array_values($all_users);
        return $all_users;
    }
}
