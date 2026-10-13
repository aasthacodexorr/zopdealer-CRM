<?php

namespace Tenant\app\Controllers;

use phpDocumentor\Reflection\Types\Nullable;
use Tenant\app\Models\SMSConversationListModel;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\SMSConversationRepository;
use Tenant\app\Repositories\UserSMSConfigurationRepository;
use Twilio\Rest\Client;

/**
 * SMSConversationController This Controller has methods related to SMS Communication for the Dealerships with their customers.
 * 
 */
class SMSConversationController extends \App\Controllers\BaseController
{
    /**
     * repository This repository corresponds to the SMSConversationRepository which can be used by any method here.
     *
     * @var SMSConversationRepository
     */
    private $repository;

    /**
     * client This is the instance of Twilio API
     *
     * @var Client
     */
    private $client;

    /**
     * configuration_repository
     *
     * @var ConfigurationRepository
     */
    private $configuration_repository;

    /**
     * user_sms_configuration_repository
     *
     * @var UserSMSConfigurationRepository
     */
    private $user_sms_configuration_repository;

    public function __construct()
    {
        $this->repository = new SMSConversationRepository();
        $this->client = new Client(getenv('twilio.sid'), getenv('twilio.auth_token'));
        $this->configuration_repository = new ConfigurationRepository();
        $this->user_sms_configuration_repository = new UserSMSConfigurationRepository();
    }

    public function compose()
    {

        $data = $this->request->getPost();

        $is_sms_service_enabled = $this->configuration_repository->findConfigurationColumnsByKey('enable_sms_package', 'value');
        $available_credit = $this->configuration_repository->findConfigurationColumnsByKey('available_sms_credit', 'value');
        $allow_sending_sms  = intval($available_credit->value) > 0 && $is_sms_service_enabled->value ? true : false;


        $user_sms = $this->user_sms_configuration_repository->findByUserId(service('authPolicy')->id);
        $can_user_send_sms = !empty($user_sms) && service('authPolicy')->isTenant();

        $validated = $this->validate([
            'to'  => [
                "label" => 'Recipient Phone',
                "rules" => 'is_natural|exact_length[10]'
            ],
            'body' => [
                "label" => 'Body',
                "rules" => 'required|max_length[140]'
            ],
        ]);

        if ($validated && $can_user_send_sms && $allow_sending_sms) {

            try {

                $message = $this->client->messages->create('+1' . $data['to'], [
                    'from' => '+1' . $user_sms['phone'],
                    'body' => $data['body'],
                    "statusCallback" => "https://" . $_SERVER['HTTP_HOST'] . "/api/conversation/sms/update",
                    'maxPrice' => 0.03,
                ]);

                $save_data = [];
                $save_data['to'] = $data['to'];
                $save_data['from'] = $user_sms['phone'];
                $save_data['body'] = $data['body'];
                $save_data['sid'] = $message->sid;
                $save_data['status'] = $message->status;
                $save_data['direction'] = $message->direction;
                $save_data['read'] = 1;

                $response = $this->repository->create($save_data);

                $this->notifyUserWithNotification($user_sms['phone']);

                $this->configuration_repository->setConfiguration(["available_sms_credit" => intval($available_credit->value) - 1]);

                if (!empty($response['success'])) {
                    $this->response->setContentType('application/json')->setJSON($response)->send();
                } else {
                    $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
                }
            } catch (\Exception $ex) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($ex->getMessage())->send();
            }
        } else {

            if (!$validated) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
            }
        }
    }

    public function updateBySID()
    {
        $data = $this->request->getPost();

        $validated = $this->validate([
            'MessageSid'  => [
                "label" => 'Message Sid',
                "rules" => 'required'
            ],
            'SmsStatus' => [
                "label" => 'SMS Status',
                "rules" => 'required'
            ]
        ]);

        $sms_instance = $this->repository->findBySID($data['MessageSid']);

        if ($validated) {

            if ($sms_instance == null || !isset($sms_instance['id'])) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                    "errors" => "SMS couldn't be found."
                ])->send();
            } else {

                $sms_instance['status'] = $data['SmsStatus'];

                $response = $this->repository->update($sms_instance['id'], $sms_instance);
                if (isset($response['success'])) {

                    try {
                        $pushFactory = service('channelService')->getInstance("internal")['push'];
                        $pushFactory->setSubject("SMS:UPDATE:" . $sms_instance["to"]);
                        $pushFactory->setContent($sms_instance);

                        $db = \Config\Database::connect();
                        $app_builder = $db->table('app')->get()->getRowArray();
                        $app_name = $app_builder['name'];

                        $pushFactory->setRecipient($app_name, "*");
                        $pushFactory->send();
                    } catch (\Exception $th) {
                    }

                    $this->response->setContentType('application/json')->setJSON($response)->send();
                } else {
                    $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
                }
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    public function customerReply()
    {

        $available_credit = $this->configuration_repository->findConfigurationColumnsByKey('available_sms_credit', 'value');

        $data = $this->request->getPost();
        $validated = $this->validate([
            'MessageSid'  => [
                "label" => 'Message Sid',
                "rules" => 'required'
            ],
            'SmsStatus' => [
                "label" => 'SMS Status',
                "rules" => 'required'
            ],
            'To' => [
                "label" => 'Recipient Phone',
                "rules" => 'required'
            ],
            'From' => [
                "label" => 'Sender Phone',
                "rules" => 'required'
            ],
            'Body' => [
                "label" => 'Body',
                "rules" => 'required'
            ],

        ]);

        if ($validated) {

            $save_data = [];
            $save_data['to'] = str_replace("+1", "", $data['To']);
            $save_data['from'] = str_replace("+1", "", $data['From']);
            $save_data['body'] = $data['Body'];
            $save_data['sid'] = $data['MessageSid'];
            $save_data['status'] = $data['SmsStatus'];
            $save_data['direction'] = 'Incoming';
            $save_data['read'] = 0;

            $response = $this->repository->create($save_data);
            $this->configuration_repository->setConfiguration(["available_sms_credit" => intval($available_credit->value) - 1]);
            if (isset($response['success'])) {

                try {
                    $pushFactory = service('channelService')->getInstance("internal")['push'];
                    $pushFactory->setSubject("SMS:REPLY:" . $save_data["from"]);
                    $pushFactory->setContent($save_data);

                    $db = \Config\Database::connect();
                    $app_builder = $db->table('app')->get()->getRowArray();
                    $app_name = $app_builder['name'];

                    $pushFactory->setRecipient($app_name, "*");
                    $pushFactory->send();

                    $this->notifyUserWithNotification($save_data['to']);
                } catch (\Exception $th) {
                }

                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    public function getConversation()
    {

        $is_sms_service_enabled = $this->configuration_repository->findConfigurationColumnsByKey('enable_sms_package', 'value');
        $available_credit = $this->configuration_repository->findConfigurationColumnsByKey('available_sms_credit', 'value');
        $allow_sending_sms  = intval($available_credit->value) > 0 && $is_sms_service_enabled->value ? true : false;

        $user_sms = $this->user_sms_configuration_repository->findByUserId(service('authPolicy')->id);
        $data = $this->request->getPost();

        $validated = $this->validate([
            'to'  => [
                "label" => 'Recipient Phone',
                "rules" => 'is_natural|exact_length[10]'
            ]
        ]);

        if ($validated && !empty($user_sms) && service('authPolicy')->isTenant() && $allow_sending_sms) {

            $response = $this->repository->getConversation($user_sms['phone'], $data['to']);
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {

            if (!$validated) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
            }
        }
    }

    public function index()
    {

        $is_sms_service_enabled = $this->configuration_repository->findConfigurationColumnsByKey('enable_sms_package', 'value');
        $available_credit = $this->configuration_repository->findConfigurationColumnsByKey('available_sms_credit', 'value');
        $allow_sending_sms  = intval($available_credit->value) > 0 && $is_sms_service_enabled->value ? true : false;

        $user_sms = $this->user_sms_configuration_repository->findByUserId(service('authPolicy')->id);

        if (!empty($user_sms) && service('authPolicy')->isTenant() && $allow_sending_sms) {

            $this->repository->setModel(new SMSConversationListModel());

            $this->repository->setSalesperson_number($user_sms['phone']);

            $items = 100;
            $paginate = true;


            $column = 'timestamp';
            $order = 'DESC';


            if ($this->request->getGet('items')) {
                $items = $this->request->getGet('items');
            }

            if ($this->request->getGet('page_no')) {
                $_GET['page'] = $this->request->getGet('page_no');
            }

            if ($this->request->getGet('paginate')) {
                $paginate = $this->request->getGet('paginate');
                $paginate  = $paginate == 'false' ? false : true;
            }

            if ($this->request->getGet('column')) {
                $column = $this->request->getGet('column');
            }

            if ($this->request->getGet('order')) {
                $order = $this->request->getGet('order') == "ASC" ? "ASC" : "DESC";
            }


            $data = $this->repository;

            if ($this->request->getGet('search')) {
                $search = $this->request->getGet('search');
                if ($search != '') {
                    $data = $this->repository->search($search);
                }
            }

            $sort = '{"' . $column . '" : "' . $order  . '"}';

            $data = $data->index(null, $header = null, null, null, $items, $sort, $paginate, 'view_id', 'sms_conversations_list');

            $this->response->setContentType('application/json')->setJSON($data)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function updateRead()
    {

        $is_sms_service_enabled = $this->configuration_repository->findConfigurationColumnsByKey('enable_sms_package', 'value');
        $available_credit = $this->configuration_repository->findConfigurationColumnsByKey('available_sms_credit', 'value');
        $allow_sending_sms  = intval($available_credit->value) > 0 && $is_sms_service_enabled->value ? true : false;

        $user_sms = $this->user_sms_configuration_repository->findByUserId(service('authPolicy')->id);
        $data = $this->request->getPost();

        $validated = $this->validate([
            'to'  => [
                "label" => 'Recipient Phone',
                "rules" => 'is_natural|exact_length[10]'
            ]
        ]);

        if ($validated && !empty($user_sms) && service('authPolicy')->isTenant() && $allow_sending_sms) {

            $response = $this->repository->updateRead($user_sms['phone'], $data['to']);
            $this->response->setContentType('application/json')->setJSON($response)->send();

            $this->notifyUserWithNotification($user_sms['phone']);
            
        } else {

            if (!$validated) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
            }
        }
    }

    public function userSMSStats()
    {

        $is_sms_service_enabled = $this->configuration_repository->findConfigurationColumnsByKey('enable_sms_package', 'value');
        $available_credit = $this->configuration_repository->findConfigurationColumnsByKey('available_sms_credit', 'value');
        $allow_sending_sms  = intval($available_credit->value) > 0 && $is_sms_service_enabled->value ? true : false;

        $user_sms = $this->user_sms_configuration_repository->findByUserId(service('authPolicy')->id);

        $this->response->setContentType('application/json')->setJSON([
            'is_sms_service_enabled' => $allow_sending_sms,
            'available_sms_credit' => $allow_sending_sms ? $available_credit->value : "0",
            'sms_phone_number' => $user_sms['phone'],
            'unread' => !empty($user_sms['phone']) ? $this->repository->getUnreadCount($user_sms['phone']) : NULL
        ])->send();
    }

    private function notifyUserWithNotification($phone_no){
        $pushFactory = service('channelService')->getInstance("internal")['push'];
        $pushFactory->setSubject("SMS:NOTIFY:" . $phone_no);
        $pushFactory->setContent("");

        $db = \Config\Database::connect();
        $app_builder = $db->table('app')->get()->getRowArray();
        $app_name = $app_builder['name'];
        $pushFactory->setRecipient($app_name, "*");
        $pushFactory->send();
    }
}
