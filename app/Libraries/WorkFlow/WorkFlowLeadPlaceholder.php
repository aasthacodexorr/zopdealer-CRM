<?php

namespace App\Libraries\WorkFlow;

use App\Helpers\Utility;
use App\Libraries\WorkFlow\WorkFlowEntityPlaceholderInterface;
use Tenant\app\Repositories\InventoryRepository;
use Tenant\app\Repositories\LeadResponseRepository;
use Tenant\app\Repositories\TenantUserMetaDataRepository;

class WorkFlowLeadPlaceholder extends WorkFlowPlaceholderAbstract implements WorkFlowEntityPlaceholderInterface
{
    public function setEntities(): void
    {
        if ($this->hasEntityId()) {
            $this->base_entity = (new LeadResponseRepository())->show($this->entity_id);
            if (!empty($this->base_entity)) {
                $this->base_entity['assignee_name'] = $this->base_entity['assignee_mobile'] = $this->base_entity['assignee_email'] = '';

                if (!empty($this->base_entity['assignee']) && is_numeric($this->base_entity['assignee'])) {
                    $assignee_objects = (new TenantUserMetaDataRepository())->getUserMetaDataById($this->base_entity['assignee']);
                    if (is_array($assignee_objects)) {
                        foreach ($assignee_objects as $assignee) {
                            $this->base_entity['assignee_name'] = $assignee['name'];
                            $this->base_entity['assignee_email'] = $assignee['email'];
                            $this->base_entity['assignee_mobile'] = $assignee['phone'];
                        }
                    }
                }

                $this->base_entity['inventory_id'] = '';

                if (!empty($this->base_entity['stock_no'])) {
                    $inventory = (new InventoryRepository())->findSingleInventoryByStockId($this->base_entity['stock_no']);
                    if (!empty($inventory)) {
                        $this->base_entity['inventory_id'] = $inventory['inventory_id'];
                    }
                }
            }
        }
    }

    public function setModulePlaceholderMapping(
        $additional_placeholders = [],
        $dependent_classes = ['App\Libraries\WorkFlow\WorkFlowInventoryPlaceholder', 'App\Libraries\WorkFlow\WorkFlowCommonPlaceholder']
    ): ?array {
        $this->dependend_classes = $dependent_classes;
        parent::setModulePlaceholderMapping($additional_placeholders, $dependent_classes);

        if (!empty($this->dependend_classes)) {
            $current_class_to_delegate = array_shift($this->dependend_classes);
            if (!empty($this->base_entity['inventory_id'])) {
                $compiled_class = new $current_class_to_delegate($this->base_entity['inventory_id']);
            } else {
                $compiled_class = new $current_class_to_delegate();
            }

            $compiled_class->setEntities();
            $this->module_mapping = $compiled_class->setModulePlaceholderMapping($this->module_mapping, $this->dependend_classes);
        
        }

        return $this->module_mapping;
    }

    public function setContentMapping(): void
    {
        if (!empty($this->base_entity)) {

            $content_mapping = [
                '{Lead First Name}' => $this->base_entity['first_name'],
                '{Lead Full Name}' => $this->base_entity['first_name'] . ' ' . $this->base_entity['last_name'],
                '{Lead Mobile}' => Utility::formatPhoneNumber($this->base_entity['phone']),
                '{Lead Email}' => $this->base_entity['email'],
                '{Lead Assignee}' => $this->base_entity['assignee_name'],
                '{Lead Assignee ID}' => $this->base_entity['assignee'],
            ];

            $this->module_mapping['content'] = $content_mapping;
        }
    }

    public function setEmailAddressMapping(): void
    {

        if (!empty($this->base_entity)) {
            $email_mapping = [
                '{Lead Email}' => $this->base_entity['email'],
                '{Lead Assignee Email}' => $this->base_entity['assignee_email'],
                'Lead Email' => $this->base_entity['email'],
                'Lead Assignee Email' => $this->base_entity['assignee_email'],
            ];

            $this->module_mapping['email'] = $email_mapping;
        }
    }

    public function setSmsPhoneNumberMapping(): void
    {
        if (!empty($this->base_entity)) {
            $sms_mapping = [
                '{Lead Mobile}' => $this->base_entity['phone'],
                '{Lead Assignee Mobile}' => $this->base_entity['assignee_mobile'],
                'Lead Mobile' => $this->base_entity['phone'],
                'Lead Assignee Mobile' => $this->base_entity['assignee_mobile'],
            ];

            $this->module_mapping['sms'] = $sms_mapping;
        }
    }
}
