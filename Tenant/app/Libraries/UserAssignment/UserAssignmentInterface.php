<?php

namespace Tenant\app\Libraries\UserAssignment;

/***
 * UserAssignmentInterface defines the method that can be
 */
interface UserAssignmentInterface
{    
    /**
     * assign This returns the user id of the assignment of the user
     *
     * @return int
     */
    public function assign(): ?int;
}
