<?php

namespace App\Libraries\WorkFlow;

use App\Helpers\Utility;
use App\Libraries\WorkFlow\WorkFlowEntityPlaceholderInterface;
use Tenant\app\Libraries\Shortner\ZopUrlShortner;
use Tenant\app\Repositories\CustomerRepository;
use Tenant\app\Repositories\TenantUserMetaDataRepository;

class WorkFlowCustomerPlaceholder  extends WorkFlowPlaceholderAbstract implements WorkFlowEntityPlaceholderInterface
{
    public function setEntities(): void
    {
        if ($this->hasEntityId()) {
            $this->base_entity = (new CustomerRepository())->show($this->entity_id);
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

                $db = \Config\Database::connect();
                $app_builder = $db->table('app')->get()->getRowArray();
                $app_name = $app_builder['name'];

                $encrypted_id = Utility::shortEncryptString(md5(db_connect()->database) . "-" . $this->entity_id, getenv("password.encryption"));
                $review_url = "https://" . $app_name . '.' . getenv('MAIN_DOMAIN') . "/api/website/reviews/feedback/render/" . $encrypted_id;
                $short_review_url = ZopUrlShortner::getShortUrl($review_url);

                $this->base_entity['review_url'] = $review_url;
                $this->base_entity['short_review_url'] = $short_review_url;
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
            $content_mapping = [
                '{Customer First Name}' => $this->base_entity['first_name'],
                '{Customer Full Name}' => $this->base_entity['first_name'] . ' ' . $this->base_entity['last_name'],
                '{Customer Mobile}' => Utility::formatPhoneNumber($this->base_entity['mobile']),
                '{Customer Email}' => $this->base_entity['email'],
                '{Personalized Review Link}' => $this->base_entity['review_url'],
                '{Personalized Short Review Link}' => $this->base_entity['short_review_url'],
                '{Customer Assignee}' => $this->base_entity['assignee_name'],
                '{Customer Assignee ID}' => $this->base_entity['assignee'],
            ];

            $this->module_mapping['content'] = $content_mapping;
        }
    }

    public function setEmailAddressMapping(): void
    {

        if (!empty($this->base_entity)) {
            $email_mapping = [
                '{Customer Email}' => $this->base_entity['email'],
                '{Customer Assignee Email}' => $this->base_entity['assignee_email'],
                'Customer Email' => $this->base_entity['email'],
                'Customer Assignee Email' => $this->base_entity['assignee_email'],
            ];

            $this->module_mapping['email'] = $email_mapping;
        }
    }

    public function setSmsPhoneNumberMapping(): void
    {
        if (!empty($this->base_entity)) {
            $sms_mapping = [
                '{Customer Mobile}' => $this->base_entity['mobile'],
                'Customer Mobile' => $this->base_entity['mobile'],
                '{Customer Assignee Mobile}' => $this->base_entity['assignee_mobile'],
                'Customer Assignee Mobile' => $this->base_entity['assignee_mobile'],
            ];

            $this->module_mapping['sms'] = $sms_mapping;
        }
    }
}
