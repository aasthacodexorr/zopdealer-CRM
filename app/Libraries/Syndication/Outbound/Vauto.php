<?php

namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Helpers\Utility;
use App\Repositories\SyndicationRepository;
use Hug\Sftp\Sftp as Sftp;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\LocationRepository;
use Carbon\Carbon;

class Vauto extends SyndicationAbstract
{
  protected $imageUrlLink;

  public function __construct($dataconfig)
  {
    $this->config = $dataconfig;
    $this->config->client_id = "vauto"; // Override Client ID for global sheet
    $this->dbase = \Config\Database::connect();
    $this->dbase->setDatabase($this->config->db_name);
  }

  public function setHeader()
  {
    return $this->header = [
      'Vehicle ID',
      'Seller ID',
      'Vin',
      'Internet Price',
      'Conditional Price',
      'Seller Name',
      'Seller Address',
      'Seller City',
      'Seller Region',
      'Seller Phone',
      'Seller PostalCode',      
      'Detail URI',
      'Odometer',
      'New/Used',
      'Image Counts/URLS',
      'Stock Number',
      'Year',
      'Make',
      'Model',
      'Series',
      'IsCertified',
      'EngineDescription',
      'TransmissionDescription',
      'ExteriorColor',
      'InteriorColor',
      'BodyDoorCount',
      'EngineFuelType',
      'DriveTrainDescription',
      'Equipment',
      'SellerComments',
      'OEM Model Code',
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
      } elseif (!empty($line['image_urls'])) {
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
    $csv = $file->openFile('w');  // append mode
    //($csv->getSize() == 0) ? $csv->fputcsv($this->header) : '';  // logic to add header or not
    // Always write header (file is empty anyway)
    $csv->fputcsv($this->header);

    foreach ($syndication as $key => $line) {
      $csv->fputcsv($line);
    }

    return true;
  }

  public function formatLineRecord($line)
  {

    $data['Vehicle ID'] = $this->validate($line['stock_no']);
    $data['Seller ID'] = $this->validate($this->config->identity_id);
    $data['Vin'] = $this->validate($line['vin']);
    $data['Internet Price'] = ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
    $data['Conditional Price'] = "";
    $data['Seller Name'] = $this->config->business_name ;
    $data['Seller Address'] = $this->config->business_address;
    $data['Seller City'] =  $this->config->business_city;
    $data['Seller Region'] = $this->config->business_state ;
    $data['Seller Phone'] = $this->config->business_phone;
    $data['Seller PostalCode'] = $this->config->business_code;
    $data['Detail URI'] = $this->validate($line['vdp_url']);
    $data['Odometer'] = $this->validate($line['odometer']);
    $data['New/Used'] = "U"; //STATIC
    $data['Image Counts/URLS'] = $this->imageUrlLink;
    $data['Stock Number'] = $this->validate($line['stock_no']);
    $data['Year'] = $this->validate($line['year']);
    $data['Make'] = $this->validate($line['make']);
    $data['Model'] = $this->validate($line['model']);
    $data['Series'] = $this->validate($line['trim']);
    $data['IsCertified'] = 'N';
    $data['EngineDescription'] = $this->validate($line['engine']) . " " . $this->validate($line['litres']);
    $data['TransmissionDescription'] = $this->validate($line['transmission']);
    $data['ExteriorColor'] = $this->validate($line['exterior_color']);
    $data['InteriorColor'] = $this->validate($line['interior_color']);
    $data['BodyDoorCount'] = $this->validate($line['door']);
    $data['EngineFuelType'] = $this->validate($line['fuel_type']);
    $data['DriveTrainDescription'] = $this->validate($line['drivetrain']);
    $data['Equipment'] = "";
    $data['SellerComments'] = "";
    $data['OEM Model Code'] = "";

    return $data;
  }

  public function uploadToFtp()
  {
      $csvfile = SYNDICATION_EXPORT . $this->config->client_id . '/' . $this->config->file_name;
      $remoteFile = Carbon::now()->format('Ymdhis') . '.csv';    // Additional
      $uploadResult = 0;

      $login = Sftp::test($this->config->ftp_host, $this->config->ftp_user, $this->config->ftp_pass, $this->config->ftp_port, $timeout = 14);
      if ($login) {
          $uploadResult = Sftp::upload($this->config->ftp_host, $this->config->ftp_user, $this->config->ftp_pass, $csvfile, $remoteFile, $this->config->ftp_port, $timeout = 14);
      }

      return $uploadResult;
  }

  function validate($data)
  {
    $proData =  ($data == 'null') ? "" : $data;
    return $proData;
  }

  function archive(): string{
   
    $directory = SYNDICATION_EXPORT . $this->config->client_id;
    $csvfile = $directory . '/' . $this->config->file_name;  
   
    // condition based file archive mode 
    if(file_exists($csvfile)) {
      $modified_date =  date ("j", filemtime($csvfile));
      $today_date = date("j");
      if($today_date == $modified_date){
        return $this->config->file_name;
      }
    }
  
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
