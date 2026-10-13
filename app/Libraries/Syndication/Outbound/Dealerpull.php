<?php namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Repositories\SyndicationRepository;
use App\Libraries\FtpClient\FtpClient;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\InventoryReportRepository;

class Dealerpull extends SyndicationAbstract
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
            'DealerID',
            'VIN',
            'StockNumber',
            'Year',
            'Make',
            'Model',
            'Body',
            'TrimLevel',
            'Transmission',
            'Odometer',
            'ExteriorColor',
            'SalePrice',
            'CostTotal',
            'Description',
            'DriveTrain',
            'PhotoUrls',
            'ImageLastModifiedDates'
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

        $data['DealerID'] = $this->validate($this->config->identity_id);
        $data['VIN'] = $this->validate($line['vin']);
        $data['StockNumber'] = $this->validate($line['stock_no']);
        $data['Year'] = $this->validate($line['year']);
        $data['Make'] = $this->validate($line['make']);
        $data['Model'] = $this->validate($line['model']);
        $data['Body'] = $this->validate($line['body_type']);
        $data['TrimLevel'] = $this->validate($line['trim']);
        $data['Transmission'] = $this->validate($line['transmission']);
        $data['Odometer'] = $this->validate($line['odometer']);
        $data['ExteriorColor'] = $this->validate($line['exterior_color']);
        $data['SalePrice'] = ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
        $comments = (new SyndicationRepository())->getInventoryDescParse($line);
        $data['CostTotal'] = $this->cost;
        $data['Description'] = SyndicationUtility::parseComment($comments);
        $data['DriveTrain'] = $this->validate($line['drivetrain']);
        $data['PhotoUrls'] = $this->imageUrlLink;
        $data['ImageLastModifiedDates'] = $this->validate($line['image_timestamps']);

        return $data;
    }

    public function uploadToFtp()
    {

        $csvfile = SYNDICATION_EXPORT . $this->config->client_id . '/' . $this->config->file_name;
        $status = 0;

        $ftp = new FtpClient();
        //$ftp->connect($host, $ssl = false, $port = 21, $timeout = 90)
        $ftp->connect($this->config->ftp_host, $this->config->ssl, $this->config->ftp_port);
        $ftp->login($this->config->ftp_user, $this->config->ftp_pass);
        $ftp->pasv(1);
        $ftp->chdir($this->config->folder);
        $status = $ftp->put($this->config->file_name, $csvfile, FTP_BINARY);
        $ftp->close();

        return $status;
        // @TODO : Add case for error handling
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
