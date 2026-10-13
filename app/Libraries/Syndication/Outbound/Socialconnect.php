<?php

namespace App\Libraries\Syndication\Outbound;

use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Libraries\SocialConnect\FacebookConnect;
use Tenant\app\Libraries\SocialConnect\InstagramConnect;
use Tenant\app\Libraries\SocialConnect\GoogleConnect;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\SocialConnectListRepository;

// @todo remove in future
set_time_limit(0);

class Socialconnect extends SyndicationAbstract
{

    public function __construct($dataconfig)
    {
        $this->config = $dataconfig;
        $this->dbase = \Config\Database::connect();
        $this->dbase->setDatabase($this->config->db_name);
    }

    public function setHeader()
    {
        return true;
    }

    public function formatImages($images): string
    {
        return true;
    }

    public function generateFile()
    {
        $syndication = [];
        $limit = 5;  // Manaul set for 5 posts by default
        $enableCheck = (new ConfigurationRepository())->findConfigurationColumnsByKey('feed_automatic', ['value']);

        $error = [];
        if ($enableCheck->value == 1) {
            $records = (new SocialConnectListRepository())->getYesterdayInventory()->findAll($limit);
            $this->config->icount = count($records);

            if (count($records) != 0) {
                $fbops = new FacebookConnect();
                $Igops = new InstagramConnect();
                $gops = new GoogleConnect();

                foreach ($records as $key => $line) {
                    try {
                        $response = $fbops->postToSocialById($line['inventory_id']);
                        if (isset($response['error'])) {
                            $error['fb_' . $line['inventory_id']] = $response['error'];
                        }

                        $responsex = $Igops->postToSocialById($line['inventory_id']);
                        if (isset($responsex['error'])) {
                            $error['ig_' . $line['inventory_id']] = $responsex['error'];
                        }

                        $responsey = $gops->postToSocialById($line['inventory_id']);
                        if (isset($responsey['error'])) {
                            $error['g_' . $line['inventory_id']] = $responsey['error'];
                        }

                    } catch (\Exception $e) {
                        $error['main_' . $line['inventory_id']] = $e->getMessage();
                    }
                    sleep(1);

                }
            }
        }

        return true;
    }

    public function formatLineRecord($line)
    {
        return true;
    }

    public function uploadToFtp()
    {
        return true;
    }

    public function validate($data)
    {
        return true;
    }

    public function archive(): string
    {
        return $this->config->file_name;
    }
}
