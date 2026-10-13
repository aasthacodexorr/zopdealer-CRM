<?php

namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Repositories\SyndicationRepository;
use App\Libraries\FtpClient\FtpClient;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\LocationRepository;
use Tenant\app\Repositories\InventoryReportRepository;

class Homenet extends SyndicationAbstract
{

  protected $imgPath = '/public/media/export/';
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
      'VIN',
      'STOCK',
      'NEW-USED',
      'YEAR',
      'MAKE',
      'MODEL',
      'MODELNUMBER',
      'BODY',
      'TRANSMISSION',
      'TRIM',
      'DOORS',
      'MILES',
      'CYLINDERS',
      'DISPLACEMENT',
      'DRIVETRAIN',
      'EXTCOLOR',
      'EXTCOLOR CODE',
      'INTCOLOR',
      'INTCOLOR CODE',
      'INVOICE',
      'COST',
      'MSRP',
      'BOOKVALUE',
      'SELLINGPRICE',
      'DATE-IN-STOCK',
      'CERTIFIED',
      'DESCRIPTION',
      'OPTIONS',
      'OPTION CODES',
      'MPG HWY',
      'MPG CITY',
      'WHEELBASE',
      'STATUS CODE',
      'LOCATION CODE',
      'CHROME STYLE ID',
      'PHOTO TIME STAMP',
      'COMMENTS1',
      'COMMENTS2',
      'PhotoURLs'
    ];
  }

  public function formatImages($images): string {
    $p = 0;
    $imageUrl = null;
    $picDate=null;

    $photos = explode(";", $images);
    foreach ($photos as $pkey => $photo) {     
      if ($p != 0) {
        $imageUrl .= ";";
        $picDate .= ';';
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
    $this->setHeader();

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

      $syndication[] = $this->formatLineRecord($line);
      $this->todayDateData =null; // fix
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
    $data['DEALERID'] = $this->validate($this->config->identity_id);
    $data['VIN'] =  $this->validate($line['vin']);
    $data['STOCK'] = $this->validate($line['stock_no']);
    $data['NEW-USED'] = 'USED';
    $data['YEAR'] = $this->validate($line['year']);
    $data['MAKE'] =  $this->validate($line['make']);
    $data['MODEL'] = $this->validate($line['model']);
    $data['MODELNUMBER'] = "";
    $data['BODY'] =  $this->validate($line['body_type']);
    $data['TRANSMISSION'] = $this->validate($line['transmission']);
    $data['TRIM'] = $this->validate($line['trim']);
    $data['DOORS'] = $this->validate($line['door']);
    $data['MILES'] = $this->validate($line['odometer']);
    $data['CYLINDERS'] =  $this->validate($line['engine']);
    $data['DISPLACEMENT'] =  $this->validate($line['litres']);
    $data['DRIVETRAIN'] = $this->validate($line['drivetrain']);
    $data['EXTCOLOR'] =  $this->validate($line['exterior_color']);
    $data['EXTCOLOR CODE'] = "";
    $data['INTCOLOR'] = $this->validate($line['interior_color']);
    $data['INTCOLOR CODE'] = "";
    $data['INVOICE'] = "";
    $data['COST'] = $this->cost;
    $data['MSRP'] = "";
    $data['BOOKVALUE'] = ""; 
    $data['SELLINGPRICE'] = ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
    $data['DATE-IN-STOCK'] = $this->validate($line['stock_date']);
    $data['CERTIFIED'] = ""; 
    $comments = (new SyndicationRepository())->getInventoryDescParse($line); 
    $data['DESCRIPTION'] =  SyndicationUtility::parseComment($comments); 
    $data['OPTIONS'] =  SyndicationUtility::parseSyncdicationOptions($line['standard'],';') ;
    $data['OPTION CODES'] = "";
    $data['MPG HWY'] =  $this->validate($line['fuel_hwy']);
    $data['MPG CITY'] = $this->validate($line['fuel_city']);
    $data['WHEELBASE' ] = ""; 
    $data['STATUS CODE'] = "";
    $data['LOCATION CODE'] = "";
    $data['CHROME STYLE ID'] = "";
    $data['PHOTO TIME STAMP'] = $this->todayDateData  ;	  
    $data['COMMENTS1'] = "";
    $data['COMMENTS2'] = "";
    $data['PhotoURLs'] = $this->imageUrlLink;

    return $data;
  }

  public function uploadToFtp()
  {

    $csvfile = ROOTPATH . $this->imgPath . $this->config->client_id . '/' . $this->config->file_name;
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
    $proData =  ($data == 'null') ? "" : $data;
    return $proData;
  }

  public function archive(): string
  {
    $directory = ROOTPATH . $this->imgPath . $this->config->client_id;
    $csvfile = $directory . '/' . $this->config->file_name;
    $file = new \CodeIgniter\Files\File($csvfile);
    $newFilename = mt_rand()."_".$this->config->file_name;
    $newFile = $file->move($directory, $newFilename);    
    return $newFile->getBasename();
  }

}