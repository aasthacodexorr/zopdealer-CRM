<?php

namespace App\Libraries\WorkFlow;

use App\Repositories\WorkFlowRepository;
use Tenant\app\Repositories\WorkFlowTemplateRepository;

/**
 * WorkFlowService      This class handles logic for mapping an entity with 
 */
class WorkFlowService
{
    private array $available_workflow_template_mapping = [];

    private WorkFlowTemplateRepository $workflow_template_repository;


    public function attach(string $event, array  $context): void
    {
        try {
            $module = !empty($context['module']) ? $context['module'] : '';
            $module_id = !empty($context['module_id']) ? $context['module_id'] : '';
            $database = db_connect()->database;

            $this->init();

            $event_based_template_mapping = array_filter($this->available_workflow_template_mapping, function ($workflow_template) use (&$event) {
                return strtolower($workflow_template['event']) === strtolower($event);
            });

            foreach ($event_based_template_mapping as $template_mapping) {
                $can_be_attached =  $this->workflow_template_repository->matchConditions($template_mapping['template_id'], strtoupper($module), $module_id);
                $template_id = $template_mapping['template_id'];
                if ($can_be_attached) {
                    $current_template = $this->workflow_template_repository->show($template_mapping['template_id']);
                    $context['condition'] = $current_template['condition'];

                    $stringified_context = base64_encode(json_encode($context));
                    exec("php index.php WorkFlowQueue accept $database $template_id $module $module_id  $stringified_context");
                }
            }
        } catch (\Exception $ex) {
            log_message('warning', $ex->getMessage());
        }
    }

    public function detach(array  $context): void
    {
        $this->init();
        $database = db_connect()->database;
        $context['database'] = $database;
        $stringified_context = base64_encode(json_encode($context));
        exec("php index.php WorkFlowQueue detach $stringified_context");
    }

    private function init()
    {
        $this->workflow_template_repository = new WorkFlowTemplateRepository();
        $current_enabled_workflow_templates = $this->workflow_template_repository->filterData("enable", 1)->index();
        foreach ($current_enabled_workflow_templates['data_list'] as $workflow_template) {
            $triggers = json_decode($workflow_template['trigger'], true);

            if (!empty($triggers)) {
                foreach ($triggers as $trigger) {
                    array_push($this->available_workflow_template_mapping, ['event' => $trigger, 'template_id' => $workflow_template['id']]);
                }
            }
        }
    }
}
