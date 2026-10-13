<?php namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Helpers\Utility;
use App\Repositories\SyndicationRepository;
use App\Libraries\FtpClient\FtpClient;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\ConfigurationRepository;


class Googlemerchant extends SyndicationAbstract
{
    protected $imageUrlLink;
    protected $imageUrlMain = "";
    public function __construct($dataconfig)
    {
        $this->config = $dataconfig;
        $this->dbase = \Config\Database::connect();
        $this->dbase->setDatabase($this->config->db_name);
    }

    public function setHeader()
    {
        return $this->header = [
            'id',
            'title',
            'description',
            'link',
            'image_link',
            'price',
            'availability',
            'condition',
            'brand',
            'model',
            'year',
            'mileage',
            'fuel_type',
            'transmission',
            'color',
            'vehicle_msrp'
        ];
    }

    public function formatImages($images): string
    {
        $p = 0;
        $imageUrl = null;

        $photos = explode(";", $images);
        foreach ($photos as $pkey => $photo) {
            if ($p == 0) {
                $this->imageUrlMain = SyndicationUtility::getImageUrl($photo);
            }
            if ($p != 0) {
                $imageUrl .= ";";
            }
            $p++;

            $imageUrl .= SyndicationUtility::getImageUrl($photo);
        }

        return $imageUrl;
    }

    public function generateFile()
    {

        $syndication = [];
        $records = (new SyndicationRepository())->inventoryListBySyndication($this->config->source);
        $this->setHeader();
        $bWebsite = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_website', ['value']);
        $iType = (new ConfigurationRepository())->findConfigurationColumnsByKey('inventory_type', ['value']);
        $wordPressType = (new ConfigurationRepository())->findConfigurationColumnsByKey('wordpress_type', ['value']);
    
        $this->config->icount = count($records);

        // CHECKING FOR IMAGE LAYOUT
        $imageLayout = '1';
        $layoutCheck = (new ConfigurationRepository())->findConfigurationColumnsByKey('autorapter_overlay_enabled', ['value']);

        if (is_object($layoutCheck)) {
            if ($layoutCheck->value != "") {
                $imageLayout = $layoutCheck->value;
            }
        }

        foreach ($records as $key => $line) {
            $this->imageUrlMain = '';
            if (!empty($line['image_with_overlay_urls']) && $imageLayout != '0') {
                $this->imageUrlLink = $this->formatImages($line['image_with_overlay_urls']);
            } elseif (!empty($line['image_urls'])) {
                $this->imageUrlLink = $this->formatImages($line['image_urls']);
            } else {
                $this->imageUrlLink = "";
            }

            $line['vdp_url'] = Utility::urlCreation($bWebsite->value, $iType->value, $line, $wordPressType->value);          
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
        $data['id'] = $this->validate($line['stock_no']);
        $title = (new SyndicationRepository())->getInventoryTitle($line);
        $data['title'] = SyndicationUtility::fixutf(strip_tags($title));
        $comments = (new SyndicationRepository())->getInventoryDescParse($line);
        $data['description'] = SyndicationUtility::parseComment($comments);
        $data['link'] =  $this->validate($line['vdp_url']);
        $data['image_link'] = $this->imageUrlMain;
        $price =  ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
        $data['price'] =   number_format($price, 2, '.', '') . " CAD" ;
        $data['availability'] = 'in stock';
        $data['condition'] = 'used';
        $data['brand'] = $this->validate($line['make']);
        $data['model'] = $this->validate($line['model']);
        $data['year'] = $this->validate($line['year']);
        $data['mileage'] = $this->validate($line['odometer']);
        $data['fuel_type']= $this->validate($line['fuel_type']);
        $data['transmission'] = $this->validate($line['transmission']);
        $data['color'] = $this->validate($line['exterior_color']);
        $data['vehicle_msrp'] = $line['price'];
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
