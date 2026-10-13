<?php

namespace Tenant\app\Libraries\AttachmentAggregator;

use Tenant\app\Repositories\AttachmentRepository;
use Tenant\app\Repositories\InventoryCostRepository;

class InventoryCostAggregator extends AbstractAttachmentAggregator implements AttachmentAggregatorInterface
{
    public function __construct()
    {
        $this->module = AttachmentAggregatorConstant::INVENTORY;
    }

    public function getAllLinks(array $namespaces = [], string $current_module, string $current_module_id, array $selected_attachments = [], array $output_attachment_list = []): array
    {


        if ($this->hasRequiredInformation($current_module, $current_module_id, $selected_attachments, 'inventory_costs')) {

            $inventory_costs = (new InventoryCostRepository())->findByInventoryId($current_module_id);
            $attachment_repository = new AttachmentRepository();

            foreach ($inventory_costs as $key => $cost) {
                $entity_attachment = $attachment_repository->findByEntityDetails(
                    AttachmentAggregatorConstant::INVENTORY,
                    "EXPENSE",
                    $cost["id"]
                );

                $attachment_url =  !empty($entity_attachment["url"]) ? $entity_attachment["url"] : "";

                if (!empty($attachment_url)) {
                    $output_attachment_list['inventory/expenses/' . preg_replace('/[^a-z0-9]+/', '_', strtolower($cost['name']))

                       .'_' . substr($attachment_url, strrpos($attachment_url, '/') + 1)] = $attachment_url;
                }
            }
        }



        if (count($namespaces) > 0) {
            $attachment_aggregator_namespace = array_shift($namespaces);
            $attachment_aggregator_instance = new $attachment_aggregator_namespace();
            return $attachment_aggregator_instance->getAllLinks($namespaces, $current_module, $current_module_id, $selected_attachments, $output_attachment_list);
        }

        return $output_attachment_list;
    }
}
