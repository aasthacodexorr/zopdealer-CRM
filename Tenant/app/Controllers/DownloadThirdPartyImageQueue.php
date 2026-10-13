<?php

namespace Tenant\app\Controllers;

use App\Helpers\Utility;
use App\Libraries\CloudStorage\CloudStorageFactory;
use App\Repositories\ClientRepository;
use Carbon\Carbon;
use CodeIgniter\Cache\CacheFactory;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Controller;
use Config\NonPersistantCache;
use finfo;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\InventoryRepository;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

set_time_limit(0);


/**
 * DownloadThirdPartyImageQueue This Queue is to download the Third Party Images for Customers
 * With Inbound Syndication
 */
class DownloadThirdPartyImageQueue extends Controller
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

    public function __construct()
    {
        $this->host = getenv('rabbit.host');
        $this->port = getenv('rabbit.port');
        $this->user = getenv('rabbit.user');
        $this->pass = getenv('rabbit.pass');
        $this->vhost = getenv('rabbit.vhost');
        $this->exchange = getenv('rabbit.exchange');
        $this->queue = DOWNLOAD_THIRD_PARTY_IMAGE_ROUTE;
        $this->routing_key = DOWNLOAD_THIRD_PARTY_IMAGE_ROUTE;
        $this->client_repository = new ClientRepository();
        $this->cache_factory = CacheFactory::getHandler(new NonPersistantCache());
        $this->repository = new InventoryRepository();
    }

    public function send()
    {
        $clients = $this->client_repository->scheduleQueueBasedOnColumn('download_third_party_image_schedule');
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

                $download_inventory_third_party_image = (new ConfigurationRepository())->findConfigurationColumnsByKey('download_inventory_third_party_image', 'value');
                $download_inventory_third_party_image = $download_inventory_third_party_image->value;

                if ($download_inventory_third_party_image !== "0") {
                    CLI::write($database);
                    $image_rows = $this->repository->getThirdPartyImagesToDownload();

                    if (!empty($image_rows)) {
                        foreach ($image_rows as $row) {
                            $msg = new AMQPMessage(
                                json_encode(['database' => $database, 'image_url' => $row['image_url'], 'inventory_id' => $row['inventory_id']]),
                                array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
                            );

                            $channel->basic_publish($msg, $this->exchange, $this->routing_key);
                        }
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
        $channel->queue_declare($this->queue, false, true, false, false);
        $channel->exchange_declare($this->exchange, AMQPExchangeType::DIRECT, false, true, false);

        $channel->queue_bind($this->queue, $this->exchange, $this->routing_key);

        $consumerTag = $channel->basic_consume($this->queue, '', false, false, false, false, function ($msg) {
            try {
                $payload = json_decode($msg->body, true);

                if (!empty($payload['database'])) {
                    $db = \Config\Database::connect();
                    $db->setDatabase($payload['database']);

                    if (!empty($payload['image_url']) && !empty($payload['inventory_id'])) {
                        $original_image_url = $payload['image_url'];
                        $inventory_id = $payload['inventory_id'];

                        if (!$this->skipImageProcessing($original_image_url, $inventory_id)) {
                            $this->downloadAndUpdateImageURL($original_image_url, $inventory_id);
                            sleep(2);
                        }
                    }
                }

                CLI::write(' [x] Recieved ' . $msg->body . "\n");
                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
            } catch (\Exception $ex) {
                CLI::write(' [x] Error ' . $ex->getMessage() . "\n");
                log_message('warning', 'DownloadThirdParty::process :' . $ex->getMessage());
                $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag']);
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
            $builder->update(['download_third_party_image_schedule' => $scheduled_datetime]);
        }
    }

    private function skipImageProcessing(String $original_image_url, String $inventory_id): bool
    {
        if (!Utility::isAbsoluteUrl($original_image_url)) {
            return true;
        }

        $cache_key = 'INVENTORY_IMG_' . $original_image_url;
        $cache_key = md5($cache_key);
        $error_cache_key = 'ERROR_' . $cache_key;

        $image_has_error = $this->cache_factory->get($error_cache_key);
        if ($image_has_error) {
            CLI::write('Skipping Error Image URL ....');
            return true;
        }

        $cache_image_url = $this->cache_factory->get($cache_key);
        if ($cache_image_url) {
            CLI::write('Skipping Image Download ....');
            $this->updateImageURL($original_image_url, $cache_image_url, $inventory_id);
            return true;
        }

        return false;
    }

    private function updateImageURL(String $original_image_url, String $cached_url, String $inventory_id): void
    {
        try {
            CLI::write("Replacing the URL from " . $original_image_url . " to " . $cached_url);
            $db = \Config\Database::connect();
            $builder = $db->table('inventory_image');
            $builder->where('image_url', $original_image_url);
            $builder->update(['image_url' => $cached_url]);
            $builder->limit(1);

            $this->repository->updateTypesenseIndexService([$inventory_id]);
            $this->repository->purgeWebsiteCache([$inventory_id]);
        } catch (\Exception $ex) {
            CLI::write("Unable to replace the URL from " . $original_image_url . " to " . $cached_url);
        }
    }

    private function downloadAndUpdateImageURL(String $original_image_url, String $inventory_id)
    {
        $cache_key = 'INVENTORY_IMG_' . $original_image_url;
        $cache_key = md5($cache_key);
        $error_cache_key = 'ERROR_' . $cache_key;

        try {

            $days = rand(90, 120);
            $seconds_in_a_day = 86400;
            $ttl = $days * $seconds_in_a_day;

            $buffer = file_get_contents($original_image_url);
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime_type = $finfo->buffer($buffer);

            $content_type = null;

            $headers = implode("\n", $http_response_header);
            if (preg_match_all("/^content-type\s*:\s*(.*)$/mi", $headers, $matches)) {
                $content_type = end($matches[1]);
            }

            if (!file_exists(ROOTPATH . '/public/' . UPLOAD_FOLDER . "/" . md5(db_connect()->database))) {
                mkdir(ROOTPATH . '/public/' . UPLOAD_FOLDER . "/" . md5(db_connect()->database), 0777, true);
            }

            if (!file_exists(ROOTPATH . '/public/' . ROOT_IMAGE . "/" . md5(db_connect()->database))) {
                mkdir(ROOTPATH . '/public/' . ROOT_IMAGE . "/" . md5(db_connect()->database), 0777, true);
            }

            if (!empty($mime_type)) {
                if (in_array($mime_type, ["image/jpeg", "image/png", "image/webp"]) || in_array($content_type, ["image/jpeg"])) {
                    $file_name = md5($original_image_url);
                    $mimes = new \Mimey\MimeTypes;
                    $file_ext = $mimes->getExtension($mime_type);

                    $path = UPLOAD_FOLDER . '/' . md5(db_connect()->database) . '/' . $file_name . '.' . $file_ext;

                    CLI::write("Path " . $path . "\n");

                    if (!CloudStorageFactory::getInstance()->has('/' . $path)) {
                        CLI::write("Not Available \n");
                        file_put_contents(ROOTPATH . '/public/' . $path, $buffer);
                        CloudStorageFactory::getInstance()->save(ROOTPATH . '/public/' . $path, $path);
                    }

                    CLI::write("Already Available \n");

                    if ($this->cache_factory) {
                        CLI::write($cache_key);
                        $this->cache_factory->save($cache_key, '/' . $path, $ttl);
                    }

                    $this->updateImageURL($original_image_url, '/' . $path, $inventory_id);

                    if (is_file(ROOTPATH . '/public/' . $path)) {
                        unlink(ROOTPATH . '/public/' . $path);
                    }
                }
            }
        } catch (\Exception $ex) {
            if ($this->cache_factory) {
                $this->cache_factory->save($error_cache_key, "ERROR THIRD PARTY IMAGE", 14400);
            }
            CLI::write(' ERROR THIRD PARTY IMAGE ' . $ex->getMessage() . "\n");
        }
    }

    private function updateSchedule($client_id, $scheduled_datetime = NULL): void
    {
        $db = \Config\Database::connect();
        $db->setDatabase(getenv('database.default.database'));
        $builder = $db->table('clients');
        $builder->where('client_id', $client_id);
        $builder->update(['download_third_party_image_schedule' => $scheduled_datetime]);
    }
}
