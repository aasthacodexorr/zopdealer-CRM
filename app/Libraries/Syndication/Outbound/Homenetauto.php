<?php

namespace App\Libraries\Syndication\Outbound;

use Carbon\Carbon;
use App\Helpers\SyndicationUtility;
use App\Helpers\Utility;
use App\Repositories\SyndicationRepository;
use App\Libraries\FtpClient\FtpClient;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\LocationRepository;
use Tenant\app\Repositories\InventoryReportRepository;

class Homenetauto extends SyndicationAbstract
{
  protected $imageUrlLink;
  protected $todayDateData ="";
  private $cost;  

  public function __construct($dataconfig)
  {
    $this->config = $dataconfig;
    $this->dbase = \Config\Database::connect();
    $this->dbase->setDatabase($this->config->db_name);
  }

  public function setHeader()
  {
    return $this->header = [
      'DEALERID',
      'STOCK',
      'YEAR',
      'MAKE',
      'MODEL',
      'TRIM',
      'VIN',
      'MILEAGE',
      'PRICE',
      'EXTCOLOR',
      'INTCOLOR',
      'TRANSMISSION',
      'PHYSICAL_IMAGES',  
      'DESCRIPTION',
      'BODY',
      'ENGINE',
      'DRIVE_TYPE',
      'FUEL_TYPE',
      'OPTIONS',
      'ImageURLs',
      'VideoURLs',
      'VideoSource',
      'ModelCode',
      "VDPUrls"
    ];
  }

  public function formatImages($images): string {
    $p = 0;
    $imageUrl = null;
    $picDate=null;

    $photos = explode(";", $images);
    foreach ($photos as $pkey => $photo) {     
      if ($p != 0) {
        $imageUrl .= ",";
        $picDate .= ',';
      }     
      $p++;
            
      $imageUrl .= SyndicationUtility::getImageUrl($photo);
      $picDate .= date("m/d/Y H:i:s",strtotime("-1 days"));
    }

    $this->todayDateData = $picDate;
    return $imageUrl;
  }

  public function generateFile()
  {

    $syndication = [];
    $records = (new SyndicationRepository())->inventoryListBySyndication($this->config->source);
    //$this->setHeader();
    
    $bWebsite = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_website', ['value']);
    $iType = (new ConfigurationRepository())->findConfigurationColumnsByKey('inventory_type', ['value']);
    $wordPressType = (new ConfigurationRepository())->findConfigurationColumnsByKey('wordpress_type', ['value']);    

    $this->config->icount = count($records);

    foreach ($records as $key => $line) {
      if (!empty($line['image_with_overlay_urls'])) {
        $this->imageUrlLink = $this->formatImages($line['image_with_overlay_urls']);
      } elseif (!empty($line['image_urls'])) {
        $this->imageUrlLink = $this->formatImages($line['image_urls']);
      }else {
        $this->imageUrlLink = ""; 
      }      

      // Gettign value of cost from Purchase section
      $InventoryPurchase = new InventoryReportRepository();
      $response = $InventoryPurchase->findByInventoryId($line['inventory_id']);
      $this->cost = ''; // setting cost value
      if (!empty($response)) {        
        $this->cost = $response[0]['net_purchase_total'];
      }

      $line['vdp_url'] = Utility::urlCreation($bWebsite->value, $iType->value, $line, $wordPressType->value);

      $syndication[] = $this->formatLineRecord($line);
      $this->todayDateData =null; // fix
    }

//    Used car files: USED(dealer ID)_(mmddyy).txt
    $directory = SYNDICATION_EXPORT . $this->config->client_id;    
    $csvfile = $directory . '/' .$this->config->file_name.'_'.Carbon::now()->format('mdy') . '.txt';

    // directory creation
    (is_dir($directory)) ?: mkdir($directory, 0777);

    //File creation
    $file = new \CodeIgniter\Files\File($csvfile);
    $csv = $file->openFile('w');
   // $csv->fputcsv($this->header,",");

    foreach ($syndication as $key => $line) {
      $quotedRow = array_map(function($item) {
        return str_replace('"', '""', $item);
      }, $line);

      $csv->fputcsv($quotedRow,",");
    }
    
    return true;
  }

  public function formatLineRecord($line)
  {
    $data['DEALERID'] = $this->validate($this->config->identity_id);
    $data['STOCK'] = $this->validate($line['stock_no']);
    $data['YEAR'] = $this->validate($line['year']);
    $data['MAKE'] =  $this->validate($line['make']);
    $data['MODEL'] = $this->validate($line['model']);
    $data['TRIM'] = $this->validate($line['trim']);
    $data['VIN'] =  $this->validate($line['vin']);
    $data['MILEAGE'] = $this->validate($line['odometer']);
    $data['PRICE'] = ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
    $data['EXTCOLOR'] =  $this->validate($line['exterior_color']);
    $data['INTCOLOR'] = $this->validate($line['interior_color']);
    $data['TRANSMISSION'] = $this->validate($line['transmission']);
    $data['PHYSICAL_IMAGES'] = "";
    $comments = (new SyndicationRepository())->getInventoryDescParse($line); 
    $data['DESCRIPTION'] =  SyndicationUtility::parseComment($comments); 
    $data['BODY'] =  $this->validate($line['body_type']);
    $data['ENGINE'] =  $this->validate($line['engine']);
    $data['DRIVE_TYPE'] = $this->validate($line['drivetrain']);
    $data['FUEL_TYPE'] = $this->validate($line['fuel_type']);
    $data['OPTIONS'] =  SyndicationUtility::parseSyncdicationOptions($line['standard'],',') ;
    $data['ImageURLs'] = $this->imageUrlLink;
    $data['VideoURLs'] = "";
    $data['VideoSource'] = "";
    $data['ModelCode'] = "";
    $data['VDPUrls'] = $this->validate($line['vdp_url']);

    return $data;
  }

  public function uploadToFtp()
  {
    $newfile = $this->config->file_name.'_'.Carbon::now()->format('mdy') . '.txt';
    $csvfile = SYNDICATION_EXPORT . $this->config->client_id . '/' . $newfile ;
    $status = 0;

    $ftp = new FtpClient();
    //$ftp->connect($host, $ssl = false, $port = 21, $timeout = 90)
    $ftp->connect($this->config->ftp_host, $this->config->ssl, $this->config->ftp_port);
    $ftp->login($this->config->ftp_user, $this->config->ftp_pass);
    $ftp->pasv(1);
    $ftp->chdir($this->config->folder);
    $status = $ftp->put($newfile, $csvfile, FTP_BINARY);
    $ftp->close();

    return $status;
    // @TODO : Add case for error handling
  }

  public function validate($data)
  {
    $proData =  ($data == 'null') ? "" : $data;
    return $proData;
  }

  public function archive(): string
  {
    return $this->config->file_name.'_'.Carbon::now()->format('mdy') . '.txt';
  }

}