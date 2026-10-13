<?php

namespace Tenant\app\Controllers;

set_time_limit(0);

use App\Helpers\Utility;
use App\Libraries\Schedule\ScheduleConstant;
use App\Repositories\ScheduleRepository;
use Carbon\Carbon;
use CodeIgniter\Controller;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
use CodeIgniter\CLI\CLI;
use Tenant\app\Libraries\Report\ReportFactory;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\CustomerReportViewRepository;
use Tenant\app\Repositories\DealReportViewRepository;
use Tenant\app\Repositories\InventoryCostReportViewRepository;
use Tenant\app\Repositories\InventoryPurchaseReportViewRepository;
use Tenant\app\Repositories\InventoryReportViewRepository;
use Tenant\app\Repositories\LeadReportViewRepository;
use Tenant\app\Repositories\NotificationRepository;
use Tenant\app\Repositories\PaymentReportViewRepository;
use Tenant\app\Repositories\ScheduleTemplateRepository;
use Tenant\app\Repositories\TenantUserMetaDataRepository;

class ScheduleQueue extends Controller
{
    protected $host;
    protected $port;
    protected $user;
    protected $pass;
    protected $vhost;
    protected $exchange;
    protected $queue;
    protected $routing_key;
    protected $repository;
    protected $schedule_template_repository;
    protected $report_process_queue;
    protected $report_process_routing_key;
    public function __construct()
    {
        $this->host = getenv('rabbit.host');
        $this->port = getenv('rabbit.port');
        $this->user = getenv('rabbit.user');
        $this->pass = getenv('rabbit.pass');
        $this->vhost = getenv('rabbit.vhost');
        $this->exchange = getenv('rabbit.exchange');
        $this->queue = SCHEDULE_ROUTE;
        $this->routing_key = SCHEDULE_ROUTE;
        $this->repository = new ScheduleRepository();
        $this->schedule_template_repository = new ScheduleTemplateRepository();
        $this->report_process_queue = REPORT_PROCESS_ROUTE;
        $this->report_process_routing_key = REPORT_PROCESS_ROUTE;
    }

    public function accept(...$arg): void
    {
        $connection = new AMQPStreamConnection($this->host, $this->port, $this->user, $this->pass, $this->vhost);
        $channel = $connection->channel();

        $channel->queue_declare($this->queue, false, true, false, false);
        $channel->exchange_declare($this->exchange, AMQPExchangeType::DIRECT, false, true, false);

        $channel->queue_bind($this->queue, $this->exchange, $this->routing_key);

        $db = \Config\Database::connect();
        $db->setDatabase($arg[0]);

        $schedule_template = $this->schedule_template_repository->show($arg[1]);

        if (!empty($schedule_template)) {

            $datetime = $this->repository->convertTimeToUTC($schedule_template['datetime'], $schedule_template['timezone']);

            $data = [
                'database' => $arg[0],
                'schedule_template_id' => $arg[1],
                'module' => $schedule_template['module'],
                'module_id' => $schedule_template['module_id'],
                'frequency' => $schedule_template['frequency'],
                'timezone' => $schedule_template['timezone'],
                'status' => $arg[2],
                'schedule' => $datetime,
                'search_keyword' => $schedule_template['module'] . ' ' . $schedule_template['module_id'] . ' ' . $arg[1] . ' ' . $arg[0],
                'unique_index' => md5($schedule_template['module'] . ' ' . $schedule_template['module_id'] . ' ' . $arg[1] . ' ' . $arg[0])
            ];


            CLI::write(' [x] Recieved ' . json_encode($data) . "\n");

            $msg = new AMQPMessage(json_encode($data),    array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
            $channel->basic_publish($msg, $this->exchange, $this->routing_key);
        }

        $channel->close();
        $connection->close();
    }

    public function save()
    {
        $start_time = time();
        $timeout = 600;

        $connection = new AMQPStreamConnection(
            $this->host,
            $this->port,
            $this->user,
            $this->pass,
            $this->vhost,
            false,
            'AMQPLAIN',
            null,
            'en_US',
            3.0,
            120.0,
            null,
            true,
            60.0,
        );
        $channel = $connection->channel();
        $channel->basic_qos(null, 20, null);
        $channel->queue_declare($this->queue, false, true, false, false);
        $channel->exchange_declare($this->exchange, AMQPExchangeType::DIRECT, false, true, false);

        $channel->queue_bind($this->queue, $this->exchange, $this->routing_key);

        $consumerTag = $channel->basic_consume($this->queue, '', false, false, false, false, function ($msg) {
            try {
                $schedule = json_decode($msg->body, true);
                CLI::write(json_encode($schedule) . "\n");

                $this->repository->createOrUpdate($schedule);

                CLI::write(' [x] Recieved ' . $msg->body . "\n");
                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
            } catch (\Exception $ex) {
                CLI::write(' [x] Error ' . $ex->getMessage() . "\n");
                log_message('warning', 'Schedule::save :' . $ex->getMessage());
                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
            }
        });

        while ($channel->is_consuming()) {
            try {
                CLI::write('Ping ' . "\n");
                $channel->wait(null, false, 360);
            } catch (\PhpAmqpLib\Exception\AMQPTimeoutException $e) {
                // ignore timeout, continue loop
            }

            if ((time() - $start_time) >= $timeout) {
                echo "Terminating consumer...\n";

                // Stop consuming
                $channel->basic_cancel($consumerTag);
                break;
            }
        }

        CLI::write('.....');

        $channel->close();
        $connection->close();
    }

    public function fetchActive()
    {
        $connection = new AMQPStreamConnection($this->host, $this->port, $this->user, $this->pass, $this->vhost);
        $channel = $connection->channel();

        $channel->queue_declare($this->report_process_queue, false, true, false, false);
        $channel->exchange_declare($this->exchange, AMQPExchangeType::DIRECT, false, true, false);

        $channel->queue_bind($this->report_process_queue, $this->exchange, $this->report_process_routing_key);

        $schedules = $this->repository->activeSchedule();

        if (!empty($schedules['data_list'])) {
            foreach ($schedules['data_list'] as $key => $schedule) {

                try {

                    $updated_schedule = $this->repository->convertTimeToUTC($schedule['schedule'], "UTC", $schedule['frequency']);
                    $schedule['schedule'] = $updated_schedule;
                    $this->repository->createOrUpdate($schedule);

                    $msg = new AMQPMessage(json_encode($schedule),    array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
                    $channel->basic_publish($msg, $this->exchange, $this->report_process_routing_key);

                    $db = \Config\Database::connect();
                    $db->setDatabase($schedule['database']);

                    $builder = $db->table('schedule_templates');
                    $builder->set('datetime', $updated_schedule->tz($schedule['timezone']))->where('id', $schedule['schedule_template_id'])->limit(1)->update();

                    $db->setDatabase(getenv('database.default.database'));

                    CLI::write(' [x] Recieved ' . json_encode(["date" => $updated_schedule]) . "\n");
                } catch (\Exception $ex) {
                    log_message("notice", $ex->getMessage());
                }
            }
        }

        $channel->close();
        $connection->close();
    }

    public function process()
    {
        $start_time = time();
        $timeout = 180;

        $connection = new AMQPStreamConnection(
            $this->host,
            $this->port,
            $this->user,
            $this->pass,
            $this->vhost,
            false,
            'AMQPLAIN',
            null,
            'en_US',
            3.0,
            120.0,
            null,
            true,
            60.0,
        );
        $channel = $connection->channel();
        $channel->basic_qos(null, 20, null);
        $channel->queue_declare($this->report_process_queue, false, true, false, false);
        $channel->exchange_declare($this->exchange, AMQPExchangeType::DIRECT, false, true, false);
        $channel->queue_bind($this->report_process_queue, $this->exchange, $this->report_process_routing_key);

        $channel->queue_declare(NOTIFICATION_EMAIL_ROUTE, false, true, false, false);
        $channel->queue_bind(NOTIFICATION_EMAIL_ROUTE, $this->exchange, NOTIFICATION_EMAIL_ROUTE);


        $consumerTag = $channel->basic_consume($this->report_process_queue, '', false, false, false, false, function ($msg) use (&$channel) {
            try {
                $schedule = json_decode($msg->body, true);
                CLI::write(json_encode($schedule) . "\n");

                $db = \Config\Database::connect();
                $db->setDatabase($schedule['database']);

                $notification = $this->parseAndSaveEmailNotification($schedule);
                if (empty($notification['errors'])) {
                    CLI::write(' [x] Success' . json_encode($notification) . "\n");
                    $new_message = new AMQPMessage(json_encode($notification), array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
                    $channel->basic_publish($new_message, $this->exchange, NOTIFICATION_EMAIL_ROUTE);
                }

                CLI::write(' [x] Recieved ' . $msg->body . "\n");
                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
                $db->setDatabase(getenv('database.default.database'));
            } catch (\Exception $ex) {
                CLI::write(' [x] Error ' . $ex->getMessage() . "\n");
                log_message('warning', 'Schedule::process :' . $ex->getMessage());
                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
            }
        });

        while ($channel->is_consuming()) {
            try {
                CLI::write('Ping ' . "\n");
                $channel->wait(null, false, 180);
            } catch (\PhpAmqpLib\Exception\AMQPTimeoutException $e) {
                // ignore timeout, continue loop
            }

            if ((time() - $start_time) >= $timeout) {
                echo "Terminating consumer...\n";

                // Stop consuming
                $channel->basic_cancel($consumerTag);
                break;
            }
        }

        CLI::write('.....');

        $channel->close();
        $connection->close();
    }

    private function parseAndSaveEmailNotification($schedule)
    {
        $notificationRepository = new NotificationRepository();

        $existNotification = $notificationRepository
            ->switchStateNotification('email')->filterData('identifier', md5($schedule['unique_index'] . $schedule['schedule']))
            ->index($paginate = false);

        if (!empty($existNotification['data_list'])) {
            return ['errors' => 'Already Exist'];
        }

        $users_object = (new TenantUserMetaDataRepository())->findAllUsers(["user_metadata.user_id", "user_metadata.name", "user_metadata.email"]);

        $schedule_template = $this->schedule_template_repository->show($schedule['schedule_template_id']);

        if (!empty($schedule_template)) {

            if (!empty($schedule_template["primary_recipients"])) {
                foreach ($users_object as $key => $single_user_object) {
                    $schedule_template["primary_recipients"] = str_replace($single_user_object->name, $single_user_object->email, $schedule_template["primary_recipients"]);
                }
            }

            $primary_recipients = json_decode($schedule_template["primary_recipients"], true);
            $primary_recipients = array_unique($primary_recipients);
            $primary_recipients = array_filter($primary_recipients, fn($value) => !is_null($value) && $value !== '');
            $primary_recipients = array_values(array_filter($primary_recipients));


            $additional_recipients = json_decode($schedule_template["additional_recipients"], true);

            $additional_recipients = array_unique($additional_recipients);
            $additional_recipients = array_filter($additional_recipients, fn($value) => !is_null($value) && $value !== '');
            $additional_recipients = array_values(array_filter($additional_recipients));

            $entity_repository = null;

            switch ($schedule_template['module']) {
                case ScheduleConstant::DEAL_REPORT:
                    $entity_repository = new DealReportViewRepository();
                    break;

                case ScheduleConstant::LEAD_REPORT:
                    $entity_repository = new LeadReportViewRepository();
                    break;

                case ScheduleConstant::INVENTORY_REPORT:
                    $entity_repository = new InventoryReportViewRepository();
                    break;

                case ScheduleConstant::PAYMENT_REPORT:
                    $entity_repository = new PaymentReportViewRepository();
                    break;

                case ScheduleConstant::INVENTORY_COST_REPORT:
                    $entity_repository = new InventoryCostReportViewRepository();
                    break;

                case ScheduleConstant::INVENTORY_PURCHASE_REPORT:
                    $entity_repository = new InventoryPurchaseReportViewRepository();
                    break;

                case ScheduleConstant::CUSTOMER_REPORT:
                    $entity_repository = new CustomerReportViewRepository();
                    break;
            }

            if ($entity_repository) {
                $entity = $entity_repository->show($schedule_template['module_id']);

                $recipient = '';
                $secondary_recipients = [];

                if (!empty($primary_recipients)) {
                    if (empty($recipient)) {
                        $recipient = array_shift($primary_recipients);
                    }
                    $secondary_recipients = array_merge($secondary_recipients, $primary_recipients);
                }

                if (!empty($additional_recipients)) {
                    if (empty($recipient)) {
                        $recipient = array_shift($additional_recipients);
                    }
                    $secondary_recipients = array_merge($secondary_recipients, $additional_recipients);
                }


                $validation = \Config\Services::validation();
                $validation->reset();
                $validation->setRules([
                    'recipient' => 'required|valid_email',
                    'additional_recipients.*' => 'valid_email'
                ]);

                if (!$validation->run([
                    'recipient' => $recipient,
                    'additional_recipients' => $secondary_recipients
                ])) {
                    return ["errors" => $validation->getErrors()];
                }

                $configuration = new ConfigurationRepository();
                $account_email_name = $configuration->findConfigurationColumnsByKey('account_email_name', ['value']);
                $account_email_name = $account_email_name->value;

                $account_email_address = $configuration->findConfigurationColumnsByKey('account_email_address', ['value']);
                $account_email_address =  $account_email_address->value;

                $format = $schedule_template['format'];

                $file_name = Utility::slugify($entity['header'], '_');

                switch ($format) {
                    case "CSV":
                        $file_name  = $file_name . '.csv';
                        break;

                    case "XLS":
                        $file_name  = $file_name . '.xlsx';
                        break;

                    case "PDF":
                        $file_name  = $file_name . '.pdf';
                        break;

                    default:
                        # code...
                        break;
                }

                $complete_file_path = WRITEPATH . 'session/' . md5($schedule['database']) . '/';

                if (!file_exists($complete_file_path)) {
                    mkdir($complete_file_path, 0777, true);
                }

                $complete_file_path = $complete_file_path . $file_name;

                if (file_exists($complete_file_path)) {
                    unlink($complete_file_path);
                }

                if (!file_exists($complete_file_path)) {
                    $reportFactory = (new ReportFactory())->getInstance($schedule_template['module']);
                    $reportFactory->setDataFromView($schedule_template['module_id'])->output($complete_file_path);
                }


                if (file_exists($complete_file_path)) {
                    $saveNotification = [];

                    $saveNotification['sender'] = json_encode(["name" => $account_email_name, "email_address" => $account_email_address]);
                    $saveNotification['recipient'] = json_encode(["name" => '', "email_address" => $recipient]);
                    $saveNotification['additional_recipients'] = json_encode($secondary_recipients);
                    $saveNotification['content'] = 'Your ' . $schedule_template['frequency'] . ' scheduled report for ' . $entity['header'] . ' is here. Please find the document in the attachment.';
                    $saveNotification['subject'] = $schedule_template['frequency'] . ' ' . $entity['header'] . ' report';
                    $saveNotification['premium'] = 0;
                    $saveNotification['context'] = 'Schedule';
                    $saveNotification['schedule'] = Carbon::parse(null, 'UTC');
                    $saveNotification['identifier'] = md5($schedule['unique_index'] . $schedule['schedule']);
                    $saveNotification['attachment'] = $complete_file_path;

                    $response = $notificationRepository->switchStateNotification('email')->create($saveNotification);
                    $saveNotification['id'] = $response['email_id'];
                    $saveNotification['database'] = db_connect()->getDatabase();

                    return $saveNotification;
                }
            }
        }
    }
}
