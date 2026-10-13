<?php namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Repositories\SyndicationRepository;
use App\Libraries\FtpClient\FtpClient;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\LocationRepository;
use Tenant\app\Repositories\ConfigurationRepository;

class Boost extends SyndicationAbstract {

  protected $imgPath = '/public/media/export/';
  protected $imageUrlLink;
  protected $website;

  public function __construct($dataconfig) {
    $this->config = $dataconfig;
    $this->dbase = \Config\Database::connect();
    $this->dbase->setDatabase($this->config->db_name);
  }

  public function setHeader() {
    return $this->header = [
        'Dealership_Boost_ID',
        'Dealership_Other_ID',
        'Dealership_Name',
        'Dealership_Website',
        'Dealership_Phone',
        'Dealership_Email',
        'Dealership_Address',
        'Dealership_City',
        'Dealership_Postal',
        'Dealership_Province',
        'Dealership_Latitude',
        'Dealership_Longitude',
        'Boost_Vehicle_ID',
        'Other_Vehicle_ID',
        'VIN',
        'Stock_Number',
        'Year',
        'Make',
        'Model',
        'SubModel_Trim',
        'Body_Style',
        'Exterior_Colour',
        'Interior_Colour',
        'Mileage',
        'Doors',
        'Seats',
        'Transmission',
        'Engine_Capacity',
        'Cylinders',
        'Price',
        'Entry_Date',
        'Last_Change_Date',
        'Interior_Features',
        'Exterior_Features',
        'Safety_Features',
        'Drivetrain_Features',
        'Description',
        'Comments',
        'Warranty_Text',
        'Certified',
        'CarProof',
        'YouTube',
        'Drivetrain',
        'FuelType',
        'VehicleStatus',
        'Images',
        'Features',
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
      $imageUrl .= SyndicationUtility::getImageUrl($photo);
      $p++;
    }

    return $imageUrl;
  }

  public function generateFile() {

    $syndication = [];
    $records = (new SyndicationRepository())->inventoryListBySyndication($this->config->source);
    $this->setHeader();
   
    $bWebsite = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_website', ['value']);
    $this->website =  $bWebsite->value ;

    $this->config->icount = count($records);

     // CHECKING FOR IMAGE LAYOUT  
     $imageLayout = '1';
     $layoutCheck = (new ConfigurationRepository())->findConfigurationColumnsByKey('cargurus_overlay_enabled', ['value']);
     $Gstat = $this->config->identity_id;

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

      if($Gstat == ""){
        $multipleDealerID = (new ConfigurationRepository())->findConfigurationColumnsByKey('syndication_multiple_location', ['value'])->value;           
        foreach (json_decode($multipleDealerID,true) as $locationCheck) { 
          if($locationCheck['label'] == $this->config->business_tname  ){
             $this->config->identity_id = $locationCheck['dealerid'];            
          }            
        }      
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

    $data['Dealership_Boost_ID'] =  $this->validate($this->config->identity_id);
    $data['Dealership_Other_ID'] = '';
    $data['Dealership_Name'] = $this->validate($this->config->business_name); 
    $data['Dealership_Website'] =   $this->website;
    $data['Dealership_Phone'] = $this->validate($this->config->business_phone);
    $data['Dealership_Email'] =  $this->validate($this->config->business_email);
    $data['Dealership_Address'] = $this->validate($this->config->business_address);
    $data['Dealership_City'] = $this->validate($this->config->business_city);
    $data['Dealership_Postal'] = $this->validate($this->config->business_code);
    $data['Dealership_Province'] = $this->validate($this->config->business_state);
    $data['Dealership_Latitude'] = '';
    $data['Dealership_Longitude'] = '';
    $data['Boost_Vehicle_ID'] = '';
    $data['Other_Vehicle_ID'] = $this->validate($line['stock_no']);
    $data['VIN'] =  $this->validate($line['vin']);  
    $data['Stock_Number'] = $this->validate($line['stock_no']);
    $data['Year'] =  $this->validate($line['year']);
    $data['Make'] =  $this->validate($line['make']); 
    $data['Model'] =  $this->validate($line['model']);
    $data['SubModel_Trim'] = $this->validate($line['trim']);
    $data['Body_Style'] = $this->validate($line['body_type']);
    $data['Exterior_Colour'] = $this->validate($line['exterior_color']);
    $data['Interior_Colour'] = $this->validate($line['interior_color']);
    $data['Mileage'] = $this->validate($line['odometer']) ;
    $data['Doors'] = $this->validate($line['door']);
    $data['Seats'] = "";
    $data['Transmission'] = $this->validate($line['transmission']);
    $data['Engine_Capacity'] = $this->validate($line['litres']); 
    $data['Cylinders'] = $this->validate($line['engine']);
    $data['Price'] = ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];             
    $data['Entry_Date'] = '';  
    $data['Last_Change_Date'] = ''; 
    $data['Interior_Features'] = '';  
    $data['Exterior_Features'] = ''; 
    $data['Safety_Features'] = '';  
    $data['Drivetrain_Features'] = ''; 
    $comments = (new SyndicationRepository())->getInventoryDescParse($line); 
    $data['Description'] =  SyndicationUtility::parseComment($comments); 
    $data['Comments'] = ''; 
    $data['Warranty_Text'] = '';  
    $data['Certified'] = 'FALSE'; 
    $data['CarProof'] = '';  
    $data['YouTube'] =  '';//$this->validate($line['video']);
    $data['Drivetrain'] = $this->validate($line['drivetrain']); 
    $data['FuelType'] = $this->validate($line['fuel_type']);
    $data['VehicleStatus'] = 'Used';  
    $data['Images'] = $this->imageUrlLink ; 
    $data['Features'] = SyndicationUtility::parseSyncdicationOptions($line['standard'], '|'); 

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

  public function validate($data) {
    $proData =  ($data == 'null') ? "" : $data;
    return $proData;

  }

  public function archive(): string{

    $directory = ROOTPATH . $this->imgPath . $this->config->client_id;
    $csvfile = $directory . '/' . $this->config->file_name;
    $file = new \CodeIgniter\Files\File($csvfile);
    $newFilename = mt_rand()."_".$this->config->file_name;
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
