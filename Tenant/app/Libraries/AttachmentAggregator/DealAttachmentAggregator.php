<?php

namespace Tenant\app\Libraries\AttachmentAggregator;

use App\Helpers\Utility;

class DealAttachmentAggregator extends AbstractAttachmentAggregator implements AttachmentAggregatorInterface
{
    public function __construct()
    {
        $this->module = AttachmentAggregatorConstant::DEAL;
    }
    public function getAllLinks(array $namespaces = [], string $current_module, string $current_module_id, array $selected_attachments = [], array $output_attachment_list = []): array
    {


        $available_attachments = [
            'deal_bill_of_sale',
            'deal_test_drive',
            'deal_deposit_agreement',
            'deal_credit_application',
            'deal_trade_in',
            'deal_trade_in_disclosures',
            'deal_vehicle_history_report',
            'deal_customer_worksheet',
            'deal_open_disclosure_agreement',
            'deal_alternate_bill_of_sale',
            'deal_exempt_motor_vehicle',
        ];

        foreach ($available_attachments as $available_attachment) {
            if ($this->hasRequiredInformation($current_module, $current_module_id, $selected_attachments, $available_attachment)) {
                $output_attachment_list['DEAL_' . $current_module_id . '/' . $available_attachment . ".pdf"] = $this->getAttachmentUrl($available_attachment, $current_module_id);
            }
        }



        if (count($namespaces) > 0) {
            $attachment_aggregator_namespace = array_shift($namespaces);
            $attachment_aggregator_instance = new $attachment_aggregator_namespace();
            return $attachment_aggregator_instance->getAllLinks($namespaces, $current_module, $current_module_id, $selected_attachments, $output_attachment_list);
        }

        return $output_attachment_list;
    }

    private function getAttachmentUrl(String $attachment, String $entity_id)
    {
        $attachment_slug = 'private-billofsale';

        $db = \Config\Database::connect();
        $app_builder = $db->table('app')->get()->getRowArray();
        $app_name = $app_builder['name'];

        switch ($attachment) {
            case 'deal_bill_of_sale':
                $attachment_slug = 'private-billofsale';
                break;

            case 'deal_test_drive':
                $attachment_slug = 'private-test-drive';
                break;

            case 'deal_deposit_agreement':
                $attachment_slug = 'private-deposit-agreement';
                break;

            case 'deal_credit_application':
                $attachment_slug = 'private-credit-application';
                break;

            case 'deal_trade_in':
                $attachment_slug = 'private-trade-in';
                break;

            case 'deal_trade_in_disclosures':
                $attachment_slug = 'private-trade-in-disclosures';
                break;

            case 'deal_vehicle_history_report':
                $attachment_slug = 'private-vehicle-history-report-acknowledgement';
                break;

            case 'deal_customer_worksheet':
                $attachment_slug = 'private-customer-worksheet';
                break;

            case 'deal_open_disclosure_agreement':
                $attachment_slug = 'private-open-disclosure-agreement';
                break;

            case 'deal_alternate_bill_of_sale':
                $attachment_slug = 'private-alternate-billofsale';
                break;

            case 'deal_exempt_motor_vehicle':
                $attachment_slug = 'private-exempt-motor-vehicle';
                break;

            default:
                break;
        }

        $attachment_url = "https://" . $app_name . '.' . getenv('MAIN_DOMAIN') . "/api/website/private/pdf/" . $attachment_slug . "/view/" .
            Utility::encryptString(md5(db_connect()->database) . "-" . $entity_id, getenv("password.encryption"));

        return $attachment_url;
    }
}
