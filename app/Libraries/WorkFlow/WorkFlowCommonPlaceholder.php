<?php

namespace App\Libraries\WorkFlow;

use App\Helpers\Utility;
use App\Libraries\WorkFlow\WorkFlowEntityPlaceholderInterface;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\LocationRepository;

class WorkFlowCommonPlaceholder extends WorkFlowPlaceholderAbstract implements WorkFlowEntityPlaceholderInterface
{
    public function setEntities(): void
    {
        $location = (new LocationRepository())->getLocationByName();

        if (!empty($location)) {

            $website = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_website', 'value')->value;

            $db = \Config\Database::connect();
            $app_builder = $db->table('app')->get()->getRowArray();
            $app_name = $app_builder['name'];

            $review_url = "https://" . $app_name . '.' . getenv('MAIN_DOMAIN') . "/api/website/reviews/feedback/widget/render";

            $address =
                $location['business_name'] . ",\r\n" .
                $location['street'] . ",\r\n" .
                $location['city'] . ", " . $location['state']  . ", " . $location['zipcode'] . ",\r\n" .
                "Tel : " . Utility::format_phone_us($location['phone']) . ",\r\n" .
                "Email : " . $location['email'] . ",\r\n" .
                "Tax # " . $location['tax_number'];

            $this->base_entity = $location;
            $this->base_entity['website'] = $website;
            $this->base_entity['review_url'] = $review_url;
            $this->base_entity['address'] = $address;
        }

    }

    public function setContentMapping(): void
    {
        if (!empty($this->base_entity)) {
            $content_mapping = [
                '{Business Name}' => $this->base_entity['business_name'],
                '{Business Email}' => $this->base_entity['email'],
                '{Business Phone}' => Utility::formatPhoneNumber($this->base_entity['phone']),
                '{Business Mobile}' => Utility::formatPhoneNumber($this->base_entity['phone']),
                '{Business Address}' => $this->base_entity['address'],
                '{Business Review Link}' => $this->base_entity['review_url'],
                '{Business Website}' => $this->base_entity['website']
            ];

            $this->module_mapping['content'] = $content_mapping;
        }
    }

    public function setEmailAddressMapping(): void
    {

        if (!empty($this->base_entity)) {
            $email_mapping = [
                '{Business Email}' => $this->base_entity['email'],
                'Business Email' => $this->base_entity['email'],
            ];

            $this->module_mapping['email'] = $email_mapping;
        }
    }

    public function setSmsPhoneNumberMapping(): void
    {
        if (!empty($this->base_entity)) {
            $sms_mapping = [
                '{Business Phone}' => $this->base_entity['phone'],
                'Business Phone' => $this->base_entity['phone'],
                'Business Mobile' => Utility::formatPhoneNumber($this->base_entity['phone']),
            ];

            $this->module_mapping['sms'] = $sms_mapping;
        }
        
    }
}
