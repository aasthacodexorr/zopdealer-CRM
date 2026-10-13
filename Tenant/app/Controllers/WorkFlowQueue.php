<?php

namespace Tenant\app\Controllers;

set_time_limit(0);

use App\Helpers\Utility;
use App\Libraries\WorkFlow\WorkFlowConstant;
use App\Repositories\WorkFlowRepository;
use Carbon\Carbon;
use CodeIgniter\Controller;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
use CodeIgniter\CLI\CLI;
use Config\Services;
use Exception;
use Tenant\app\Repositories\ActivityRepository;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\NotificationRepository;
use Tenant\app\Repositories\WorkFlowTemplateCommonDependentRepository;
use Tenant\app\Repositories\WorkFlowTemplateRepository;

class WorkFlowQueue extends Controller
{
    protected $host;
    protected $port;
    protected $user;
    protected $pass;
    protected $vhost;
    protected $exchange;
    protected $queue;
    protected $routing_key;

    private WorkFlowRepository $repository;

    private WorkFlowTemplateRepository $workflow_template_repository;

    private WorkFlowTemplateCommonDependentRepository $workflow_template_common_dependent_repository;


    public function __construct()
    {
        $this->host = getenv('rabbit.host');
        $this->port = getenv('rabbit.port');
        $this->user = getenv('rabbit.user');
        $this->pass = getenv('rabbit.pass');
        $this->vhost = getenv('rabbit.vhost');
        $this->exchange = getenv('rabbit.exchange');
        $this->queue = WORKFLOW_ROUTE;
        $this->routing_key = WORKFLOW_ROUTE;
        $this->repository = new WorkFlowRepository();
        $this->workflow_template_repository = new WorkFlowTemplateRepository();
        $this->workflow_template_common_dependent_repository = new WorkFlowTemplateCommonDependentRepository();
    }

    public function accept(...$arg): void
    {
        $connection = new AMQPStreamConnection($this->host, $this->port, $this->user, $this->pass, $this->vhost);
        $channel = $connection->channel();

        $channel->queue_declare($this->queue, false, true, false, false);
        $channel->exchange_declare($this->exchange, AMQPExchangeType::DIRECT, false, true, false);

        $channel->queue_bind($this->queue, $this->exchange, $this->routing_key);

        $data = [
            'database' => $arg[0],
            'workflow_template_id' => $arg[1],
            'module' => $arg[2],
            'module_id' => $arg[3],
            'context' => base64_decode($arg[4]),
            'search_keyword' => $arg[2] . ' ' . $arg[3] . ' ' . $arg[1] . ' ' . $arg[0],
            'unique_index' => md5($arg[2] . ' ' . $arg[3] . ' ' . $arg[1] . ' ' . $arg[0])

        ];

        $msg = new AMQPMessage(json_encode($data),    array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
        $channel->basic_publish($msg, $this->exchange, $this->routing_key);

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
                $workflow = json_decode($msg->body, true);
                CLI::write(json_encode($workflow) . "\n");

                $response = $this->repository->create($workflow);

                CLI::write(' [x] Recieved ' . $msg->body . "\n");
                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
            } catch (\Exception $ex) {
                CLI::write(' [x] Error ' . $ex->getMessage() . "\n");
                log_message('warning', 'WorkFlow::save :' . $ex->getMessage());
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

    public function processPrefetch()
    {
        $prefetched_workflows = $this->repository->filterData("status", WorkFlowConstant::PREFETCH)->index();
        $db = \Config\Database::connect();

        if (!empty($prefetched_workflows['data_list'])) {
            foreach ($prefetched_workflows['data_list'] as $key => $workflow) {
                $db->setDatabase($workflow['database']);
                $current_workflow_template_id = $workflow['workflow_template_id'];
                $workflow_template_step = $this->workflow_template_common_dependent_repository
                    ->switchWorkFlowTemplateCommonDependentState('steps')
                    ->findStepByWorkFlowTemplateId($current_workflow_template_id);

                if (!empty($workflow_template_step)) {
                    $scheduled_datetime = $this->workflow_template_common_dependent_repository
                        ->calculateDateTimeForWorkFlowTemplateStep($workflow_template_step);

                    CLI::write($scheduled_datetime . "\n");

                    $db->setDatabase(getenv('database.default.database'));

                    $builder = $db->table('workflow');
                    $builder->where('id', $workflow['id']);
                    $builder->update(['status' => WorkFlowConstant::ACTIVE, 'schedule' => $scheduled_datetime]);
                }
            }
        }
    }

    public function processActive()
    {

        $connection = new AMQPStreamConnection($this->host, $this->port, $this->user, $this->pass, $this->vhost);
        $channel = $connection->channel();

        $channel->queue_declare(NOTIFICATION_SMS_ROUTE, false, true, false, false);
        $channel->queue_declare(NOTIFICATION_EMAIL_ROUTE, false, true, false, false);

        $channel->queue_declare($this->queue, false, true, false, false);
        $channel->exchange_declare($this->exchange, AMQPExchangeType::DIRECT, false, true, false);

        $channel->queue_bind(NOTIFICATION_SMS_ROUTE, $this->exchange, NOTIFICATION_SMS_ROUTE);
        $channel->queue_bind(NOTIFICATION_EMAIL_ROUTE, $this->exchange, NOTIFICATION_EMAIL_ROUTE);

        $active_workflows = $this->repository->scheduleActiveWorkFlow();
        $db = \Config\Database::connect();

        if (!empty($active_workflows['data_list'])) {
            foreach ($active_workflows['data_list'] as $key => $workflow) {
                try {
                    $db->setDatabase($workflow['database']);

                    $current_workflow_template_id = $workflow['workflow_template_id'];
                    $workflow_template_step = $this->workflow_template_common_dependent_repository
                        ->switchWorkFlowTemplateCommonDependentState('steps')
                        ->findStepDetailsByWorkFlowTemplateId($current_workflow_template_id, 1, intval($workflow["current_step"]) - 1);

                    $value_parser_mapper = $this->workflow_template_repository
                        ->setTemplateValueParser($workflow['workflow_template_id'], $workflow['module'], $workflow['module_id']);

                    if (!empty($workflow_template_step['details'])) {

                        if (!empty($workflow_template_step)) {

                            $workflow_template_step['details']['identifier'] = $workflow['unique_index'] . $workflow_template_step['id'];

                            $notificationRepository = new NotificationRepository();

                            switch ($workflow_template_step["action"]) {
                                case WorkFlowConstant::SMS:
                                    $response = $this->parseAndSaveSMSEntity($notificationRepository, $workflow_template_step['details'], $value_parser_mapper);
                                    if (empty($response['errors'])) {
                                        $msg = new AMQPMessage(json_encode($response), array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
                                        $channel->basic_publish($msg, $this->exchange, NOTIFICATION_SMS_ROUTE);
                                    }
                                    break;

                                case WorkFlowConstant::EMAIL:
                                    $response = $this->parseAndSaveEmailEntity($notificationRepository, $workflow_template_step['details'], $value_parser_mapper);
                                    if (empty($response['errors'])) {
                                        $msg = new AMQPMessage(json_encode($response), array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
                                        $channel->basic_publish($msg, $this->exchange, NOTIFICATION_EMAIL_ROUTE);
                                    }
                                    break;

                                case WorkFlowConstant::ACTIVITY:
                                    $workflow_template_step['details']["schedule"] = $workflow['schedule'];
                                    $workflow_template_step['details']["module"] = ucwords(strtolower($workflow['module']));
                                    $workflow_template_step['details']["module_id"] = $workflow['module_id'];

                                    if ($workflow['module'] === WorkFlowConstant::LEAD) {
                                        $workflow_template_step['details']['assignee'] = $value_parser_mapper["content"]["{Lead Assignee ID}"];
                                    } else if ($workflow['module'] === WorkFlowConstant::CUSTOMER) {
                                        $workflow_template_step['details']['assignee'] = $value_parser_mapper["content"]["{Customer Assignee ID}"];
                                    } else if ($workflow['module'] === WorkFlowConstant::DEAL) {
                                        $workflow_template_step['details']['assignee'] = $value_parser_mapper["content"]['{Deal Assignee ID}'];
                                        $workflow_template_step['details']['approver'] = $value_parser_mapper["content"]['{Deal Approver ID}'];
                                    }

                                    $this->parseAndSaveActivityEntity($workflow_template_step['details'], $value_parser_mapper);
                                    break;

                                default:
                                    # code...
                                    break;
                            }
                        }
                    }

                    $workflow_template_steps_count = $this->workflow_template_common_dependent_repository
                        ->switchWorkFlowTemplateCommonDependentState('steps')
                        ->findByWorkFlowTemplateStepCount($current_workflow_template_id);

                    $next_step = intval($workflow["current_step"]);


                    //Check if there are more steps to schedule it
                    if ($next_step <= $workflow_template_steps_count) {

                        $workflow_template_step = $this->workflow_template_common_dependent_repository
                            ->switchWorkFlowTemplateCommonDependentState('steps')
                            ->findStepByWorkFlowTemplateId($current_workflow_template_id, 1, $next_step);

                        //If step exist calculate the schedule date and time
                        if (!empty($workflow_template_step)) {
                            $scheduled_datetime = $this->workflow_template_common_dependent_repository
                                ->calculateDateTimeForWorkFlowTemplateStep($workflow_template_step, Carbon::parse($workflow['schedule'], 'UTC'));

                            CLI::write($scheduled_datetime . "\n");

                            $db->setDatabase(getenv('database.default.database'));

                            $builder = $db->table('workflow');
                            $builder->where('id', $workflow['id']);
                            $builder->update(['current_step' => $next_step + 1, 'status' => WorkFlowConstant::ACTIVE, 'schedule' => $scheduled_datetime]);
                        } else {
                            $this->disableWorkFlow($db, $workflow);
                        }
                    } else {
                        $this->disableWorkFlow($db, $workflow);
                    }
                } catch (\Exception $ex) {
                    log_message("notice", $ex->getMessage());
                    var_dump($ex->getMessage());
                }

                $db->setDatabase(getenv('database.default.database'));
                //STEPS To update the workflow entry
            }
        }

        $channel->close();
        $connection->close();
    }

    public function detach(...$arg): void
    {
        $context = base64_decode($arg[0]);
        $context = json_decode($context, true);
        $template_id = !empty($context['template_id']) ? $context['template_id'] : "";
        $database = !empty($context['database']) ? $context['database'] : "";
        $action = !empty($context['action']) ? $context['action'] : "";

        switch ($action) {
            case WorkFlowConstant::WORKFLOW_TEMPLATE_DELETE:
                if (!empty($template_id) && !empty($database)) {
                    $this->repository->filterData('database', $database)->filterData('workflow_template_id', $template_id)->deleteWithoutKey();
                }
                break;

            default:
                break;
        }
    }


    private function parseAndSaveSMSEntity(NotificationRepository $notificationRepository, array $entity, array $value_parser): ?array
    {
        $smsNotification = $notificationRepository->switchStateNotification('sms');

        $existNotification = $notificationRepository
            ->filterData('identifier', $entity['identifier'])
            ->index($paginate = false);

        $exist_id = null;

        if (!empty($existNotification['data_list'])) {
            $exist_id = $existNotification['data_list'][0]['id'];
        }

        if (intval($entity["override_custom_template"]) === 1 && strlen($entity['custom_template']) > 0) {
            if (is_file(APPPATH . 'Views/sms/workflows/' . $entity['custom_template'] . '.php')) {
                $entity["content"] = view('sms/workflows/' . $entity['custom_template'], $value_parser['content']);
            }
        }

        $entity = $this->parsePlaceholdersInEntity($entity, $value_parser['sms'], $value_parser['content'], ['content', 'primary_recipients'], ['content']);

        $recipient = '';
        $secondary_recipients = [];

        $primary_recipients = json_decode($entity["primary_recipients"], true);
        if (!empty($primary_recipients)) {
            if (empty($recipient)) {
                $recipient = array_shift($primary_recipients);
            }
            $secondary_recipients = array_merge($secondary_recipients, $primary_recipients);
        }

        $additional_recipients = json_decode($entity["additional_recipients"], true);
        if (!empty($additional_recipients)) {
            if (empty($recipient)) {
                $recipient = array_shift($additional_recipients);
            }
            $secondary_recipients = array_merge($secondary_recipients, $additional_recipients);
        }

        $validation = \Config\Services::validation();
        $validation->reset();
        $validation->setRules([
            'recipient' => 'required|is_natural|exact_length[10]',
            'additional_recipients.*' => 'required|is_natural|exact_length[10]',
            'content' => 'required'
        ]);

        if (!$validation->run([
            'recipient' => $recipient,
            'additional_recipients' => $secondary_recipients,
            'content' => $entity['content']
        ])) {
            log_message("warning", json_encode($validation->getErrors()));
            return ["errors" => $validation->getErrors()];
        } else {
            if (empty($exist_id)) {

                $saveNotification = [
                    'sender' => getenv("twilio.phone"),
                    'recipient' => "+1" . $recipient,
                    'additional_recipients' => json_encode(array_map(function ($phone) {
                        return "+1" . $phone;
                    }, $secondary_recipients)),
                    'premium' => 1,
                    'content' => $entity['content'],
                    'context' => 'WorkFlow',
                    'identifier' => $entity['identifier'],
                    'schedule' => Carbon::parse(null, 'UTC')
                ];

                $response = $smsNotification->create($saveNotification);
                $saveNotification['id'] = $response['sms_id'];
                $saveNotification['database'] = db_connect()->getDatabase();
                return $saveNotification;
            } else {
                return ["errors" => 'Already Exist.'];
            }
        }
    }

    private function parseAndSaveEmailEntity(NotificationRepository $notificationRepository, array $entity, array $value_parser): ?array
    {

        $emailNotification = $notificationRepository->switchStateNotification('email');

        $existNotification = $notificationRepository
            ->filterData('identifier', $entity['identifier'])
            ->index($paginate = false);

        $exist_id = null;

        if (!empty($existNotification['data_list'])) {
            $exist_id = $existNotification['data_list'][0]['id'];
        }

        if (intval($entity["override_custom_template"]) === 1 && strlen($entity['custom_template']) > 0) {
            if (is_file(APPPATH . 'Views/emails/workflows/' . $entity['custom_template'] . '.php')) {
                $entity["content"] = view('emails/workflows/' . $entity['custom_template'], $value_parser['content']);
            }
        }

        $entity = $this->parsePlaceholdersInEntity($entity, $value_parser['email'], $value_parser['content'], ['subject', 'content', 'primary_recipients'], ['subject']);

        $recipient = '';
        $secondary_recipients = [];

        $configuration = new ConfigurationRepository();
        $account_email_name = $configuration->findConfigurationColumnsByKey('account_email_name', ['value']);
        $account_email_name = $account_email_name->value;

        $account_email_address = $configuration->findConfigurationColumnsByKey('account_email_address', ['value']);
        $account_email_address =  $account_email_address->value;

        $primary_recipients = json_decode($entity["primary_recipients"], true);
        if (!empty($primary_recipients)) {
            if (empty($recipient)) {
                $recipient = array_shift($primary_recipients);
            }
            $secondary_recipients = array_merge($secondary_recipients, $primary_recipients);
        }

        $additional_recipients = json_decode($entity["additional_recipients"], true);
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
            'additional_recipients.*' => 'valid_email',
            'subject' => 'required',
            'content' => 'required'
        ]);

        if (!$validation->run([
            'recipient' => $recipient,
            'additional_recipients' => $secondary_recipients,
            'subject' => $entity['subject'],
            'content' => $entity['content']
        ])) {
            log_message("warning", json_encode($validation->getErrors()));
            return ["errors" => $validation->getErrors()];
        } else {
            if (empty($exist_id)) {
                $saveNotification = [
                    'sender' => json_encode(["name" => $account_email_name, "email_address" => $account_email_address]),
                    'recipient' => json_encode(["name" => '', "email_address" => $recipient]),
                    'additional_recipients' => json_encode($secondary_recipients),
                    'premium' => 1,
                    'content' => $entity['content'],
                    'subject' => $entity['subject'],
                    'context' => 'WorkFlow',
                    'identifier' => $entity['identifier'],
                    'schedule' => Carbon::parse(null, 'UTC')
                ];

                $response = $emailNotification->create($saveNotification);
                $saveNotification['id'] = $response['email_id'];
                $saveNotification['database'] = db_connect()->getDatabase();
                return $saveNotification;
            } else {
                return ["errors" => 'Already Exist.'];
            }
        }
    }

    private function parseAndSaveActivityEntity(array $entity, array $value_parser): void
    {
        $default_assignee = $entity["default_assignee"];

        $assignee = $default_assignee;

        if (intval($entity['automate_assignment']) == 1) {
            $assignee = !empty($entity["assignee"]) ? $entity["assignee"] : $default_assignee;
        }

        $entity = $this->parsePlaceholdersInEntity($entity, [], $value_parser['content'], ['title', 'description'], ['title', 'description']);

        $notification_date = Carbon::parse($entity['schedule'], 'UTC');
        $start_date = $notification_date->copy()->isoFormat("MM/DD/YYYY HH:mm");
        $end_date = $notification_date->copy()->addHour()->isoFormat("MM/DD/YYYY HH:mm");

        $throttler = Services::throttler(false);

        if ($throttler->check('WF_ACTIVITY_' . $entity['identifier'], 1, MONTH, 2)) {
            (new ActivityRepository())->create([
                "title" => $entity["title"],
                "description" => $entity["description"],
                "notify" => $entity["notify"],
                "all_day" => "0",
                "priority" => $entity["priority"],
                "status" => "Pending",
                "activity_method" => $entity["activity_method"],
                "activity_type" => $entity["activity_type"],
                "notification_method" => $entity["notification_method"],
                "module" => $entity["module"],
                "module_id" => $entity["module_id"],
                "notification_at" => "at",
                "notification_date" => $notification_date->isoFormat("MM/DD/YYYY HH:mm"),
                "start_date" => $start_date,
                "end_date " => $end_date,
                "assignee" => $assignee
            ]);
        }
    }

    private function parsePlaceholdersInEntity(array $entity, array $contact_parser, array $content_parser, array $fields_to_parse = [], array $fields_to_strip_html = []): array
    {
        foreach ($fields_to_parse as $field) {
            if (!empty($entity[$field])) {
                $entity[$field] = $this->parsePlaceholdersInString($contact_parser, $entity[$field]);
            }
        }

        foreach ($fields_to_parse as $field) {
            if (!empty($entity[$field])) {
                $entity[$field] = $this->parsePlaceholdersInString($content_parser, $entity[$field]);
                $entity[$field] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $entity[$field]);
            }
        }

        foreach ($fields_to_strip_html as $field) {
            if (!empty($entity[$field])) {
                $entity[$field] = Utility::stripHTMLTags($entity[$field]);
            }
        }

        return $entity;
    }


    private function parsePlaceholdersInString(array $mapping = [], string $description): string
    {
        try {

            foreach ($mapping as $variable => $value) {
                $description = str_replace($variable, $value, $description);
            }
            return $description;
        } catch (\Exception $ex) {
            return $description;
        }
    }

    private function disableWorkFlow($db, $workflow)
    {
        $db->setDatabase(getenv('database.default.database'));

        $builder = $db->table('workflow');
        $builder->where('id', $workflow['id']);
        $builder->update(['status' => WorkFlowConstant::DISABLED]);
    }
}
