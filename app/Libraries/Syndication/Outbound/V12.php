<?php namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Repositories\SyndicationRepository;
use App\Libraries\FtpClient\FtpClient;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\ConfigurationRepository;

class V12 extends SyndicationAbstract {

  protected $imgPath = '/public/media/export/';
  protected $imageUrlLink;
  protected $imageUrlMain = "";
  protected $todayDateData ="";

  public function __construct($dataconfig) {
    $this->config = $dataconfig;
    $this->dbase = \Config\Database::connect();
    $this->dbase->setDatabase($this->config->db_name);
  }

  public function setHeader() {
    return $this->header = [
      'Dealer ID',
      'Stock Number',
      'Year',
      'Make',
      'Model',
      'Trim',
      'VIN',
      'Mileage',
      'Price',
      'Color',
      'Interior Color',
      'Transmission',
      'Image',
      'Description',
      'Options',
      'Vehicle Type',
      'Engine',
      'Drive-train',
      'Fuel Type',
      'Destination URL',
      'New/Used',
      'Certified',
      'Doors',            
      'MSRP',
      'Down-payment',
      'Fuel city min',
      'Fuel city max',
      'Highway city min',
      'Highway city max',
      'Title',      
      'Date added',
      'Car report',         
    ];
  }


  public function formatImages($images): string {
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

  public function generateFile() {

    $syndication = [];
    $records = (new SyndicationRepository())->inventoryListBySyndication($this->config->source);
    $this->setHeader();
    
    $this->config->icount = count($records);

     // CHECKING FOR IMAGE LAYOUT  
     $imageLayout = '1';
     $layoutCheck = (new ConfigurationRepository())->findConfigurationColumnsByKey('v12_overlay_enabled', ['value']);
 
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

  public function formatLineRecord($line) {

    $today = date("m/d/Y H:i:s");    
    $data['Dealer ID'] = $this->validate($this->config->identity_id) ;
    $data['Stock Number'] = $this->validate($line['stock_no']);
    $data['Year'] = $this->validate($line['year']);
    $data['Make'] = $this->validate($line['make']);
    $data['Model'] = $this->validate($line['model']);
    $data['Trim'] = $this->validate($line['trim']);
    $data['VIN'] = $this->validate($line['vin']);
    $data['Mileage'] = $this->validate($line['odometer']) ;
    $data['Price'] = ($line['special_price_on'] == "1" )? $line['special_price'] : $line['price'];
    $data['Color'] = $this->validate($line['exterior_color']);
    $data['Interior Color'] = $this->validate($line['interior_color']);
    $data['Transmission'] = $this->validate($line['transmission']);
    $data['Image'] = $this->imageUrlLink;   
    $comments = (new SyndicationRepository())->getInventoryDescParse($line); 
    $data['Description'] = SyndicationUtility::parseComment($comments);
    $data['Options'] = SyndicationUtility::parseSyncdicationOptions($line['standard'],';') ;
    $data['Vehicle Type'] = $this->validate($line['body_type']);
    $data['Engine'] = $this->validate($line['litres']); 
    $data['Drive-train'] = $this->validate($line['drivetrain']);
    $data['Fuel Type'] = $this->validate($line['fuel_type']);
    $data['Destination URL'] = '';
    $data['New/Used'] = 'USED'; 
    $data['Certified'] = '';
    $data['Doors'] = $this->validate($line['door']);
    $data['MSRP'] = '';
    $data['Down-payment'] = '';
    $data['Fuel city min'] = $this->validate($line['fuel_city']);
    $data['Fuel city max'] = '';
    $data['Highway city min'] = $this->validate($line['fuel_hwy']);
    $data['Highway city max'] = '';
    $data['Title'] =  $this->validate($line['year'])." ".$this->validate($line['make'])." ".$this->validate($line['model'])." ".$this->validate($line['trim']); 
    $data['Date added'] = '';
    $data['Car report'] = '';

    return $data;
  }

  public function uploadToFtp() {

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

  function validate($data) {
    $proData =  ($data == 'null') ? "" : $data;
    return $proData;
  }

  function archive(): string{
   
    $directory = ROOTPATH . $this->imgPath . $this->config->client_id;
    $csvfile = $directory . '/' . $this->config->file_name;
    $file = new \CodeIgniter\Files\File($csvfile);  
    $newFilename = mt_rand()."_".$this->config->file_name;
    $newFile = $file->move($directory, $newFilename);    
    return $newFile->getBasename();

  }
   
}
