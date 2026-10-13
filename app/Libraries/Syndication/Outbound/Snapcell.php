<?php

namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Repositories\SyndicationRepository;
use Hug\Sftp\Sftp as Sftp;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\LocationRepository;
use Tenant\app\Repositories\ConfigurationRepository;

use App\Helpers\Utility;

class Snapcell extends SyndicationAbstract
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
      'dealer_name',
      'address',
      'phone',
      'postalcode',
      'email',
      'vehicle_id',
      'vin',
      'stockid',
      'year',
      'make',
      'model',
      'engine',
      'body',
      'trim',
      'transmission',
      'kilometers',
      'exterior_color',
      'price',
      'comments',
      'drivetrain',
      'video_url',
      'images',
      'fuel_type',
      'interior_color',
      'installed_options',
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

    foreach ($records as $key => $line) {
      if (!empty($line['image_with_overlay_urls'])) {
        $this->imageUrlLink = $this->formatImages($line['image_with_overlay_urls']);
      } elseif (!empty($line['image_urls'] )) {
        $this->imageUrlLink = $this->formatImages($line['image_urls']);
      }else {
        $this->imageUrlLink = "";
      }

      $line['vdp_url'] = Utility::urlCreation($bWebsite->value, $iType->value, $line, $wordPressType->value);

      $location = gettype($line['location']) == 'string' ? $line['location'] : "";
      $this->getBusinessLocation($location);
      $syndication[] = $this->formatLineRecord($line);
    }

    $directory = SYNDICATION_EXPORT. $this->config->client_id;
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
    $data['dealer_name'] = $this->validate($this->config->business_name);
    $data['address'] = $this->validate($this->config->business_address) . ', ' . $this->validate($this->config->business_city) . ', ' . $this->validate($this->config->business_state);
    $data['phone'] = $this->validate($this->config->business_phone);
    $data['postalcode'] = $this->validate($this->config->business_code);
    $data['email'] = $this->validate($this->config->business_email);
    $data['vehicle_id'] = $this->validate($line['stock_no']);
    $data['vin'] = $this->validate($line['vin']);
    $data['stockid'] = $this->validate($line['stock_no']);
    $data['year'] = $this->validate($line['year']);
    $data['make'] = $this->validate($line['make']);
    $data['model'] = $this->validate($line['model']);
    $data['engine'] = $this->validate($line['engine']);
    $data['body'] = $this->validate($line['body_type']);
    $data['trim'] = $this->validate($line['trim']);
    $data['transmission'] = $this->validate($line['transmission']);
    $data['kilometers'] = $this->validate($line['odometer']);
    $data['exterior_color'] = $this->validate($line['exterior_color']);
    $data['price'] = ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
    $comments = (new SyndicationRepository())->getInventoryDescParse($line);
    $data['comments'] = SyndicationUtility::parseComment($comments);
    $data['drivetrain'] = $this->validate($line['drivetrain']);
    $data['video_url'] = '';
    $data['images'] = $this->imageUrlLink;
    $data['fuel_type'] = $this->validate($line['fuel_type']);
    $data['interior_color'] = $this->validate($line['interior_color']);
    $data['installed_options'] = SyndicationUtility::parseSyncdicationOptions($line['standard'],';') ;   
    $data['VDPUrls'] = $this->validate($line['vdp_url']);     

    return $data;
  }

  public function uploadToFtp()
  {

    $csvfile = SYNDICATION_EXPORT . $this->config->client_id . '/' . $this->config->file_name;
    $status = 0;

    // uploading file
    $login =  Sftp::test($this->config->ftp_host, $this->config->ftp_user,  $this->config->ftp_pass, $this->config->ftp_port, $timeout = 14);
    if ($login) {
      $status =  Sftp::upload($this->config->ftp_host,$this->config->ftp_user,$this->config->ftp_pass,$csvfile,$this->config->file_name,$this->config->ftp_port, $timeout = 14);
    }
  
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
    $newFilename = mt_rand()."_".$this->config->file_name;
    $newFile = $file->move($directory, $newFilename);    
    return $newFile->getBasename();
  }

  public function getBusinessLocation($location_name)
  {
    $business = (new LocationRepository())->getLocationByName($location_name);
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