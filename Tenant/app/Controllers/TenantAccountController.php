<?php

namespace Tenant\app\Controllers;

use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\TenantUserMetaDataRepository;

class TenantAccountController extends \App\Controllers\BaseController
{
    /**
     * configuration_repository
     *
     * @var ConfigurationRepository
     */
    private $configuration_repository;

    public function __construct()
    {
        $this->configuration_repository = new ConfigurationRepository();
    }

    public function sendCloseAccountRequest()
    {
        if (service('authPolicy')->hasOwnerRole()) {

            $data = $this->request->getPost();

            $validated = $this->validate([
                'feedback'  => [
                    "label" => 'Feedback',
                    "rules" => 'required'
                ],
            ]);

            if ($validated) {

                $user_details = (new TenantUserMetaDataRepository())->getUserMetaDataById(service('authPolicy')->currentUserID());

                $configuration = new ConfigurationRepository();
                $account_email_name = $configuration->findConfigurationColumnsByKey('account_email_name', ['value']);
                $account_email_name = $account_email_name->value;

                $account_email_address = $configuration->findConfigurationColumnsByKey('account_email_address', ['value']);
                $account_email_address =  $account_email_address->value;

                $account_email_template_header = $configuration->findConfigurationColumnsByKey('account_email_template_header', ['value']);
                $account_email_template_header = $account_email_template_header->value;

                $account_email_template_footer = $configuration->findConfigurationColumnsByKey('account_email_template_footer', ['value']);
                $account_email_template_footer = $account_email_template_footer->value;


                $body = '';
                if (!empty($user_details[0])) {
                    if (!empty($user_details[0]['name'])) {
                        $body .= '<Br> Name' . ' ' . $user_details[0]['name'];
                    }

                    if (!empty($user_details[0]['email'])) {
                        $body .= '<Br> Email' . ' ' . $user_details[0]['email'];
                    }
                }

                $body .= "<Br> Reason" . ' ' . $data['feedback'];

                $subject = strip_tags($account_email_template_header);
                $subject .= " - ACCOUNT CANCELLATION REQUEST";

                try {
                    $emailFactory = service('channelService')->getInstance("internal")['email'];
                    $emailFactory->init(
                        getEnv('mailbox.smtp.host'),
                        getEnv('mailbox.smtp.username'),
                        getEnv('mailbox.smtp.password'),
                        getEnv('mailbox.smtp.port')
                    );

                    $emailFactory->setSubject("ACCOUNT CANCELLATION REQUEST");

                    $body = view(
                        'emails/default_customer_reply',
                        [
                            'header' => $account_email_template_header,
                            'content' => $body,
                            'footer' => $account_email_template_footer

                        ]
                    );

                    $emailFactory->setSubject($subject);
                    $emailFactory->setContent($body);
                    $emailFactory->setSender($account_email_address, $account_email_name);
                    $emailFactory->setRecipient("accounting@zopmedia.com", "Zop Media");
                    $emailFactory->setCC("nelly@zopmedia.com");
                    $emailFactory->setHTML(true);
                    $emailFactory->send();
                } catch (\Exception $ex) {
                    $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => $ex->getMessage()])->send();
                }
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
                exit;
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function changeLogo()
    {
        if (service('authPolicy')->hasElevatedRoles()) {

            $validated = $this->validate([
                'file' => [
                    'uploaded[file]',
                    'mime_in[file,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
                    'max_size[file,32000]',
                ],
            ]);

            if ($validated) {
                $file = $this->request->getFile('file');
                $name = $file->getRandomName();
                $name = db_connect()->database . '_' . $name;

                $file->move(ROOT_BUSINESS_LOGO, $name);

                $this->configuration_repository->setConfiguration(["business_logo" => $name]);

                $this->response->setContentType('application/json')->setJSON(["success" => "Logo changed successfully."])->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }
}
