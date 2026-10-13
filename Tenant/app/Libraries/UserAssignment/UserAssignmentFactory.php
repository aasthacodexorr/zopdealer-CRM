<?php

namespace Tenant\app\Libraries\UserAssignment;

use Exception;
use Tenant\app\Libraries\UserAssignment\RandomStrategy;
use Tenant\app\Libraries\UserAssignment\RoundRobinStrategy;
use Tenant\app\Libraries\UserAssignment\UserAssignmentConstant;
use Tenant\app\Libraries\UserAssignment\UserAssignmentInterface;

class UserAssignmentFactory
{
    public static function getInstance(int $type): ? UserAssignmentInterface
    {
        try {
            switch ($type) {
                case UserAssignmentConstant::RANDOM:
                    return new RandomStrategy();
                    break;

                case UserAssignmentConstant::ROUND_ROBIN:
                    return new RoundRobinStrategy();
                    break;
                default:
                    throw new Exception('User Assignment Strategy Not Set');
                    break;
            }
        } catch (\Exception $ex) {
            log_message('notice', $ex->getMessage());
            return null;
        }
    }
}
