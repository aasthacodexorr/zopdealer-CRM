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

class SMSNotificationQueue extends Controller
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
        $channel->queue_declare(NOTIFICATION_SMS_ROUTE, false, true, false, false);
        $channel->exchange_declare($this->exchange, AMQPExchangeType::DIRECT, false, true, false);

        $channel->queue_bind(NOTIFICATION_SMS_ROUTE, $this->exchange, NOTIFICATION_SMS_ROUTE);

        /** Append Consumer to the queue and the exchange */
        $consumerTag =  $channel->basic_consume(NOTIFICATION_SMS_ROUTE, '', false, false, false, false, function ($msg) {
            try {
                $notification = json_decode($msg->body, true);
                CLI::write($msg->body);
                $db = \Config\Database::connect();
                $db->setDatabase($notification['database']);
                $this->send($notification);
                $this->repository->switchStateNotification('sms')->update($notification['id'], ["status" => 'SENT']);
                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
            } catch (NotReadableException $e) {
                CLI::write(' [x] SMS Notification ' . $e->getMessage() . "\n");
                $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag']);
                log_message('warning', $e->getMessage());
            } catch (Exception $e) {
                $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag']);
                CLI::write(' [x] SMS Notification ' . $e->getMessage() . "\n");
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

            $notification['recipient'] = !empty($notification['recipient']) ? $notification['recipient'] : '';
            $notification['sender'] = !empty($notification['sender']) ? $notification['sender'] : '';

            $smsFactory = service('channelService')->getInstance("internal")['sms'];
            $smsFactory->setSid(getenv('twilio.sid'));
            $smsFactory->setAuth_token(getenv('twilio.auth_token'));
            $smsFactory->setSender($notification['sender']);
            $smsFactory->setContent($notification['content']);
            $smsFactory->setRecipient($notification['recipient']);

            if (!empty($notification['additional_recipients'])) {
                $additional_recipients = json_decode($notification['additional_recipients'], true);
                if (!empty($additional_recipients)) {
                    $smsFactory->setAdditionalRecipients($additional_recipients);
                }
            }

            $smsFactory->send();
        } catch (\Exception $ex) {
            CLI::write("Exception was thrown : " . $ex->getMessage());
        }
    }
}
