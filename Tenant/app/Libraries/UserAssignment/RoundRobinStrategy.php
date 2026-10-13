<?php

namespace Tenant\app\Libraries\UserAssignment;

use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\TenantUserMetaDataRepository;

final class RoundRobinStrategy extends AbstractUserAssignment implements UserAssignmentInterface
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

    public function assign(): ?int
    {
        try {

            $lead_round_robin_assignment_index = $this->configuration_repository->findConfigurationColumnsByKey('lead_round_robin_assignment_index', ['value']);
            $lead_round_robin_assignment_index = $lead_round_robin_assignment_index->value;

            $current_index = (int) $lead_round_robin_assignment_index;

            $all_users = $this->getUsersWithAutoAssignEnabled();
            $max_count = count($all_users) - 1;

            if ($current_index > $max_count) {
                $current_index = 0;
            }

            $this->configuration_repository->setConfiguration(["lead_round_robin_assignment_index" => $current_index + 1]);

            return $all_users[$current_index];
        } catch (\Exception $ex) {
            log_message('error', $ex->getMessage());
            return null;
        }
    }
}
