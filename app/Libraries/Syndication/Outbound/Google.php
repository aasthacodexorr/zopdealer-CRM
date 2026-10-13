<?php

namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Helpers\Utility;
use App\Repositories\SyndicationRepository;
use App\Libraries\FtpClient\FtpClient;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\LocationRepository;

/**
 *  Autotrader class for syndication export
 */
class Google extends SyndicationAbstract
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
            'store_code',
            'availability',
            'VIN',
            'id',
            'title',
            'description',
            'link_template',
            'link',
            'image_link',
            'price',
            'condition',
            'brand',
            'model',
            'year',
            'mileage',
            'body_style',
            'additional_image_link',
            'trim',
            'color',
            'vehicle_option',
            'google_product_category',
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
                $imageUrl .= ",";
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

        foreach ($records as $key => $line) {
            $this->imageUrlMain = '';
            if (!empty($line['image_urls'])) {
                $this->imageUrlLink = $this->formatImages($line['image_urls']);
            } else {
                $this->imageUrlLink = "";
            }

            $line['vdp_url'] = Utility::urlCreation($bWebsite->value, $iType->value, $line, $wordPressType->value);
            $location = gettype($line['location']) == 'string' ? $line['location'] : "";

            $this->getBusinessLocation($location);

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
        $today = date("Y-m-d", strtotime("-1 days"));
        //$country = "USA"; // feed only canada
        $data['store_code'] = $this->validate($this->config->identity_id) ;
        $data['availability'] = "in_stock" ;
        $data['VIN'] = $this->validate($line['vin']);
        $data['id'] = $this->validate($line['stock_no']);
        $title = (new SyndicationRepository())->getInventoryTitle($line);
        $data['title'] = SyndicationUtility::fixutf(strip_tags($title));
        $comments = (new SyndicationRepository())->getInventoryDescParse($line);
        $data['description'] =   SyndicationUtility::parseDescription($comments);
        $data['link_template'] =  $this->validate($line['vdp_url'])."?store={store_code}";
        $data['link'] =  $this->validate($line['vdp_url']);
        $data['image_link'] =  $this->imageUrlMain;
        $price =  ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
        $data['price'] = (int)$price. " CAD";
        $data['condition'] = "Used";
        $data['brand'] = $this->validate($line['make']);
        $data['model'] = $this->validate($line['model']);
        $data['year'] = $this->validate($line['year']);
        $data['mileage'] = $this->validate($line['odometer']).' '.$this->validate($line['km_miles']);
        $data['body_style'] =$this->validate($line['body_type']);
        $data['additional_image_link'] = $this->imageUrlLink;
        $data['trim'] = $this->validate($line['trim']);
        $data['color']  = $this->validate($line['exterior_color']);
        $data['vehicle_option'] = SyndicationUtility::parseSyncdicationOptions($line['standard'], ',');
        $data['google_product_category'] = '916'; // static as only allowed to Canada
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

    function validate($data)
    {
        $proData =  ($data == 'null') ? "" : $data;
        return $proData;
    }

    function archive(): string
    {

        $directory = SYNDICATION_EXPORT . $this->config->client_id;
        $csvfile = $directory . '/' . $this->config->file_name;

        $file = new \CodeIgniter\Files\File($csvfile);
        $newFilename = mt_rand() . "_" . $this->config->file_name;
        $newFile = $file->move($directory, $newFilename);
        return $newFile->getBasename();
    }

    public function getBusinessLocation($location_name){

        $business = (new LocationRepository())->getLocationByName($location_name);
        //$bWebsite = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_website', ['value']);
        //$this->config->business_website = $bWebsite->value ;

        if($business){
            $this->config->business_tname = $business['name'];
            $this->config->business_name = $business['business_name'];
            $this->config->business_address = $business['street'];
            $this->config->business_state = $business['state'];
            $this->config->business_city = $business['city'];
            $this->config->business_code = $business['zipcode'];
            $this->config->business_country = $business['country'];
            $this->config->business_phone = $business['phone'];
            $this->config->business_email = $business['email'];
        }else{
            // throws error  @TODO catch/handle no data error
        }
    }

}
