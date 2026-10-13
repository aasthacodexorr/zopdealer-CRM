<?php

namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Helpers\Utility;
use App\Repositories\SyndicationRepository;
use App\Libraries\FtpClient\FtpClient;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\LocationRepository;
use Tenant\app\Libraries\Mapper\Facebook as FacebookMapper;

class Fbaia extends SyndicationAbstract
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
      'vehicle_id',
      'title',
      'description',
      'price',
      'url',
      'body_style',
      'mileage.unit',
      'mileage.value',
      'address.addr1',
      'address.city',
      'address.country',
      'latitude',
      'longitude',  
      'neighborhood[0]',
      'state_of_vehicle',
      'make',
      'model',
      'year',
      'image[0].url',
      'image[0].tag[0]',
      'availability',
      'condition',
      'sale_price',
      'dealer_url',
      'vin',
      'vehicle_registration_plate',
      'transmission',
      'fuel_type',
      'vehicle_type',
      'dealer_privacy_policy_url',
      'dealer_communication_channel',
      'drivetrain',
      'days_on_lot',
      'previous_price',
      'address.addr2',	
      'address.addr3',	
      'address.city_id',	
      'address.region',	
      'address.postal_code',	
      'address.unit_number',	     	
      'exterior_color',
      'interior_color',
      'trim',
      'dealer_id',
      'dealer_name',
      'features[0].value',	
      'features[0].type',
      'features[1].value',	
      'features[1].type',	
      'features[2].value',	
      'features[2].type',	
      'vehicle_specifications[0].type',	
      'vehicle_specifications[0].units',	
      'vehicle_specifications[0].value',	
      'custom_number_0',	
      'custom_number_1',	
      'custom_number_2',	
      'stock_number',	
      'legal_disclosure_impressum_url',	
      'msrp',	
      'carfax_dealership_id',	
      'energy_efficiency_class_eu',	
      'vehicle_finance_types[0]',	
      'engine_size',	
      'horse_power',	
      'applink.android_app_name',	
      'applink.android_package',	
      'applink.android_url',	
      'applink.ios_app_name',	
      'applink.ios_app_store_id',	
      'applink.ios_url',	
      'applink.ipad_app_name',	
      'applink.ipad_app_store_id',	
      'applink.ipad_url',	
      'applink.iphone_app_name',	
      'applink.iphone_app_store_id',	
      'applink.iphone_url',	
      'applink.windows_phone_app_id',	
      'applink.windows_phone_app_name',	
      'applink.windows_phone_url',	
    ];
  }

  public function formatImages($images): string
  {
    $p = 0;
    $imageUrl = null;
    $photos = explode(";", $images);
    foreach ($photos as $pkey => $photo) {
      if ($p == 0) {
        $imageUrl = SyndicationUtility::getImageUrl($photo);
      }
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
      if (!empty($line['image_urls'])) {
        $this->imageUrlLink = $this->formatImages($line['image_urls']);
      } else {
        $this->imageUrlLink = "";
        continue; // skip record if no product images
      }

      $price =  ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
      if($price == 0){continue;}

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
    $facebookMapper = new FacebookMapper();
    $data['id'] =  $this->validate($line['stock_no']);
    $title = (new SyndicationRepository())->getInventoryTitle($line);
    $data['title'] = SyndicationUtility::fixutf(strip_tags($title));   
    $comments = (new SyndicationRepository())->getInventoryDescParse($line);
    $data['description'] = SyndicationUtility::parseComment($comments);
    if (strlen($data['description']) > 5000) {
      $data['description'] = substr($data['description'], 0, 5000);
    } 
    $data['price'] =  ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
    $data['link'] =  $this->validate($line['vdp_url']);
    $data['body_style'] =  $facebookMapper->exactMatch("body_types", $line['body_type'], "Other");
    $data['mileage.unit'] =   ($line['km_miles'] == "Miles")? "MI" : "KM" ;   
    $data['mileage.value'] =  $this->validate($line['odometer']);
    $data['address.addr1'] = $this->validate($this->config->business_address);
    $data['address.city'] =  $this->validate($this->config->business_city);
    $data['address.country'] =  $this->validate($this->config->business_country);
    $data['latitude'] =  "";
    $data['longitude'] =  "";
    $data['neighborhood[0]'] ="";
    $data['state_of_vehicle'] =  $facebookMapper->exactMatch("state_of_vehicle", $line['vehicle_type'], "Used");
    $data['brand'] =  $facebookMapper->exactMatch("makes", $line['make'], $line['make']);
    $data['model'] =   $facebookMapper->exactMatch("models", $line['model'], $line['model']);
    $data['year'] =  $this->validate($line['year']);
    $data['image[0].url'] =  $this->imageUrlLink;
    $data['image[0].tag[0]'] =  "";
    //$data['availability'] =  'available'; //static
    //$data['condition'] = "GOOD"; //static.
    $data['availability'] =  'in stock'; //static
    $data['condition'] = "used"; //static
    $data['sale_price'] =  '';
    $data['dealer_url'] =  "https://www." . $this->config->domain_name;
    $data['vin'] =  $this->validate($line['vin']);
    $data['vehicle_registration_plate'] = "";
    $data['transmission'] = $facebookMapper->exactMatch("transmission", $line['transmission'], "Manual");
    $data['fuel_type'] =  $facebookMapper->exactMatch("fuel_types", $line['fuel_type'], "Other");
    $data['vehicle_type'] = $facebookMapper->exactMatch("vehicle_type", $line['body_type'], "Other");
    $data['dealer_privacy_policy_url'] =  "";
    $data['dealer_communication_channel'] =  "";
    $data['drivetrain'] =  $facebookMapper->exactMatch("drivetrain", $line['drivetrain'], "Other");
    $data['days_on_lot'] =  "";
    $data['previous_price'] =  "";
    $data['address.addr2'] =  "";	
    $data['address.addr3'] =  "";	
    $data['address.city_id'] =  "";	
    $data['address.region'] =  $this->validate($this->config->business_state);
    $data['address.postal_code'] =  $this->validate($this->config->business_code);
    $data['address.unit_number'] =  "";	
    $data['exterior_color'] =  $facebookMapper->exactMatch("exterior_colors", $line['exterior_color'], "Other");
    $data['interior_color'] =  $facebookMapper->exactMatch("interior_colors", $line['interior_color'], "Other");
    $data['trim'] =  $this->validate($line['trim']);
    $data['dealer_id'] =  $this->validate($this->config->identity_id);
    $data['dealer_name'] =  $this->validate($this->config->business_name);
    $data['features[0].value'] =  "";	
    $data['features[0].type'] =  "";	
    $data['features[1].value'] =  "";	
    $data['features[1].type'] =  "";	
    $data['features[2].value'] =  "";	
    $data['features[2].type'] =  "";	
    $data['vehicle_specifications[0].type'] =  "";	
    $data['vehicle_specifications[0].units'] =  "";	
    $data['vehicle_specifications[0].value'] =  "";	
    $data['custom_number_0'] =  "";	
    $data['custom_number_1'] =  "";	
    $data['custom_number_2'] =  "";	
    $data['stock_number'] =  $this->validate($line['stock_no']);
    $data['legal_disclosure_impressum_url'] =  "";	
    $data['msrp'] =  "";	
    $data['carfax_dealership_id'] =  "";	
    $data['energy_efficiency_class_eu'] =  "NONE";
    $data['vehicle_finance_types[0]'] =  "";	
    $data['engine_size'] =   "";  //$this->validate($line['engine']);
    $data['horse_power'] =  "";	
    $data['applink.android_app_name'] =  "";	
    $data['applink.android_package'] =  "";	
    $data['applink.android_url'] =  "";	
    $data['applink.ios_app_name'] =  "";	
    $data['applink.ios_app_store_id'] =  "";	
    $data['applink.ios_url'] =  "";	
    $data['applink.ipad_app_name'] =  "";	
    $data['applink.ipad_app_store_id'] =  "";	
    $data['applink.ipad_url'] =  "";	
    $data['applink.iphone_app_name'] =  "";	
    $data['applink.iphone_app_store_id'] =  "";	
    $data['applink.iphone_url'] =  "";	
    $data['applink.windows_phone_app_id'] =  "";	
    $data['applink.windows_phone_app_name'] =  "";	
    $data['applink.windows_phone_url'] =  "";	

    return $data;
  }

  public function uploadToFtp()
  {
    $csvfile = SYNDICATION_EXPORT . $this->config->client_id . '/' . $this->config->file_name;
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
    $directory = SYNDICATION_EXPORT . $this->config->client_id;
    $csvfile = $directory . '/' . $this->config->file_name;
    $file = new \CodeIgniter\Files\File($csvfile);
    $newFilename = mt_rand() . "_" . $this->config->file_name;
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