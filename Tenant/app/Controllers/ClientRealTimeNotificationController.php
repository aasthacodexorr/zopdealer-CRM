<?php

namespace Tenant\app\Controllers;

use Tenant\app\Repositories\ConfigurationRepository;

/**
 * ClientRealTimeNotificationController : Controller for the clients notification from the front end using API.
 */
class ClientRealTimeNotificationController extends \App\Controllers\BaseController
{
    public function sendEmail()
    {
        $data = $this->request->getPost();

        $validated = $this->validate([
            'recipient_email'  => [
                "label" => 'Recipient Email',
                "rules" => 'required|valid_email'
            ],
            'recipient_name'  => [
                "label" => 'Recipient Name',
                "rules" => 'required'
            ],
            'subject'  => [
                "label" => 'Subject',
                "rules" => 'required'
            ],
            'body'  => [
                "label" => 'Body',
                "rules" => 'required'
            ],
            'additional_recipient_email'  => [
                "label" => 'Additional Email',
                "rules" => 'permit_empty|valid_email'
            ],
        ]);

        if ($validated) {

            $configuration = new ConfigurationRepository();
            $account_email_name = $configuration->findConfigurationColumnsByKey('account_email_name', ['value']);
            $account_email_name = $account_email_name->value;

            $account_email_address = $configuration->findConfigurationColumnsByKey('account_email_address', ['value']);
            $account_email_address =  $account_email_address->value;

            $account_email_template_header = $configuration->findConfigurationColumnsByKey('account_email_template_header', ['value']);
            $account_email_template_header = $account_email_template_header->value;

            $account_email_template_footer = $configuration->findConfigurationColumnsByKey('account_email_template_footer', ['value']);
            $account_email_template_footer = $account_email_template_footer->value;

            try {
                $emailFactory = service('channelService')->getInstance("internal")['email'];
                $emailFactory->init(
                    getEnv('mailbox.smtp.host'),
                    getEnv('mailbox.smtp.username'),
                    getEnv('mailbox.smtp.password'),
                    getEnv('mailbox.smtp.port')
                );

                $emailFactory->setSubject($data['subject']);

                $body = view(
                    'emails/default_customer_reply',
                    [
                        'header' => $account_email_template_header,
                        'content' => $data['body'],
                        'footer' => $account_email_template_footer

                    ]
                );
                $emailFactory->setContent($body);
                $emailFactory->setSender($account_email_address, $account_email_name);
                $emailFactory->setRecipient($data['recipient_email'], $data['recipient_name']);

                if (!empty($data['additional_recipient_email'])) {
                    $emailFactory->setCC($data['additional_recipient_email']);
                }

                $emailFactory->setHTML(true);
                $emailFactory->send();
            } catch (\Exception $ex) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => $ex->getMessage()])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
            exit;
        }
    }
}
