<?php

namespace Tenant\app\Libraries\Zoho\Api;

use Tenant\app\Libraries\Zoho\ZohoClient;

class Subscription extends ZohoClient
{
    /**
     * @param string $subscriptionId The subscription's id
     * @throws \Exception
     * @return array
     */
    public function getSubscriptionByCustomerId(string $customerId): array
    {
        $response = $this->sendRequest('GET',  self::ZOHO_SUBSCRIPTION_API_ENDPOINT . sprintf('subscriptions?customer_id=%s', $customerId));
        $responseData = $this->processResponse($response);

        return $responseData->subscriptions;
    }
}
