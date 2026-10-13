<?php

namespace Tenant\app\Libraries\UserAssignment;

use Tenant\app\Repositories\TenantUserMetaDataRepository;

final class RandomStrategy extends AbstractUserAssignment implements UserAssignmentInterface
{
    public function assign(): ?int
    {
        try {
            $all_users = $this->getUsersWithAutoAssignEnabled();
            $max_count = count($all_users) - 1;
            $random_number = rand(0, $max_count);
            return $all_users[$random_number];
        } catch (\Exception $ex) {
            log_message('error', $ex->getMessage());
            return null;
        }
    }
}
