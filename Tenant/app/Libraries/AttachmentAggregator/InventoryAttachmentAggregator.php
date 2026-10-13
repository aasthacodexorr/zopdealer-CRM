<?php

namespace Tenant\app\Libraries\AttachmentAggregator;

use Tenant\app\Repositories\InventoryRepository;

class InventoryAttachmentAggregator extends AbstractAttachmentAggregator implements AttachmentAggregatorInterface
{
    public function __construct()
    {
        $this->module = AttachmentAggregatorConstant::INVENTORY;
    }

    public function getAllLinks(array $namespaces = [], string $current_module, string $current_module_id, array $selected_attachments = [], array $output_attachment_list = []): array
    {

        $inventory_files = (new InventoryRepository())->fileList($current_module_id);

        foreach ($inventory_files as $inventory_file) {
            if ($inventory_file['header'] === 'Vehicle History Report') {
                if (
                    $this->hasRequiredInformation($current_module, $current_module_id, $selected_attachments, 'inventory_vehicle_history_report')
                ) {
                    $attachment_url =  !empty($inventory_file["file_url"]) ? $inventory_file["file_url"] : "";
                    if (!empty($attachment_url)) {
                        $output_attachment_list['inventory/files/' . preg_replace('/[^a-z0-9]+/', '_', strtolower($inventory_file['header']))

                            . '_' . substr($attachment_url, strrpos($attachment_url, '/') + 1)] = $attachment_url;
                    }
                }
            }

            if ($inventory_file['header'] === 'Vehicle Repair Report') {
                if (
                    $this->hasRequiredInformation($current_module, $current_module_id, $selected_attachments, 'inventory_vehicle_repair_report')
                ) {
                    $attachment_url =  !empty($inventory_file["file_url"]) ? $inventory_file["file_url"] : "";
                    if (!empty($attachment_url)) {
                        $output_attachment_list['inventory/files/' . preg_replace('/[^a-z0-9]+/', '_', strtolower($inventory_file['header']))

                            . '_' . substr($attachment_url, strrpos($attachment_url, '/') + 1)] = $attachment_url;
                    }
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
