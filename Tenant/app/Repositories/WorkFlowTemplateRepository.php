<?php

namespace Tenant\app\Repositories;

use App\Libraries\WorkFlow\WorkFlowConstant;
use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\WorkFlowTemplateModel;

/**
 * WorkFlowTemplateRepository CRUD and pagination for the WorkFlow Template
 */
class WorkFlowTemplateRepository extends CRUDWithAdvanceRepository
{

    private array $modules = [
        WorkFlowConstant::DEAL => [
            'namespace' => 'Tenant\app\Repositories\DealRepository',
            'key'   => 'id',
            'mapping_namespace' => 'App\Libraries\WorkFlow\WorkFlowDealPlaceholder'
        ],
        WorkFlowConstant::CUSTOMER => [
            'namespace' => 'Tenant\app\Repositories\CustomerRepository',
            'key'   => 'id',
            'mapping_namespace' => 'App\Libraries\WorkFlow\WorkFlowCustomerPlaceholder'
        ],
        WorkFlowConstant::INVENTORY => [
            'namespace' => 'Tenant\app\Repositories\InventoryReportRepository',
            'key'   => 'inventory_id',
            'mapping_namespace' => 'App\Libraries\WorkFlow\WorkFlowInventoryPlaceholder'
        ],
        WorkFlowConstant::LEAD => [
            'namespace' => 'Tenant\app\Repositories\LeadResponseRepository',
            'key'   => 'id',
            'mapping_namespace' => 'App\Libraries\WorkFlow\WorkFlowLeadPlaceholder'
        ]
    ];

    public function __construct()
    {
        parent::__construct(new WorkFlowTemplateModel());
        $this->entity = 'CodeIgniter\Entity';
        $this->entityLabel = 'WorkFlow Template';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'workflow_template_id';
    }

    public function matchConditions(string $workflow_template_id, string $module, string $module_id): bool
    {
        $current_template = $this->show($workflow_template_id);
        $current_module = $this->modules[strtoupper($module)];

        if (!empty($current_module['namespace']) && !empty($current_module['key'] && strtoupper($module) === strtoupper($current_template['module']))) {
            $module_instance = new $current_module['namespace']();
            $entities = $module_instance->filterData($current_module['key'], $module_id)->index(null, null, null, $current_template['condition'], 20, null, false);
            if (count($entities['data_list']) > 0) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * setTemplateValueParser
     *
     * @param  string $workflow_template_id
     * @param  string $module
     * @param  string $module_id
     * @return array
     */
    public function setTemplateValueParser(string $workflow_template_id, string $module, string $module_id): ?array
    {
        $current_template = $this->show($workflow_template_id);
        $current_module = $this->modules[strtoupper($module)];
        if (
            !empty($current_module['mapping_namespace'])
            && !empty($current_module['key']
                && strtoupper($module) === strtoupper($current_template['module']))
        ) {

            $module_instance = new $current_module['mapping_namespace']($module_id);
            $module_instance->setEntities();
            $module_instance->setModulePlaceholderMapping();
            return $module_instance->getModule_mapping();
        }

        return [];
    }
}
