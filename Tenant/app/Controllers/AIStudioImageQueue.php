<?php

namespace Tenant\app\Controllers;

set_time_limit(0);

use App\Helpers\Utility;
use App\Libraries\CloudStorage\CloudStorageFactory;
use App\Repositories\ClientRepository;
use Carbon\Carbon;
use CodeIgniter\Cache\CacheFactory;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Controller;
use Config\NonPersistantCache;
use Exception;
use finfo;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\InventoryRepository;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
use Tenant\app\Libraries\AIStudio\AIStudio;
use PhpAmqpLib\Wire\AMQPTable;

set_time_limit(0);


class AIStudioImageQueue extends Controller
{
    protected   $host;
    protected   $port;
    protected   $user;
    protected   $pass;
    protected   $vhost;
    protected   $exchange;
    protected   $routing_key;
    private     $client_repository;
    protected   $queue;
    protected   $cache_factory;
    protected   $repository;
    protected   $configuration_repository;

    public function __construct()
    {
        $this->host = getenv('rabbit.host');
        $this->port = getenv('rabbit.port');
        $this->user = getenv('rabbit.user');
        $this->pass = getenv('rabbit.pass');
        $this->vhost = getenv('rabbit.vhost');
        $this->exchange = getenv('rabbit.exchange');
        $this->queue = DOWNLOAD_AI_STUDIO_IMAGE_ROUTE;
        $this->routing_key = DOWNLOAD_AI_STUDIO_IMAGE_ROUTE;
        $this->client_repository = new ClientRepository();
        $this->cache_factory = CacheFactory::getHandler(new NonPersistantCache());
        $this->repository = new InventoryRepository();
        $this->configuration_repository = new ConfigurationRepository();
    }

    public function send()
    {
        $clients = $this->client_repository->scheduleQueueBasedOnColumn('download_zop_studio_processed_image_schedule');
        $db = \Config\Database::connect();

        $clients = $clients['data_list'];

        $connection = new AMQPStreamConnection($this->host, $this->port, $this->user, $this->pass, $this->vhost);
        $channel = $connection->channel();

        $channel->queue_declare($this->queue, false, true, false, false);
        $channel->exchange_declare($this->exchange, AMQPExchangeType::DIRECT, false, true, false);

        $channel->queue_bind($this->queue, $this->exchange, $this->routing_key);

        foreach ($clients as $client) {
            try {

                $minutes = rand(180, 240);
                $current = Carbon::now('UTC');
                $scheduled_datetime = $current->copy()->addMinutes($minutes);

                $this->updateSchedule($client['client_id'], $scheduled_datetime);

                $database = $client['subdomain'] . '_' . $client['client_hash'];
                $db->setDatabase($database);

                if ($this->iSQueueEnabledAndHasEnoughCredits()) {
                    CLI::write($database);

                    $images = $this->repository->getImageListForOverlayProcessing();
                    foreach ($images as $image) {

                        $arr = array(
                            'db' => $database,
                            'image' => $image['image_url'],
                        );

                        $msg = new AMQPMessage(json_encode($arr),    array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
                        $channel->basic_publish($msg, $this->exchange, $this->routing_key);
                    }
                }
            } catch (\Exception $ex) {
                CLI::write("Exception was thrown : " . $ex->getMessage());
                $minutes = rand(180, 240);
                $current = Carbon::now('UTC');
                $scheduled_datetime = $current->copy()->addMinutes($minutes);
                $this->updateSchedule($client['client_id'], $scheduled_datetime);
            }
        }

        $channel->close();
        $connection->close();
    }

    /**
     * sendSingleImage 
     *
     * @param  mixed $arg There parameters have to be passed  : TENANT_DB INVENTORY_IMAGE_NAME
     * @return void
     */
    public function sendSingleImage(...$arg)
    {


        /* Set up connection **/
        $connection = new AMQPStreamConnection($this->host, $this->port, $this->user, $this->pass, $this->vhost);
        $channel = $connection->channel();

        $channel->queue_declare($this->queue, false, true, false, false);
        $channel->exchange_declare($this->exchange, AMQPExchangeType::DIRECT, false, true, false);

        $channel->queue_bind($this->queue, $this->exchange, $this->routing_key);

        $database_name = $arg[0];
        $image_file_name = $arg[1];

        $image = "/" . UPLOAD_FOLDER . "/" . md5($database_name) . "/" . $image_file_name;

        $arr = array(
            'db' => $database_name,
            'image' => $image,
        );

        $msg = new AMQPMessage(json_encode($arr),    array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
        $channel->basic_publish($msg, $this->exchange, $this->routing_key);

        $channel->close();
        $connection->close();
    }

    public function receive()
    {
        $start_time = time();
        $timeout = 360;

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
        $channel->queue_declare($this->queue, false, true, false, false);
        $channel->exchange_declare($this->exchange, AMQPExchangeType::DIRECT, false, true, false);

        $channel->queue_bind($this->queue, $this->exchange, $this->routing_key);

        /* Get message in the callback **/

        /** Append Consumer to the queue and the exchange */
        $consumerTag =  $channel->basic_consume($this->queue, '', false, false, false, false, function ($msg) {
            $associative_array = json_decode($msg->body, true);
            $retryCount = 0;

            try {

                Sleep(20);

                if ($msg->has('application_headers')) {
                    $table = $msg->get('application_headers');
                    if ($table instanceof \PhpAmqpLib\Wire\AMQPTable) {
                        $retryCount = (int) (($table->getNativeData()['x-retry'] ?? 0));
                    }
                }

                $db = \Config\Database::connect();
                $db->setDatabase($associative_array['db']);

                $query_builder = $db->table('inventory_image');
                $query_builder->where('image_url', $associative_array['image']);
                $query = $query_builder->get();
                $query = $query->getRowArray();

                if (empty($query)) {
                    throw new Exception("Image Not Found", 400);
                }

                if (!empty($query['image_with_overlay_url']) && !str_contains(strtoupper($query['image_with_overlay_url']), strtoupper(getenv('image.cdn')))) {
                    throw new Exception("Image With Processed Background Is Already Available.", 1);
                }

                if (!$this->isEnabledAndHasEnoughCredits()) {
                    throw new Exception("Not Enabled or Not Enough Credits", 402);
                }

                if (!CloudStorageFactory::getInstance()->has($associative_array['image'])) {
                    throw new Exception("Image Not Found", 400);
                }

                $image_url = getenv('image.cdn') . $associative_array['image'];

                $processed_result = AIStudio::process($image_url, $this->getSelectedBackgroundImage(), [
                    'mask_number_plate' => $this->maskNumberPlateConfig(),
                    'show_shadow' => $this->showShadowConfig()
                ]);

                CLI::write(json_encode($processed_result));

                if (!empty($processed_result['output_url'])) {
                    $builder = $db->table('inventory_image');
                    $builder->where('image_url', $associative_array['image']);
                    $builder->update(['image_with_overlay_url' => $processed_result['output_url']]);

                    $available_credits = $this->availableCredits();
                    $updated_available_credits = $available_credits > 0 ? --$available_credits : 0;
                    $this->configuration_repository->setConfiguration(['zop_studio_available_credit' => $updated_available_credits], false);

                    (new InventoryRepository())->updateTypesenseIndexService([$query["inventory_id"]]);
                    (new InventoryRepository())->purgeWebsiteCache([$query["inventory_id"]]);
                } else {
                    throw new Exception("Processing Background Error", 400);
                }

                CLI::write(' [x] Processed ' . $msg->body . "\n");
                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
            } catch (Exception $e) {

                if ($retryCount <= 5) {

                    $new_header = new AMQPTable([
                        'x-retry' => $retryCount + 1,
                    ]);

                    $new_message = new AMQPMessage(
                        $msg->getBody(),
                        [
                            'delivery_mode' => $msg->get('delivery_mode') ?: 2,
                            'application_headers' => $new_header,
                        ]
                    );
                    $msg->delivery_info['channel']->basic_publish($new_message, $this->exchange, $this->routing_key);
                }

                $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag']);
                CLI::write(' [x] Image Url Not Processed ' . $e->getMessage() . "\n");
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

    public function resetSchedule(...$arg): void
    {
        $current = Carbon::now('UTC');
        $scheduled_datetime = $current->copy()->addMinutes(10);

        if (!empty($arg[0])) {
            $db = \Config\Database::connect();
            $db->setDatabase(getenv('database.default.database'));
            $builder = $db->table('clients');
            $builder->where('subdomain', $arg[0]);
            $builder->update(['download_zop_studio_processed_image_schedule' => $scheduled_datetime]);
        }
    }

    private function updateSchedule($client_id, $scheduled_datetime = NULL): void
    {
        $db = \Config\Database::connect();
        $db->setDatabase(getenv('database.default.database'));
        $builder = $db->table('clients');
        $builder->where('client_id', $client_id);
        $builder->update(['download_zop_studio_processed_image_schedule' => $scheduled_datetime]);
    }

    private function isEnabledAndHasEnoughCredits(): bool
    {
        $enable_zop_studio_config = $this->configuration_repository->findConfigurationColumnsByKeyAsArray('enable_zop_studio', ['value']);

        $is_zop_studio_enabled = !empty($enable_zop_studio_config['value']);
        $is_zop_studio_available_credit_available =  $this->availableCredits() > 0;
        $is_zop_background_image_available = !empty($this->getSelectedBackgroundImage());

        if ($is_zop_studio_enabled) {
            if ($is_zop_studio_available_credit_available) {
                if ($is_zop_background_image_available) {
                    return true;
                }
            }
        }

        return false;
    }

    private function availableCredits(): ?int
    {
        $zop_studio_available_credit_config = $this->configuration_repository->findConfigurationColumnsByKeyAsArray('zop_studio_available_credit', ['value']);
        return intval($zop_studio_available_credit_config['value']);
    }

    private function iSQueueEnabledAndHasEnoughCredits()
    {
        $enable_zop_studio_queue_config = $this->configuration_repository->findConfigurationColumnsByKeyAsArray('enable_zop_studio_queue', ['value']);
        $is_zop_studio_queue_enabled = !empty($enable_zop_studio_queue_config['value']);

        return $this->isEnabledAndHasEnoughCredits() && $is_zop_studio_queue_enabled;
    }

    private function getSelectedBackgroundImage(): ?String
    {
        $zop_studio_background_image_config = $this->configuration_repository->findConfigurationColumnsByKeyAsArray('zop_studio_background_image', ['value']);
        return $zop_studio_background_image_config['value'];
    }

    private function maskNumberPlateConfig(): ?bool
    {
        $zop_studio_mask_number_plate_config = $this->configuration_repository->findConfigurationColumnsByKeyAsArray('zop_studio_mask_number_plate', ['value']);
        return !empty($zop_studio_mask_number_plate_config['value']);
    }

    private function showShadowConfig(): ?bool
    {
        $zop_show_shadow_config = $this->configuration_repository->findConfigurationColumnsByKeyAsArray('zop_studio_show_shadow', ['value']);
        return !empty($zop_show_shadow_config['value']);
    }
}
