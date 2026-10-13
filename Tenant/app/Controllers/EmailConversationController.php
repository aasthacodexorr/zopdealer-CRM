<?php

namespace Tenant\app\Controllers;

use App\Libraries\Mailbox\IMAP;
use Tenant\app\Repositories\TenantUserMetaDataRepository;
use Tenant\app\Repositories\UserEmailConfigurationRepository;

class EmailConversationController extends \App\Controllers\BaseController
{

    /**
     * user_email_repository This retrives the configurations for the user.
     *
     * @var mixed
     */
    private $user_email_repository;

    private const NO_EMAIL_CONFIGURATION = 'User Configuration Not Found.';

    public function __construct()
    {
        $this->user_email_repository = new UserEmailConfigurationRepository();
    }
    /**
     * getConversation This method expects recipient with whome the conversation has taken place.
     *
     * @return void
     */
    public function getConversation()
    {

        $attachment = null;
        $user = $this->user_email_repository->findByUserId(service('authPolicy')->id);

        $validated = $this->validate([
            'recipient'  => [
                "label" => 'Recipient',
                "rules" => 'required|valid_email'
            ],
        ]);

        if ($validated && !empty($user)) {
            $imap = new IMAP($user['incoming_hostname'], $user['email'], $user['password'], $user['incoming_port'], $user['incoming_secure'], $attachment);
            $response = $imap->getEmailConversation($this->request->getPost('recipient'));

            if (!empty($response['errors']) || empty($response)) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            }
        } else {
            if (empty($user)) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => EmailConversationController::NO_EMAIL_CONFIGURATION])->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
            }
        }
    }

    /**
     * getEmail 
     *
     * @param  mixed $id
     * @return void
     */
    public function getEmailDetails($id)
    {

        $attachment = null;
        $user = $this->user_email_repository->findByUserId(service('authPolicy')->id);

        $validated = $this->validate([
            'mailbox'  => [
                "label" => 'Mailbox',
                "rules" => 'required'
            ]
        ]);

        if ($validated  && !empty($user)) {
            $imap = new IMAP($user['incoming_hostname'], $user['email'], $user['password'], $user['incoming_port'], $user['incoming_secure'], $attachment);
            $response = $imap->getSingleEmail($this->request->getPost('mailbox'), (int) $id);

            if (!empty($response['errors'])) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            }
        } else {
            if (empty($user)) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => EmailConversationController::NO_EMAIL_CONFIGURATION])->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
            }
        }
    }

    /**
     * composeEmail 
     *
     * @return void
     */
    public function composeEmail()
    {

        $attachment = null;
        $user = $this->user_email_repository->findByUserId(service('authPolicy')->id);

        $validation = [
            'to'  => [
                "label" => 'To',
                "rules" => 'required|regex_match[/^(\s?[^\s,]+@[^\s,]+\.[^\s,]+\s?,)*(\s?[^\s,]+@[^\s,]+\.[^\s,]+)$/i]'
            ],
            'cc'  => [
                "label" => 'CC',
                "rules" => 'permit_empty|regex_match[/^(\s?[^\s,]+@[^\s,]+\.[^\s,]+\s?,)*(\s?[^\s,]+@[^\s,]+\.[^\s,]+)$/i]'
            ],
            'subject'  => [
                "label" => 'Subject',
                "rules" => 'required'
            ],
            'body'  => [
                "label" => 'Body',
                "rules" => 'permit_empty'
            ]
        ];

        if (array_key_exists('attachment', $this->request->getFiles())) {
            $validation['attachment'] = [
                'uploaded[attachment]',
                'mime_in[attachment,application/pdf,image/jpg,image/jpeg,image/gif,image/png]',
                'max_size[attachment,32000]',
            ];
        }

        //validate email fields
        $validated = $this->validate($validation);

        if ($validated && !empty($user)) {
            try {

                $auth = $user['auth'] == '1' ? true : false;

                $emailFactory = service('channelService')->getInstance("personal")['email'];
                $emailFactory->init(
                    $user['outgoing_hostname'],
                    $user['email'],
                    $user['password'],
                    $user['outgoing_port'],
                    $user['outgoing_secure'],
                    $auth,
                    $user['incoming_hostname'],
                    $user['incoming_port'],
                    $user['incoming_secure']
                );
                $emailFactory->setSubject($this->request->getPost('subject'));
                $emailFactory->setContent($this->request->getPost('body'));
                $emailFactory->setSender($user['email'], $user['name']);

                $recipient = $this->request->getPost('to');
                $recipient = explode(",", $recipient);

                foreach ($recipient as $a_recipient) {
                    $emailFactory->setRecipient(trim($a_recipient));
                }

                if (!empty($this->request->getPost('cc'))) {
                    $cc = $this->request->getPost('cc');

                    $cc = explode(",", $cc);
                    foreach ($cc as $a_cc) {
                        $emailFactory->setCC(trim($a_cc));
                    }
                }

                if (array_key_exists('attachment', $this->request->getFiles())) {
                    $file = $this->request->getFile('attachment');
                    $file_tmp  = $_FILES['attachment']['tmp_name'];
                    $name = $file->getClientName();
                    $emailFactory->AddAttachment($file_tmp, $name);
                }

                $response = $emailFactory->send();

                if (!empty($response['errors'])) {
                    $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
                } else {
                    $this->response->setContentType('application/json')->setJSON($response)->send();
                }
            } catch (\Exception $ex) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => $ex->getMessage()])->send();
            }
        } else {
            if (empty($user)) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => EmailConversationController::NO_EMAIL_CONFIGURATION])->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
            }
        }
    }
}
