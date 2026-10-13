<?php

namespace Tenant\app\Controllers;

set_time_limit(0);

use App\Libraries\CloudStorage\CloudStorageFactory;
use App\Repositories\ClientRepository;
use Carbon\Carbon;
use CodeIgniter\Controller;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
use CodeIgniter\CLI\CLI;
use Exception;
use Intervention\Image\Exception\NotReadableException;
use \Intervention\Image\ImageManager;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\InventoryRepository;

class ImageQueue extends Controller
{


    protected   $host;
    protected   $port;
    protected   $user;
    protected   $pass;
    protected   $vhost;
    protected   $exchange;
    protected   $queue;
    protected   $routing_key;
    private $client_repository;


    public function __construct()
    {
        $this->host = getenv('rabbit.host');
        $this->port = getenv('rabbit.port');
        $this->user = getenv('rabbit.user');
        $this->pass = getenv('rabbit.pass');
        $this->vhost = getenv('rabbit.vhost');
        $this->exchange = getenv('rabbit.exchange');
        $this->queue = getenv('rabbit.queue');
        $this->routing_key = 'images_overlay';
        $this->client_repository = new ClientRepository();
    }

    /**
     * send 
     *
     * @param  mixed $arg There parameters have to be passed  : TENANT_DB FOLDER_WHERE_IMAGES_STORED OVERLAY_IMAGE_FILE
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

        $database_name = $arg[0];
        $overlay_folder_name = $arg[1];
        $overlay_file_name = $arg[2];
        $unprocessed_flag = $arg[3];

        $db = \Config\Database::connect();
        $db->setDatabase($database_name);
        $builder = $db->table('inventory_image');
        $builder->select('inventory_image.*');
        $builder->join('inventory', 'inventory.inventory_id = inventory_image.inventory_id', 'inner');
        $builder->where('inventory.deleted_at', null);
        $builder->whereIn("status", ["Instock"]);

        //Filter array for unprocessed images.
        //1 is used to indicate bulk update
        if (!empty($unprocessed_flag)) {
            if ($unprocessed_flag == "1") {
                $builder->groupStart()
                ->notLike("image_url", "http")
                ->where('inventory_image.image_with_overlay_url', null)
                ->orWhere('inventory_image.image_with_overlay_url', '')
                ->orLike('inventory_image.image_with_overlay_url', getenv('image.cdn'))
                ->groupEnd();
            }
        }

        $builder->orderBy('sort_rank', 'ASC');

        $query = $builder->get();

        foreach ($query->getResult() as $row) {
            $arr = array(
                'db' => $database_name,
                'folder' => $overlay_folder_name,
                'overlay' => $overlay_file_name,
                'image' => $row->image_url,
            );

            $msg = new AMQPMessage(json_encode($arr),    array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
            $channel->basic_publish($msg, $this->exchange, $this->routing_key);
        }

        $channel->close();
        $connection->close();
    }

    /**
     * sendSingleImage 
     *
     * @param  mixed $arg There parameters have to be passed  : TENANT_DB FOLDER_WHERE_IMAGES_STORED OVERLAY_IMAGE_FILE INVENTORY_IMAGE_NAME
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
        $overlay_folder_name = $arg[1];
        $overlay_file_name = $arg[2];
        $image_file_name = $arg[3];

        $image = "/" . UPLOAD_FOLDER . "/" . md5($database_name) . "/" . $image_file_name;

        $arr = array(
            'db' => $database_name,
            'folder' => $overlay_folder_name,
            'overlay' => $overlay_file_name,
            'image' => $image,
        );

        $msg = new AMQPMessage(json_encode($arr),    array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
        $channel->basic_publish($msg, $this->exchange, $this->routing_key);

        $channel->close();
        $connection->close();
    }


    /**
     * sendUnprocessedImages This queues the images for processing images
     *
     * @return void
     */
    public function sendUnprocessedImages()
    {
        $clients = $this->client_repository->scheduleQueueBasedOnColumn('image_overlay_schedule');
        $db = \Config\Database::connect();

        $clients = $clients['data_list'];

        foreach ($clients as $client) {

            try {
                $db->setDatabase($client['subdomain'] . '_' . $client['client_hash']);

                $image_queuing_flag = (new ConfigurationRepository())->findConfigurationColumnsByKey('image_overlay_queuing', 'value');
                if ($image_queuing_flag->value == '1') {
                    (new ImageController())->executeOverlayForUnprocessedImages();
                    CLI::write("Image processing : " . $client['subdomain'] . '_' . $client['client_hash']);
                }

                $minutes = rand(120, 180);
                $current = Carbon::now('UTC');
                $scheduled_datetime = $current->copy()->addMinutes($minutes);
                $this->updateSchedule($client['client_id'], $scheduled_datetime);
            } catch (\Exception $ex) {
                CLI::write("Exception was thrown : " . $ex->getMessage());
                $this->updateSchedule($client['client_id']);
            }
        }
    }

    /**
     * receive This processes the images
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
        $channel->queue_declare($this->queue, false, true, false, false);
        $channel->exchange_declare($this->exchange, AMQPExchangeType::DIRECT, false, true, false);

        $channel->queue_bind($this->queue, $this->exchange, $this->routing_key);

        /** Append Consumer to the queue and the exchange */
        $consumerTag = $channel->basic_consume($this->queue, '', false, false, false, false, function ($msg) {
            $associative_array = json_decode($msg->body, true);
            /* process queue only if the image files are present ***/

            if (CloudStorageFactory::getInstance()->has($associative_array['image'])) {

                try {
                    $file = new \CodeIgniter\Files\File('');
                    $db = \Config\Database::connect();
                    $db->setDatabase($associative_array['db']);

                    $query_builder = $db->table('inventory_image');
                    $query_builder->where('image_url', $associative_array['image']);
                    $query = $query_builder->get();
                    $query = $query->getRowArray();

                    if (!empty($query['image_with_overlay_url']) && !str_contains(strtoupper($query['image_with_overlay_url']), strtoupper(getenv('image.cdn')))) {
                        throw new Exception("Image With Overlay Already Present.", 1);
                    }

                    if (!file_exists(ROOTPATH . '/public/' . UPLOAD_FOLDER . "/" . md5($associative_array['db']))) {
                        mkdir(ROOTPATH . '/public/' . UPLOAD_FOLDER . "/" . md5($associative_array['db']), 0777, true);
                    }

                    if (!file_exists(ROOTPATH . '/public/' . UPLOAD_FOLDER . "/" . md5($associative_array['db']) . '/' . OVERLAY_FOLDER)) {
                        mkdir(ROOTPATH . '/public/' . UPLOAD_FOLDER . "/" . md5($associative_array['db']) . '/' . OVERLAY_FOLDER, 0777, true);
                    }

                    if (!file_exists(ROOTPATH . '/public/' . ROOT_IMAGE . "/" . md5($associative_array['db']))) {
                        mkdir(ROOTPATH . '/public/' . ROOT_IMAGE . "/" . md5($associative_array['db']), 0777, true);
                    }

                    $image_url = substr($associative_array['image'], 1);

                    $overlay_image_name = md5($image_url . $associative_array['overlay']);

                    $overlay_image_path = UPLOAD_FOLDER . "/" . md5($associative_array['db']) . '/' . OVERLAY_FOLDER . "/" . $overlay_image_name . '.jpg';

                    if (!CloudStorageFactory::getInstance()->has('/' . $overlay_image_path)) {

                        //save the image with the overlay
                        $overlay_url =  $associative_array['folder'] . "/" . md5($associative_array['db']) . "/" . $associative_array['overlay'];
                        CloudStorageFactory::getInstance()->get("/" . $overlay_url);

                        CLI::write(' [x] Image Url ' . $image_url . "\n");
                        CloudStorageFactory::getInstance()->get("/" . $image_url);

                        //save the image path in the db
                        $manager = new ImageManager(array('driver' => 'imagick'));

                        $width = $manager->make($overlay_url)->width();
                        $height = $manager->make($overlay_url)->height();
                        $watermark = $manager->make($overlay_url);

                        $original_image = $manager->make($image_url)->orientate()->resize($width, $height, function ($constraint) {
                            $constraint->aspectRatio();
                        });

                        $image = $manager->canvas($width, $height, 'e7e7e7')->insert($original_image, 'center')->insert($watermark)->save($overlay_image_path);
                        CloudStorageFactory::getInstance()->save(ROOTPATH . '/public/' . $overlay_image_path, '/' . $overlay_image_path);
                    }

                    $builder = $db->table('inventory_image');
                    $builder->where('image_url', $associative_array['image']);
                    $builder->update(['image_with_overlay_url' => '/' . $overlay_image_path]);

                    (new InventoryRepository())->updateTypesenseIndexService([$query["inventory_id"]]);
                    (new InventoryRepository())->purgeWebsiteCache([$query["inventory_id"]]);

                    //Remove Overlay image from local once stored on cloud
                    if (is_file(ROOTPATH . '/public/' . $overlay_image_path)) {
                        unlink(ROOTPATH . '/public/' . $overlay_image_path);
                    }

                    if (is_file(ROOTPATH . '/public/' .  $image_url)) {
                        unlink(ROOTPATH . '/public/' .  $image_url);
                    }

                    CLI::write(' [x] Recieved ' . $msg->body . "\n");
                    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
                } catch (NotReadableException $e) {
                    CLI::write(' [x] Image Url ' . $e->getMessage() . "\n");
                    $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag']);
                } catch (Exception $e) {
                    $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag']);
                    CLI::write(' [x] Image Url ' . $e->getMessage() . "\n");
                }
            } else {
                CLI::write(' [x] File Doesnot exist ' . $msg->body . "\n");
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

    private function updateSchedule($client_id, $scheduled_datetime = NULL): void
    {
        $db = \Config\Database::connect();
        $db->setDatabase(getenv('database.default.database'));
        $builder = $db->table('clients');
        $builder->where('client_id', $client_id);
        $builder->update(['image_overlay_schedule' => $scheduled_datetime]);
    }
}
