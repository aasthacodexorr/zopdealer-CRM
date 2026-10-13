<?php

namespace App\Libraries\WorkFlow;

use App\Libraries\WorkFlow\WorkFlowEntityPlaceholderInterface;
use Tenant\app\Models\DealCustomerPivotModel;
use Tenant\app\Models\DealInventoryPivotModel;
use Tenant\app\Repositories\DealCommonDependentRepository;
use Tenant\app\Repositories\DealRepository;
use Tenant\app\Repositories\InventoryRepository;
use Tenant\app\Repositories\TenantUserMetaDataRepository;

class WorkFlowDealPlaceholder extends WorkFlowPlaceholderAbstract implements WorkFlowEntityPlaceholderInterface
{

    public function setEntities(): void
    {
        if ($this->hasEntityId()) {
            $this->base_entity = (new DealRepository())->show($this->entity_id);
            if (!empty($this->base_entity)) {
                $this->base_entity['approver_name'] = $this->base_entity['salesperson_name'] =
                    $this->base_entity['approver_email'] = $this->base_entity['salesperson_email'] =
                    $this->base_entity['approver_phone'] = $this->base_entity['salesperson_phone'] = '';

                if (!empty($this->base_entity['approved_by']) && is_numeric($this->base_entity['approved_by'])) {
                    $assignee_objects = (new TenantUserMetaDataRepository())->getUserMetaDataById($this->base_entity['approved_by']);

                    if (is_array($assignee_objects)) {
                        foreach ($assignee_objects as $assignee) {
                            $this->base_entity['approver_name'] = $assignee['name'];
                            $this->base_entity['approver_email'] = $assignee['email'];
                            $this->base_entity['approver_phone'] = $assignee['phone'];
                        }
                    }
                }

                if (!empty($this->base_entity['salesperson']) && is_numeric($this->base_entity['salesperson'])) {
                    $salesperson_objects = (new TenantUserMetaDataRepository())->getUserMetaDataById($this->base_entity['salesperson']);

                    if (is_array($salesperson_objects)) {
                        foreach ($salesperson_objects as $salesperson) {
                            $this->base_entity['salesperson_name'] = $salesperson['name'];
                            $this->base_entity['salesperson_email'] = $salesperson['email'];
                            $this->base_entity['salesperson_phone'] = $salesperson['phone'];
                        }
                    }
                }

                $this->base_entity['customer_id'] = $this->base_entity['inventory_id'] = "";

                $customer_pivot_repository = (new DealCommonDependentRepository());
                $customer_pivot_repository->setModel(new DealCustomerPivotModel());

                $customer = $customer_pivot_repository->filterByDealIdAsArray($this->base_entity['id']);

                if (!empty($customer)) {
                    $this->base_entity['customer_id'] = $customer[0]['customer_id'];
                }

                $inventory_pivot_repository = (new DealCommonDependentRepository());
                $inventory_pivot_repository->setModel(new DealInventoryPivotModel());

                $inventory = $inventory_pivot_repository->filterByDealIdAsArray($this->base_entity['id']);

                if (!empty($inventory)) {
                    $inventory = (new InventoryRepository())->findSingleInventoryByStockId($inventory[0]['stock_no']);
                    if (!empty($inventory)) {
                        $this->base_entity['inventory_id'] = $inventory['inventory_id'];
                    }
                }
            }
        }
    }

    public function setModulePlaceholderMapping(
        $additional_placeholders = [],
        $dependent_classes = ['App\Libraries\WorkFlow\WorkFlowCustomerPlaceholder', 'App\Libraries\WorkFlow\WorkFlowInventoryPlaceholder', 'App\Libraries\WorkFlow\WorkFlowCommonPlaceholder']
    ): ?array {
        $this->dependend_classes = $dependent_classes;
        parent::setModulePlaceholderMapping($additional_placeholders, $dependent_classes);

        while (count($this->dependend_classes) > 0) {

            if (!empty($this->dependend_classes)) {
                $current_class_to_delegate = array_shift($this->dependend_classes);
                $entity_id = null;

                switch ($current_class_to_delegate) {
                    case 'App\Libraries\WorkFlow\WorkFlowCustomerPlaceholder':
                        $entity_id = $this->base_entity['customer_id'];
                        break;

                    case 'App\Libraries\WorkFlow\WorkFlowInventoryPlaceholder':
                        $entity_id = $this->base_entity['inventory_id'];
                        break;
                }

                $compiled_class = new $current_class_to_delegate($entity_id);

                $compiled_class->setEntities();
                $this->module_mapping = $compiled_class->setModulePlaceholderMapping($this->module_mapping, $this->dependend_classes);
            }
        }

        return $this->module_mapping;
    }

    public function setContentMapping(): void
    {
        if (!empty($this->base_entity)) {
            $content_mapping = [
                '{Deal Assignee Name}' => $this->base_entity['salesperson_name'],
                '{Deal Approver Name}' => $this->base_entity['approver_name'],
                '{Deal Assignee ID}' => $this->base_entity['salesperson'],
                '{Deal Approver ID}' => $this->base_entity['approved_by']
            ];

            $this->module_mapping['content'] = $content_mapping;
        }
    }

    public function setEmailAddressMapping(): void
    {

        if (!empty($this->base_entity)) {
            $email_mapping = [
                '{Deal Assignee Email}' => $this->base_entity['salesperson_email'],
                '{Deal Approver Email}' => $this->base_entity['approver_email'],
                'Deal Assignee Email' => $this->base_entity['salesperson_email'],
                'Deal Approver Email' => $this->base_entity['approver_email'],
            ];

            $this->module_mapping['email'] = $email_mapping;
        }
    }

    public function setSmsPhoneNumberMapping(): void
    {
        if (!empty($this->base_entity)) {
            $sms_mapping = [
                '{Deal Assignee Mobile}' => $this->base_entity['salesperson_phone'],
                '{Deal Approver Mobile}' => $this->base_entity['approver_phone'],
                'Deal Assignee Mobile' => $this->base_entity['salesperson_phone'],
                'Deal Approver Mobile' => $this->base_entity['approver_phone'],
            ];

            $this->module_mapping['sms'] = $sms_mapping;
        }
    }
}
