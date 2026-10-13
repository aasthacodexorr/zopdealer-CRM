<?php

namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Repositories\SyndicationRepository;
use App\Libraries\FtpClient\FtpClient;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\LocationRepository;

class Autotraderglobalnpv extends SyndicationAbstract
{
  protected $imageUrlLink;
  protected $imageUrlMain = "";
  protected $todayDateData = "";

  public function __construct($dataconfig)
  {
    $this->config = $dataconfig;
    //  $this->config->client_id = "global"; // Override Client ID for global sheet
    $this->dbase = \Config\Database::connect();
    $this->dbase->setDatabase($this->config->db_name);
  }

  public function setHeader()
  {
    return $this->header = [
      'CompanyID',
      'Stock Number',
      'Year',
      'Make',
      'Model',
      'Trim',
      'Vin',
      'Price',
      'Main Photo',
      'Main Photo TimeStamp',
      'Photos',
      'Photo TimeStamps',
      'Vehicle Status',
      'Transmission',
      'KMS',
      'Doors',
      'No. of Passengers',
      'Exterior Color',
      'Interior Color',
      'Drive',
      'Engine Size',
      'Cylinders',
      'Fuel Type',
      'Body',
      'Options',
      'Desc',
      'Warranty (Numeric)',
      'Warranty Description',
      'Finance Down Payment',
      'Finance Lease Kilometers',
      'Finance Number Of Payments',
      'Finance Payment Type',
      'MFG Certification Program',
      'Ad Last Modified Date',
      'Category Id'    
    ];
  }

  public function formatImages($images): string
  {
    $p = 0;
    $imageUrl = null;
    $picDate = null;

    $photos = explode(";", $images);
    foreach ($photos as $pkey => $photo) {
      if ($p == 0) {
        $this->imageUrlMain = SyndicationUtility::getImageUrl($photo);
      }
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

    // CHECKING FOR IMAGE LAYOUT  
    $imageLayout = '1';
    $layoutCheck = (new ConfigurationRepository())->findConfigurationColumnsByKey('autotraderglobal_overlay_enabled', ['value']);
    $Gstat = $this->config->identity_id;

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
      $this->getCategoryType($line['autotrader_category']);

      $location = gettype($line['location']) == 'string' ? $line['location'] : "";
      $this->getBusinessLocation($location);
            
      if($Gstat == ""){
        $multipleDealerID = (new ConfigurationRepository())->findConfigurationColumnsByKey('syndication_multiple_location', ['value'])->value;           
        foreach (json_decode($multipleDealerID,true) as $locationCheck) { 
          if($locationCheck['label'] == $this->config->business_tname  ){
             $this->config->identity_id = $locationCheck['dealerid'];            
          }            
        }      
      }
      //  $line['carfax'] = (new SyndicationRepository())->inventoryGetFile('Vehicle History Report',$line['inventory_id']); 
      $syndication[] = $this->formatLineRecord($line);
    }

    $directory = SYNDICATION_EXPORT . $this->config->client_id;
    $csvfile = $directory . '/' . $this->config->file_name;

    // directory creation
    (is_dir($directory)) ?: mkdir($directory, 0777);

    //File creation
    $file = new \CodeIgniter\Files\File($csvfile);
    //$csv = $file->openFile('a');  // append mode
    //($csv->getSize() == 0) ? $csv->fputcsv($this->header) :'' ;  // logic to add header or not
    $csv = $file->openFile('w');
    $csv->fputcsv($this->header);

    foreach ($syndication as $key => $line) {
      $csv->fputcsv($line);
    }

    return true;
  }

  public function formatLineRecord($line)
  {
    $today = date("m/d/Y H:i:s",strtotime("-1 days"));
    $data['CompanyID'] = $this->validate($this->config->identity_id);
    $data['Stock Number'] = $this->validate($line['stock_no']);
    $data['Year'] = $this->validate($line['year']);
    $data['Make'] = $this->validate($line['make']);
    $data['Model'] = $this->validate($line['model']);
    $data['Trim'] = $this->validate($line['trim']);
    $data['Vin'] = $this->validate($line['vin']);
    $data['Price'] = ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
    $data['Main Photo']  = $this->imageUrlMain;
    $data['Main Photo TimeStamp'] = $today;
    $data['Photos'] = $this->imageUrlLink;
    $data['Photo TimeStamps'] = $this->todayDateData;
    $data['Vehicle Status'] = "Used";      //STATIC
    $data['Transmission'] = $this->validate($line['transmission']);
    $data['KMS'] = $this->validate($line['odometer']);
    $data['Doors'] = $this->validate($line['door']);
    $data['No. of Passengers'] = $this->validate((int) filter_var($line['passengers'], FILTER_SANITIZE_NUMBER_INT));
    $data['Exterior Color'] = $this->validate($line['exterior_color']);
    $data['Interior Color'] = $this->validate($line['interior_color']);
    $data['Drive'] = $this->validate($line['drivetrain']);
    $data['Engine Size'] = $this->validate($line['litres']);
    $data['Cylinders'] = $this->validate($line['engine']);
    $data['Fuel Type'] = $this->validate($line['fuel_type']);
    $data['Body'] = $this->validate($line['body_type']);
    $data['Options'] = SyndicationUtility::parseSyncdicationOptions($line['standard'], ';');
    $comments = (new SyndicationRepository())->getInventoryDescParse($line);
    $data['Desc'] =   SyndicationUtility::parseComment($comments);
    $data['Warranty (Numeric)'] = "";
    $data['Warranty Description'] = "";
    $data['Finance Down Payment'] = "";
    $data['Finance Lease Kilometers'] = "";
    $data['Finance Number Of Payments'] = "";
    $data['Finance Payment Type'] = "";
    $data['MFG Certification Program'] = "";
    $data['Ad Last Modified Date'] = $today;
    $data['Category Id'] =  $this->config->business_category;
    //$data['Carfax'] =  $this->validate($line['carfax']);
  
    return $data;
  }

  public function uploadToFtp()
  {
    return true;
  }

  function validate($data)
  {
    $proData =  ($data == 'null') ? "" : $data;
    return $proData;
  }

  function archive(): string
  {
    return $this->config->file_name;
  }

  public function getCategoryType($category = "")
  {

    if ($category != "") {
      $this->config->business_category = $category;
      return true;
    }

    $category = (new ConfigurationRepository())->findConfigurationColumnsByKey('autotrader_category', ['value']);
    if (is_object($category)) {
      $business_category = ($category->value != "") ? $category->value : '';
    } else {
      $business_category = '';
    }
    $this->config->business_category = $business_category;
  }

  public function getBusinessLocation($location_name){
    
    $business = (new LocationRepository())->getLocationByName($location_name);

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