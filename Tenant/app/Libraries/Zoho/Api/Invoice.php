<?php

namespace Tenant\app\Libraries\Zoho\Api;

use Tenant\app\Libraries\Zoho\ZohoClient;

/**   
 * Zoho Subscription : Invoice API class functions
 * 
 * @package Zoho 
 */

class Invoice extends ZohoClient
{
    /** Provide list of invoices by subscription id 
     * 
     * @param string $subscriptionId The subscription's id
     * @return array
     */
    public function getInvoicesBySubscriptionIds(string $subscriptionId): array
    {
        $response = $this->sendRequest('GET',  self::ZOHO_SUBSCRIPTION_API_ENDPOINT . sprintf('invoices?filter_by=Status.All&subscription_id=%s', $subscriptionId));
        $responseData = $this->processResponse($response);

        return $responseData->invoices;
    }

    /** Provide details of invoice by invoice id 
     * 
     * @param string $invoiceId invoice id
     * @return array
     */
    public function getInvoiceById(string $invoiceId): object
    {
        $response = $this->sendRequest('GET',  self::ZOHO_SUBSCRIPTION_API_ENDPOINT . sprintf('invoices/%s', $invoiceId));
        $responseData = $this->processResponse($response);

        return $responseData->invoice;
    }
}
