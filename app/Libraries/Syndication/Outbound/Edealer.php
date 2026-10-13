<?php

namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Repositories\SyndicationRepository;
use App\Libraries\FtpClient\FtpClient;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\LocationRepository;

class Edealer extends SyndicationAbstract
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
      'rooftop_id',
      'dealer_name',
      'dealer_email',
      'dealer_phone',
      'dealer_address',
      'dealer_city',
      'dealer_region_code',
      'dealer_postal_code',
      'dealer_latitude',
      'dealer_longitude',
      'item_id',
      'vin',
      'stockNumber',
      'condition',
      'year',
      'make',
      'model',
      'sub_model',
      'trim',
      'price',
      'mileage',
      'body_type',
      'exterior_color',
      'interior_color',
      'number_of_doors',
      'number_of_passengers',
      'transmission',
      'drivetrain',
      'engine_displacement',
      'cylinders',
      'fuel_type',
      'description',
      'options',
      'photos',
      'created'
    ];
  }

  public function formatImages($images): string
  {
    $p = 0;

    $imageUrl = null;
    $photos = explode(";", $images);
    foreach ($photos as $pkey => $photo) {
      if ($p != 0) {
        $imageUrl .= ";";
      }

      //  $imageUrl .= SyndicationUtility::getCdnUrl(). trim($photo);
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
     $layoutCheck = (new ConfigurationRepository())->findConfigurationColumnsByKey('edealer_overlay_enabled', ['value']);
 
     if (is_object($layoutCheck)) {
       if ($layoutCheck->value != "") {
         $imageLayout = $layoutCheck->value;
       }
     }

    foreach ($records as $key => $line) {
      if (!empty($line['image_with_overlay_urls']) && $imageLayout!='0') {
        $this->imageUrlLink = $this->formatImages($line['image_with_overlay_urls']);
      } elseif (!empty($line['image_urls'])) {
        $this->imageUrlLink = $this->formatImages($line['image_urls']);
      }else {
        $this->imageUrlLink = "";
      }      

      $location = gettype($line['location']) == 'string' ? $line['location'] : "";
      $this->getBusinessLocation($location);
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

    $data['rooftop_id'] = $this->validate($this->config->identity_id);
    $data['dealer_name'] = $this->validate($this->config->business_name);
    $data['email'] = $this->validate($this->config->business_email);
    $data['phone'] = $this->validate($this->config->business_phone);
    $data['address'] = $this->validate($this->config->business_address);
    $data['dealer_city'] = $this->validate($this->config->business_city);
    $data['dealer_region_code'] = $this->validate($this->config->business_state);
    $data['dealer_postal_code'] = $this->validate($this->config->business_code);
    $data['dealer_latitude'] = "";
    $data['dealer_longitude'] = "";
    $data['item_id'] = $this->validate($line['stock_no']);
    $data['vin'] = $this->validate($line['vin']);
    $data['stockNumber'] = $this->validate($line['stock_no']);
    $data['condition'] = "USED"; //STATIC
    $data['year'] = $this->validate($line['year']);
    $data['make'] = $this->validate($line['make']);
    $data['model'] = $this->validate($line['model']);
    $data['sub_model'] = "";
    $data['trim'] = $this->validate($line['trim']);
    $data['price'] = ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
    $data['mileage'] = $this->validate($line['odometer']);
    $data['body_type'] = $this->validate($line['body_type']);
    $data['exterior_color'] = $this->validate($line['exterior_color']);
    $data['interior_color'] = $this->validate($line['interior_color']);
    $data['number_of_doors'] = $this->validate($line['door']);
    $data['number_of_passengers'] = $this->validate($line['passengers']);
    $data['transmission'] = $this->validate($line['transmission']);
    $data['drivetrain'] = $this->validate($line['drivetrain']);
    $data['engine_displacement'] = $this->validate($line['litres']);
    $data['cylinders'] = $this->validate($line['engine']);
    $data['fuel_type'] = $this->validate($line['fuel_type']);
    $comments = (new SyndicationRepository())->getInventoryDescParse($line); 
    $data['description'] = SyndicationUtility::parseComment($comments);  
    $data['options'] = ''; //PENDING
    $data['photos'] = $this->imageUrlLink;
    $data['created'] = date("m/d/Y H:i:s",strtotime("-1 days"));

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

  public function getBusinessLocation($location_name)
  {
    $business = (new LocationRepository())->getLocationByName($location_name);
    //$bWebsite = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_website', ['value']);
    //$this->config->business_website = $bWebsite->value ;

    if ($business) {
      $this->config->business_tname = $business['name'];
      $this->config->business_name = $business['business_name'];
      $this->config->business_address = $business['street'];
      $this->config->business_state = $business['state'];
      $this->config->business_city = $business['city'];
      $this->config->business_code = $business['zipcode'];
      $this->config->business_country = $business['country'];
      $this->config->business_phone = $business['phone'];
      $this->config->business_email = $business['email'];
    } else {
      // throws error  @TODO catch/handle no data error
    }
  }
}
