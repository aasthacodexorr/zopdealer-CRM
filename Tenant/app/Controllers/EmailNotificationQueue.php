<?php

namespace Tenant\app\Controllers;

use App\Models\ClientModel;
use Carbon\Carbon;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Controller;
use Exception;
use Intervention\Image\Exception\NotReadableException;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use Tenant\app\Repositories\ActivityRepository;
use Tenant\app\Repositories\NotificationRepository;
use Tenant\app\Repositories\TenantUserMetaDataRepository;

set_time_limit(0);

class EmailNotificationQueue extends Controller
{

    protected   $host;
    protected   $port;
    protected   $user;
    protected   $pass;
    protected   $vhost;
    protected   $exchange;
    protected   $routing_key;
    protected   $repository;

    public function __construct()
    {
        $this->host = getenv('rabbit.host');
        $this->port = getenv('rabbit.port');
        $this->user = getenv('rabbit.user');
        $this->pass = getenv('rabbit.pass');
        $this->vhost = getenv('rabbit.vhost');
        $this->exchange = getenv('rabbit.exchange');
        $this->repository = new NotificationRepository();
    }

    /**
     * receive
     *
     * @return void
     */
    public function receive()
    {
        $start_time = time();
        $timeout = 180;

        /* Set up connection **/
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
        $channel->queue_declare(NOTIFICATION_EMAIL_ROUTE, false, true, false, false);
        $channel->exchange_declare($this->exchange, AMQPExchangeType::DIRECT, false, true, false);

        $channel->queue_bind(NOTIFICATION_EMAIL_ROUTE, $this->exchange, NOTIFICATION_EMAIL_ROUTE);

        /** Append Consumer to the queue and the exchange */
        $consumerTag = $channel->basic_consume(NOTIFICATION_EMAIL_ROUTE, '', false, false, false, false, function ($msg) {
            try {
                $notification = json_decode($msg->body, true);
                CLI::write($msg->body);
                $db = \Config\Database::connect();
                $db->setDatabase($notification['database']);
                $this->send($notification);
                $this->repository->switchStateNotification('email')->update($notification['id'], ["status" => 'SENT']);
                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
            } catch (NotReadableException $e) {
                CLI::write(' [x] Email Notification ' . $e->getMessage() . "\n");
                $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag']);
                log_message('warning', $e->getMessage());
            } catch (Exception $e) {
                $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag']);
                CLI::write(' [x] Email Notification ' . $e->getMessage() . "\n");
                log_message('warning', $e->getMessage());
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

    protected function send($notification)
    {
        try {
            $emailFactory = service('channelService')->getInstance("internal")['email'];

            $notification['premium'] = isset($notification['premium']) ? intval($notification['premium']) : 0;
            $notification['attachment'] = isset($notification['attachment']) ? $notification['attachment'] : null;

            if ($notification['premium'] == 1) {
                $emailFactory->init(
                    getEnv('mailbox.smtp.host'),
                    getEnv('mailbox.smtp.username'),
                    getEnv('mailbox.smtp.password'),
                    getEnv('mailbox.smtp.port')
                );
            } else {
                $emailFactory->init(
                    getEnv('notification.smtp.host'),
                    getEnv('notification.smtp.username'),
                    getEnv('notification.smtp.password'),
                    getEnv('notification.smtp.port')
                );
            }

            $emailFactory->setSubject($notification['subject']);

            $content = $notification['content'];
            $content = empty($content) ? $notification['subject'] : $content;
            $emailFactory->setContent($content);

            $sender = json_decode($notification['sender'], true);
            $sender_name = !empty($sender['name']) ? $sender['name'] : '';
            $sender_email = !empty($sender['email_address']) ? $sender['email_address'] : '';

            $emailFactory->setSender($sender_email, $sender_name);

            $recipient = json_decode($notification['recipient'], true);
            $recipient_name = !empty($recipient['name']) ? $recipient['name'] : '';
            $recipient_email = !empty($recipient['email_address']) ? $recipient['email_address'] : '';

            $emailFactory->setRecipient($recipient_email, $recipient_name);

            $additional_recipients = [];

            if (!empty($notification['additional_recipients'])) {
                $additional_recipients = json_decode($notification['additional_recipients'], true);

                if (!empty($additional_recipients)) {

                    $additional_recipients = array_unique($additional_recipients);

                    foreach ($additional_recipients as $additional_recipient) {
                        $emailFactory->setCC($additional_recipient);
                    }
                }
            }

            if (!empty($notification['attachment'])) {
                $emailFactory->addAttachment($notification['attachment']);
            }



            $emailFactory->send();
        } catch (\Exception $ex) {
            CLI::write("Exception was thrown : " . $ex->getMessage());
        }
    }
}
