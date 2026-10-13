<?php

namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Repositories\SyndicationRepository;
use App\Libraries\FtpClient\FtpClient;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\ConfigurationRepository;
use App\Helpers\Utility;

class Quipdirect extends SyndicationAbstract
{

  protected $imgPath = '/public/media/export/';
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
      'DealerId',
      'Certified',
      'New/Used',
      'Stock #',
      'VIN',
      'Year',
      'Make',
      'Model',
      'Trim',
      'Odometer',
      'Price',
      'MSRP',
      'Interior Color',
      'Interior',
      'Exterior Color',
      'Exterior Base Color',
      'Body',
      'Engine',
      'Transmission',
      'OEM Model Code',
      'City MPG',
      'Highway MPG',
      'Doors',
      'Drivetrain',
      'Description',
      'Features',
      'Photo Url List',
      'VDPUrls'
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

    $bWebsite = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_website', ['value']);
    $iType = (new ConfigurationRepository())->findConfigurationColumnsByKey('inventory_type', ['value']);
    $wordPressType = (new ConfigurationRepository())->findConfigurationColumnsByKey('wordpress_type', ['value']);   

    $this->config->icount = count($records);

     // CHECKING FOR IMAGE LAYOUT  
     $imageLayout = '1';
     $layoutCheck = (new ConfigurationRepository())->findConfigurationColumnsByKey('quipdirect_overlay_enabled', ['value']);
 
     if (is_object($layoutCheck)) {
       if ($layoutCheck->value != "") {
         $imageLayout = $layoutCheck->value;
       }
     }

    foreach ($records as $key => $line) {
      if (!empty($line['image_with_overlay_urls']) && $imageLayout!='0') {
        $this->imageUrlLink = $this->formatImages($line['image_with_overlay_urls']);
      } elseif (!empty($line['image_urls'] )) {
        $this->imageUrlLink = $this->formatImages($line['image_urls']);
      }else {
        $this->imageUrlLink = "";
      }

      $line['vdp_url'] = Utility::urlCreation($bWebsite->value, $iType->value, $line, $wordPressType->value);

      $syndication[] = $this->formatLineRecord($line);
    }

    $directory = ROOTPATH . $this->imgPath . $this->config->client_id;
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

    $data['DealerId'] = $this->validate($this->config->identity_id);
    $data['Certified'] = ""; //STATIC
    $data['New/Used'] = "U"; //STATIC
    $data['Stock #'] = $this->validate($line['stock_no']);
    $data['VIN'] = $this->validate($line['vin']);
    $data['Year'] = $this->validate($line['year']);
    $data['Make'] = $this->validate($line['make']);
    $data['Model'] = $this->validate($line['model']);
    $data['Trim'] = $this->validate($line['trim']);
    $data['Odometer'] = $this->validate($line['odometer']);
    $data['Price'] = ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
    $data['MSRP'] = "";
    $data['Interior Color'] = $this->validate($line['interior_color']);
    $data['Interior'] = "";
    $data['Exterior Color'] = $this->validate($line['exterior_color']);
    $data['Exterior Base Color'] = "";
    $data['Body'] = $this->validate($line['body_type']);
    $data['Engine'] = $this->validate($line['engine']);
    $data['Transmission'] = $this->validate($line['transmission']);
    $data['OEM Model Code'] = "";
    $data['City MPG'] = $this->validate($line['fuel_city']);
    $data['Highway MPG'] =  $this->validate($line['fuel_hwy']);
    $data['Doors'] = $this->validate($line['door']);
    $data['Drivetrain'] =  $this->validate($line['drivetrain']);
    $comments = (new SyndicationRepository())->getInventoryDescParse($line);
    $data['Description'] = SyndicationUtility::parseComment($comments);
    $data['Features'] = SyndicationUtility::parseSyncdicationOptions($line['standard'], '|');
    $data['Photo Url List'] = $this->imageUrlLink;
    $data['VDPUrls'] = $this->validate($line['vdp_url']);

    return $data;
  }

  public function uploadToFtp()
  {

    $csvfile = ROOTPATH . $this->imgPath . $this->config->client_id . '/' . $this->config->file_name;
    $status = 0;

    $ftp = new FtpClient();
    //$ftp->connect($host, $ssl = false, $port = 21, $timeout = 90)
    $ftp->connect($this->config->ftp_host,  $this->config->ssl, $this->config->ftp_port);
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

    $directory = ROOTPATH . $this->imgPath . $this->config->client_id;
    $csvfile = $directory . '/' . $this->config->file_name;
    $file = new \CodeIgniter\Files\File($csvfile);
    $newFilename = mt_rand()."_".$this->config->file_name;
    $newFile = $file->move($directory, $newFilename);    
    return $newFile->getBasename();
  }
}
