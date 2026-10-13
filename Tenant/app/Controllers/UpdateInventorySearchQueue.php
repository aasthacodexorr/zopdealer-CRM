<?php

namespace Tenant\app\Controllers;

set_time_limit(0);

use CodeIgniter\Controller;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
use CodeIgniter\CLI\CLI;
use Tenant\app\Entities\Inventory;
use Tenant\app\Models\InventoryModel;
use Tenant\app\Repositories\InventoryRepository;

/****
 * @DEPRECATED
 */
class UpdateInventorySearchQueue extends Controller
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
        $this->queue = getenv('rabbit.inventory_process_queue');
        $this->routing_key = 'inventory_search_update_process_queue';
    }

    /**
     * send 
     *
     * @param  mixed $arg There parameters have to be passed  : IDS
     * @return void
     */
    public function send(...$arg)
    {


        /* Set up connection **/
        $connection = new AMQPStreamConnection($this->host, $this->port, $this->user, $this->pass, $this->vhost);
        $channel = $connection->channel();

        $channel->queue_declare($this->queue, false, true, false, false);
        $channel->exchange_declare($this->exchange, AMQPExchangeType::DIRECT, false, true, false);

        $channel->queue_bind($this->queue, $this->exchange, $this->routing_key);

        $db = \Config\Database::connect();

        $msg = new AMQPMessage(json_encode(['db' => $arg[0], 'ids' =>  $arg[1]]),    array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
        $channel->basic_publish($msg, $this->exchange, $this->routing_key);

        $channel->close();
        $connection->close();
    }

    public function receive()
    {
        $start_time = time();
        $timeout = 600;

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
            160.0,
            null,
            true,
            80.0,
        );
        $channel = $connection->channel();
        $channel->basic_qos(null, 20, null);
        $channel->queue_declare($this->queue, false, true, false, false);
        $channel->exchange_declare($this->exchange, AMQPExchangeType::DIRECT, false, true, false);

        $channel->queue_bind($this->queue, $this->exchange, $this->routing_key);

        /** Append Consumer to the queue and the exchange */
        $consumerTag = $channel->basic_consume($this->queue, '', false, false, false, false, function ($msg) {
            $associative_array = json_decode($msg->body, true);
            if (isset($associative_array['db'])) {
                $db = \Config\Database::connect();
                $db->setDatabase($associative_array['db']);

                $inventoryRepository = new InventoryRepository();
                CLI::write($associative_array['ids']);
                $inventoryIds = explode(',', $associative_array['ids']);

                foreach ($inventoryIds as $id) {
                    $inventory = $inventoryRepository->show($id);

                    $searchable_columns = [
                        'year',
                        'make',
                        'model',
                        'trim',
                        'odometer',
                        'price',
                        'exterior_color',
                        'interior_color',
                        'transmission',
                        'body_type',
                        'drivetrain',
                        'stock_no',
                        'vin',
                        'engine'
                    ];

                    $filtered_columns = [];

                    foreach ($searchable_columns as $key) {
                        if (!empty($inventory[$key])) {
                            $filtered_columns[$key] = $inventory[$key];
                        }
                    }

                    $filtered_columns['inventory_id'] = $inventory['inventory_id'];

                    $inventoryModel = new InventoryModel();
                    $inventory = new Inventory($filtered_columns);
                    if ($inventoryModel->save($inventory) === false) {
                        CLI::write('Error In Updating Cache For Inventory With :' . $inventory->inventory_id);
                    } else {
                        CLI::write('Success In Updating Cache For Inventory With :' . $inventory->inventory_id);
                    }
                }
            }

            CLI::write(' JSON Sent ' . $msg->body . "\n");
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
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
}
