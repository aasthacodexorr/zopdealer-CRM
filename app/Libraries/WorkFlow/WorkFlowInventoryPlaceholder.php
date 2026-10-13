<?php

namespace App\Libraries\WorkFlow;

use App\Libraries\WorkFlow\WorkFlowEntityPlaceholderInterface;
use Tenant\app\Repositories\InventoryRepository;

class WorkFlowInventoryPlaceholder extends WorkFlowPlaceholderAbstract implements WorkFlowEntityPlaceholderInterface
{
    public function setEntities(): void
    {
        if ($this->hasEntityId()) {
            $inventory = (new InventoryRepository())->show($this->entity_id);
            if (!empty($inventory)) {
                $this->base_entity = $inventory;
            }
        }
    }

    public function setModulePlaceholderMapping(
        $additional_placeholders = [],
        $dependent_classes = ['App\Libraries\WorkFlow\WorkFlowCommonPlaceholder']
    ): ?array {
        $this->dependend_classes = $dependent_classes;
        parent::setModulePlaceholderMapping($additional_placeholders, $dependent_classes);

        if (!empty($this->dependend_classes)) {
            $current_class_to_delegate = array_shift($this->dependend_classes);
            $compiled_class = new $current_class_to_delegate();
            $compiled_class->setEntities();
            $this->module_mapping = $compiled_class->setModulePlaceholderMapping($this->module_mapping, $this->dependend_classes);
        }

        return $this->module_mapping;
    }

    public function setContentMapping(): void
    {
        if (!empty($this->base_entity)) {
            $odometer = !empty($this->base_entity['odometer']) && is_numeric($this->base_entity['odometer']) ? number_format($this->base_entity['odometer']) . ' ' . $this->base_entity['km_miles'] : '';

            $content_mapping = [
                '{Inventory Year}' => $this->base_entity['year'],
                '{Inventory Make}' => $this->base_entity['make'],
                '{Inventory Model}' => $this->base_entity['model'],
                '{Inventory Trim}' => $this->base_entity['trim'],
                '{Inventory Stock Number}' => $this->base_entity['stock_no'],
                '{Inventory VIN}' => $this->base_entity['vin'],
                '{Inventory Odometer}' => $odometer,
            ];

            $this->module_mapping['content'] = $content_mapping;
        }
    }

    public function setEmailAddressMapping(): void
    {
    }

    public function setSmsPhoneNumberMapping(): void
    {
    }
}
