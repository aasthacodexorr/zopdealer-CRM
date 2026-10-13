<?php

namespace Tenant\app\Controllers;

use App\Models\ClientModel;
use Carbon\Carbon;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Controller;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
use Tenant\app\Models\DealCustomerPivotModel;
use Tenant\app\Repositories\ActivityRepository;
use Tenant\app\Repositories\CustomerRepository;
use Tenant\app\Repositories\DealRepository;
use Tenant\app\Repositories\LeadResponseRepository;
use Tenant\app\Repositories\NotificationRepository;
use Tenant\app\Repositories\TenantUserMetaDataRepository;
use Tenant\app\Repositories\VendorRepository;

set_time_limit(0);

class ActivityNotificationQueue extends Controller
{

    protected   $host;
    protected   $port;
    protected   $user;
    protected   $pass;
    protected   $vhost;
    protected   $exchange;
    protected   $queue;
    protected   $routing_key;

    public function __construct()
    {
        $this->host = getenv('rabbit.host');
        $this->port = getenv('rabbit.port');
        $this->user = getenv('rabbit.user');
        $this->pass = getenv('rabbit.pass');
        $this->vhost = getenv('rabbit.vhost');
        $this->exchange = getenv('rabbit.exchange');
    }
    public function accept(...$args)
    {
        $clientModel = new ClientModel();
        $clients = $clientModel->asArray()->findAll(25, $args[0]);
        /* Set up connection **/
        $connection = new AMQPStreamConnection($this->host, $this->port, $this->user, $this->pass, $this->vhost);
        $channel = $connection->channel();
        $channel->queue_declare(NOTIFICATION_SMS_ROUTE, false, true, false, false);
        $channel->queue_declare(NOTIFICATION_PUSH_ROUTE, false, true, false, false);
        $channel->queue_declare(NOTIFICATION_EMAIL_ROUTE, false, true, false, false);
        $channel->exchange_declare($this->exchange, AMQPExchangeType::DIRECT, false, true, false);
        $channel->queue_bind(NOTIFICATION_SMS_ROUTE, $this->exchange, NOTIFICATION_SMS_ROUTE);
        $channel->queue_bind(NOTIFICATION_PUSH_ROUTE, $this->exchange, NOTIFICATION_PUSH_ROUTE);
        $channel->queue_bind(NOTIFICATION_EMAIL_ROUTE, $this->exchange, NOTIFICATION_EMAIL_ROUTE);

        foreach ($clients as $client) {
            try {
                $db = \Config\Database::connect();
                $db->setDatabase($client['subdomain'] . '_' . $client['client_hash']);

                $activities = (new ActivityRepository())->activitiesReadyForReminder();

                //loop through activies and get their types. Switch on Types and Save it in the respective queue to be processed.
                $activities = $activities['data_list'];
                $app_builder = $db->table('app')->get()->getRowArray();
                $app_name = $app_builder['name'];

                foreach ($activities as $activity) {
                    CLI::write(' [x] Recieved ' . json_encode($activity['notification_method']) . "\n");

                    $notificationRepository = new NotificationRepository();
                    //check for the name of the notification method, then queue the notification in the respective queue.
                    $user = (new TenantUserMetaDataRepository())->getUserMetaDataById($activity['assignee']);
                    $user = $user[0];

                    $activity['start_date'] = $this->convertUTCToLocalTime($activity['start_date'], $user['timezone']);
                    $activity['end_date'] = $this->convertUTCToLocalTime($activity['end_date'], $user['timezone']);

                    $activity['attendee_names'] = $this->getAttendeeName($activity['module'], $activity['module_id']);
                    $activity['module_identifier'] = $this->getModuleIdentifier($activity['module'], $activity['module_id']);
                    $activity['user_name'] = $user['name'];
                    //var_dump($activity["attendee_names"]);
                    //exit;

                    if (strstr($activity['notification_method'], "Email")) {
                        $sender = [
                            'name' => getenv('internal.name'),
                            'email_address' => getenv('internal.email_address')
                        ];

                        $recipient = null;

                        if (isset($user)) {
                            $recipient = [
                                'name' => $user['name'],
                                'email_address' => $user['email']
                            ];
                        }

                        $saved_notification = $this->saveEmailNotification($notificationRepository, $activity, $sender, $recipient);
                        if (!empty($saved_notification)) {
                            $msg = new AMQPMessage(json_encode($saved_notification), array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
                            $channel->basic_publish($msg, $this->exchange, NOTIFICATION_EMAIL_ROUTE);
                        }
                    }

                    if (strstr($activity['notification_method'], "Push Notification")) {
                        $sender = [
                            'name' => 'Zop Dealer',
                        ];

                        $recipient = null;

                        if (isset($user)) {
                            $recipient = [
                                'app_namespace' => $app_name,
                                'user_id' => $user['user_id']
                            ];
                        }

                        $saved_notification = $this->savePushNotification($notificationRepository, $activity, $sender, $recipient);
                        if (!empty($saved_notification)) {
                            $msg = new AMQPMessage(json_encode($saved_notification), array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
                            $channel->basic_publish($msg, $this->exchange, NOTIFICATION_PUSH_ROUTE);
                        }
                    }

                    if (strstr($activity['notification_method'], "SMS")) {
                        $sender = getenv('twilio.phone');

                        $recipient = null;

                        if (isset($user)) {
                            $recipient = $user['phone'];
                            $recipient = "+1" . $recipient;
                        }

                        $saved_notification = $this->saveSMSNotification($notificationRepository, $activity, $sender, $recipient);
                        if (!empty($saved_notification)) {
                            $msg = new AMQPMessage(json_encode($saved_notification), array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
                            $channel->basic_publish($msg, $this->exchange, NOTIFICATION_SMS_ROUTE);
                        }
                    }
                }
                // CLI::write(' [x] Recieved '. json_encode($activities). "\n");
            } catch (\Exception $ex) {
                CLI::write("Exception was thrown : " . $ex->getMessage());
                log_message('warning', $ex->getMessage());
            }
        }

        $channel->close();
        $connection->close();
    }

    protected function saveEmailNotification(NotificationRepository $notificationRepository, $activity, $sender, $recipient): ?array
    {
        try {

            $emailNotification = $notificationRepository->switchStateNotification('email');

            $existNotification = $notificationRepository
                ->filterData('identifier', 'Activity_' . $activity['id'])
                ->index($paginate = false);

            $exist_id = null;

            if (!empty($existNotification['data_list'])) {
                $exist_id = $existNotification['data_list'][0]['id'];
            }

            // CLI::write(json_encode($existNotification));

            $content = $activity['title'];


            if (is_file(APPPATH . 'Views/emails/default_activity_template.php')) {
                $content = view('emails/default_activity_template.php', $activity);
            }

            $saveNotification = [
                'sender' => json_encode($sender),
                'recipient' => json_encode($recipient),
                'content' => $content,
                'subject' => 'ZOP Notification Reminder',
                'context' => 'Activity',
                'identifier' => 'Activity_' . $activity['id'],
                'schedule' => Carbon::parse($activity['notification_date'], 'UTC')
            ];

            if (empty($exist_id)) {
                $response = $emailNotification->create($saveNotification);
                $saveNotification['id'] = $response['email_id'];
                $saveNotification['database'] = db_connect()->getDatabase();
                return $saveNotification;
            }

            return [];
        } catch (\Exception $ex) {
            CLI::write("Exception was thrown : " . $ex->getMessage());
            log_message('warning', $ex->getMessage());
            return null;
        }
    }

    protected function savePushNotification(NotificationRepository $notificationRepository, $activity, $sender, $recipient): ?array
    {
        try {

            $pushNotification = $notificationRepository->switchStateNotification('push');
            $existNotification = $notificationRepository
                ->filterData('identifier', 'Activity_' . $activity['id'])
                ->index($paginate = false);

            $exist_id = null;

            if (!empty($existNotification['data_list'])) {
                $exist_id = $existNotification['data_list'][0]['id'];
            }

            // CLI::write(json_encode($existNotification));

            $content = $activity['title'];
            if (is_file(APPPATH . 'Views/push/default_activity_template.php')) {
                $content = view('push/default_activity_template.php', $activity);
            }

            $title = $activity['activity_type'] . " " . "Reminder!";

            $saveNotification = [
                'sender' => json_encode($sender),
                'recipient' => json_encode($recipient),
                'content' => $content,
                'subject' => $title,
                'context' => 'Activity',
                'identifier' => 'Activity_' . $activity['id'],
                'schedule' => Carbon::parse($activity['notification_date'], 'UTC')
            ];

            if (empty($exist_id)) {
                $response = $pushNotification->create($saveNotification);
                $saveNotification['id'] = $response['push_id'];
                $saveNotification['database'] = db_connect()->getDatabase();
                return $saveNotification;
            }

            return [];
        } catch (\Exception $ex) {
            CLI::write("Exception was thrown : " . $ex->getMessage());
            log_message('warning', $ex->getMessage());
            return null;
        }
    }

    protected function saveSMSNotification(NotificationRepository $notificationRepository, $activity, $sender, $recipient): ?array
    {
        try {

            $smsNotification = $notificationRepository->switchStateNotification('sms');
            $existNotification = $notificationRepository
                ->filterData('identifier', 'Activity_' . $activity['id'])
                ->index($paginate = false);

            $exist_id = null;

            if (!empty($existNotification['data_list'])) {
                $exist_id = $existNotification['data_list'][0]['id'];
            }

            //CLI::write(json_encode($existNotification));

            $activity["activity_type"] = $activity["activity_type"] === "Appointment" ? "APPT" : $activity["activity_type"];
            $activity["activity_type"] = strtoupper($activity["activity_type"]);

            $activity['title'] = (strlen($activity['title']) > 80) ? substr($activity['title'], 0, 77) . '...' : $activity['title'];

            $content = $activity['title'];

            if (is_file(APPPATH . 'Views/sms/default_activity_template.php')) {
                $content = view('sms/default_activity_template.php', $activity);
            }

            $saveNotification = [
                'sender' => $sender,
                'recipient' => $recipient,
                'content' => $content,
                'subject' => $activity['title'],
                'context' => 'Activity',
                'identifier' => 'Activity_' . $activity['id'],
                'schedule' => Carbon::parse($activity['notification_date'], 'UTC')
            ];

            if (empty($exist_id)) {
                $response = $smsNotification->create($saveNotification);
                $saveNotification['id'] = $response['sms_id'];
                $saveNotification['database'] = db_connect()->getDatabase();
                return $saveNotification;
            }

            return [];
        } catch (\Exception $ex) {
            CLI::write("Exception was thrown : " . $ex->getMessage());
            return null;
        }
    }

    public function executeForEvenNumberOfClients()
    {
        $clientModel = new ClientModel();
        $current_incremental_count = 0;
        $client_count = $clientModel->countAllResults();
        for ($i = 0; $current_incremental_count < $client_count; $i++) {
            $current_incremental_count = $i * 25;
            --$current_incremental_count;
            $current_incremental_count = $current_incremental_count < 0 ? 0 : $current_incremental_count;
            if ($i % 2 == 0) {
                CLI::write($current_incremental_count);
                exec("php index.php ActivityNotificationQueue accept $current_incremental_count > /dev/null 2>&1 &");
                sleep(10);
            }
        }
    }

    public function executeForOddNumberOfClients()
    {
        $clientModel = new ClientModel();
        $current_incremental_count = 0;
        $client_count = $clientModel->countAllResults();
        for ($i = 0; $current_incremental_count < $client_count; $i++) {
            $current_incremental_count = $i * 25;
            --$current_incremental_count;
            $current_incremental_count = $current_incremental_count < 0 ? 0 : $current_incremental_count;
            if ($i % 2 != 0) {
                CLI::write($current_incremental_count);
                exec("php index.php ActivityNotificationQueue accept $current_incremental_count > /dev/null 2>&1 &");
                sleep(10);
            }
        }
    }

    /**
     * convertUTCToLocalTime
     *
     * @param  mixed $date
     * @param  mixed $timezone
     * @return void
     */
    protected function convertUTCToLocalTime($date, $timezone)
    {
        try {
            $date = Carbon::create($date, "UTC")->setTimezone($timezone)->toDayDateTimeString();
            return $date;
        } catch (\Exception $ex) {
            return "";
        }
    }

    protected function getModuleIdentifier($module, $module_id)
    {
        if (!empty($module) && !empty($module_id)) {
            switch ($module) {
                case 'Lead':
                    return "< L-" . $module_id . " >";
                    break;

                case 'Customer':
                    return "< C-" . $module_id . " >";
                    break;

                case 'Vendor':
                    return "< V-" . $module_id . " >";
                    break;

                case 'Deal':
                    return "< D-" . $module_id . " >";
                    break;

                default:
                    return "";
                    break;
            }
        } else {
            return "";
        }
    }

    protected function getAttendeeName($module, $module_id)
    {
        if (!empty($module) && !empty($module_id)) {
            switch ($module) {
                case 'Lead':
                    $lead = (new LeadResponseRepository())->show($module_id);
                    return !empty($lead) ? $lead['last_name'] . " " . $lead["first_name"] : "";
                    break;

                case 'Customer':
                    $customer = (new CustomerRepository())->show($module_id);
                    return !empty($customer) ? $customer['last_name'] . " " . $customer["first_name"] : "";
                    break;

                case 'Vendor':
                    $vendor = (new VendorRepository())->show($module_id);
                    return !empty($vendor) ? $vendor['last_name'] . " " . $vendor["first_name"] : "";
                    break;

                case 'Deal':
                    $customers = (new DealRepository())->setModel(new DealCustomerPivotModel())->findByDealId($module_id);
                    $attendees = "";
                    if (!empty($customers)) {
                        foreach ($customers as $customer) {
                            $customer_detail = (new CustomerRepository())->show($customer['customer_id']);
                            if ($attendees == "" && !empty($customer_detail)) {
                                $attendees = $customer_detail['last_name'] . " " . $customer_detail["first_name"];
                            } else if (!empty($customer_detail)) {
                                $attendees .= " & " . $customer_detail['last_name'] . " " . $customer_detail["first_name"];
                            }
                        }
                    }
                    return $attendees;

                default:
                    return "";
                    break;
            }
        } else {
            return "";
        }
    }
}
