<?php namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Repositories\SyndicationRepository;
use Hug\Sftp\Sftp as Sftp;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\InventoryReportRepository;

class Autocorp extends SyndicationAbstract
{
    protected $imageUrlLink;
    public function __construct($dataconfig)
    {
        $this->config = $dataconfig;
        $this->dbase = \Config\Database::connect();
        $this->dbase->setDatabase($this->config->db_name);
    }

    public function setHeader()
    {
        return $this->header = [
            'dealer_id',
            'vin',
            'vehicle_id',
            'year',
            'make',
            'model',
            'body',
            'trim',
            'transmission',
            'kilometers',
            'exterior_color',
            'price',
            'cost',
            'comments',
            'drivetrain',
            'images',
            'stock_status'
        ];
    }

    public function formatImages($images): string
    {
        $p = 0;

        $imageUrl = null;
        $photos = explode(";", $images);
        foreach ($photos as $pkey => $photo) {
            if ($p != 0) {
                $imageUrl .= "|";
            }

            //$imageUrl .= SyndicationUtility::getCdnUrl(). trim($photo);
            $imageUrl .= SyndicationUtility::getImageUrl($photo);
            $p++;
        }

        return $imageUrl;
    }

    public function generateFile()
    {

        $syndication = [];
        $records = (new SyndicationRepository())->inventoryListBySyndication($this->config->source);
        $this->setHeader();

        $this->config->icount = count($records);

        // CHECKING FOR IMAGE LAYOUT
        $imageLayout = '1';
        $layoutCheck = (new ConfigurationRepository())->findConfigurationColumnsByKey('carpages_overlay_enabled', ['value']);

        if (is_object($layoutCheck)) {
            if ($layoutCheck->value != "") {
                $imageLayout = $layoutCheck->value;
            }
        }

        foreach ($records as $key => $line) {
            if (!empty($line['image_with_overlay_urls']) && $imageLayout != '0') {
                $this->imageUrlLink = $this->formatImages($line['image_with_overlay_urls']);
            } elseif (!empty($line['image_urls'])) {
                $this->imageUrlLink = $this->formatImages($line['image_urls']);
            } else {
                $this->imageUrlLink = "";
            }

            // Gettign value of cost from Purchase section
            $InventoryPurchase = new InventoryReportRepository();
            $response = $InventoryPurchase->findByInventoryId($line['inventory_id']);
            $this->cost = ''; // setting cost value
            if (!empty($response)) {
                $this->cost = $response[0]['net_purchase_total'];
            }

            $syndication[] = $this->formatLineRecord($line);
        }

        $directory = SYNDICATION_EXPORT . $this->config->client_id;
        $csvfile = $directory . '/' . $this->config->file_name;

        // directory creation
        (is_dir($directory)) ?: mkdir($directory, 0777);

        //File creation
        $file = new \CodeIgniter\Files\File($csvfile);
        $csv = $file->openFile('w');
        $csv->fputcsv($this->header);

        foreach ($syndication as $key => $line) {
            $csv->fputcsv($line);
        }
        return true;
    }

    public function formatLineRecord($line)
    {

        $data['dealer_id'] = $this->validate($this->config->identity_id);
        $data['vin'] = $this->validate($line['vin']);
        $data['vehicle_id'] = $this->validate($line['stock_no']);
        $data['year'] = $this->validate($line['year']);
        $data['make'] = $this->validate($line['make']);
        $data['model'] = $this->validate($line['model']);
        $data['body'] = $this->validate($line['body_type']);
        $data['trim'] = $this->validate($line['trim']);
        $data['transmission'] = $this->validate($line['transmission']);
        $data['kilometers'] = $this->validate($line['odometer']);
        $data['exterior_color'] = $this->validate($line['exterior_color']);
        $data['price'] = ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
        $comments = (new SyndicationRepository())->getInventoryDescParse($line);
        $data['cost'] = $this->cost;
        $data['comments'] = SyndicationUtility::parseComment($comments);
        $data['drivetrain'] = $this->validate($line['drivetrain']);
        $data['images'] = $this->imageUrlLink;
        $data['stock_status'] = "In Stock";

        return $data;
    }

    public function uploadToFtp()
    {
        $csvfile = SYNDICATION_EXPORT . $this->config->client_id . '/' . $this->config->file_name;
        $remoteFile = $this->config->file_name;
        $uploadResult = 0;

        $login = Sftp::test($this->config->ftp_host, $this->config->ftp_user, $this->config->ftp_pass, $this->config->ftp_port, $timeout = 14);
        if ($login) {
            $uploadResult = Sftp::upload($this->config->ftp_host, $this->config->ftp_user, $this->config->ftp_pass, $csvfile, $remoteFile, $this->config->ftp_port, $timeout = 14);
        }

        return $uploadResult;
    }    

    public function validate($data)
    {
        $proData = ($data == 'null') ? "" : $data;
        return $proData;

    }

    public function archive(): string
    {

        $directory = SYNDICATION_EXPORT . $this->config->client_id;
        $csvfile = $directory . '/' . $this->config->file_name;
        $file = new \CodeIgniter\Files\File($csvfile);
        $newFilename = mt_rand() . "_" . $this->config->file_name;
        $newFile = $file->move($directory, $newFilename);
        return $newFile->getBasename();

    }

}
