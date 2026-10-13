<?php namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Repositories\SyndicationRepository;
use Hug\Sftp\Sftp as Sftp;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\LocationRepository;
use Tenant\app\Repositories\ConfigurationRepository;

class Cargurus extends SyndicationAbstract 
{
  protected $imageUrlLink;

  public function __construct($dataconfig) {
    $this->config = $dataconfig;
    $this->dbase = \Config\Database::connect();
    $this->dbase->setDatabase($this->config->db_name);
  }

  public function setHeader() {
    return $this->header = [
        'vin',
        'year',
        'make',
        'model',
        'trim',
        'price',
        'mileage',
        'images',
        'exterior_color',
        'comments',
        'stockid',
        'transmission',
        'installed_options',
        'dealer_id',
        'dealer_name',
        'address',
        'city',
        'state',
        'postalcode',
        'country',
        'email',
        'is_certified',
        'engine',
        'is_new',
        'phone',
        'website',
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
             $this->config->identity_id = $locationCheck['dealerid_cargurus'];
          }            
        }      
      }

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

  public function formatLineRecord($line) {

    $data['vin'] =  $this->validate($line['vin']);
    $data['year'] = $this->validate($line['year']);
    $data['make'] = $this->validate($line['make']);
    $data['model'] = $this->validate($line['model']);
    $data['trim'] = $this->validate($line['trim']);
    $data['price'] =  ($line['special_price_on'] == "1" )? $line['special_price'] : $line['price'];  
    $data['mileage'] = $this->validate($line['odometer']) ;
    $data['images'] = $this->imageUrlLink;
    $data['exterior_color'] = $this->validate($line['exterior_color']);
    $comments = (new SyndicationRepository())->getInventoryDescParse($line); 
    $data['comments'] = SyndicationUtility::parseComment($comments);  
    $data['stockid'] = $this->validate($line['stock_no']);
    $data['transmission'] = $this->validate($line['transmission']);
    $data['installed_options'] = SyndicationUtility::parseSyncdicationOptions($line['standard'],';') ;
    $data['dealer_id'] = $this->validate($this->config->identity_id) ;
    $data['dealer_name'] = $this->validate($this->config->business_name);    
    $data['address'] = $this->validate($this->config->business_address);
    $data['city'] = $this->validate($this->config->business_city);
    $data['state'] = $this->validate($this->config->business_state);
    $data['postalcode'] = $this->validate($this->config->business_code);
    $data['country'] = $this->validate($this->config->business_country);
    $data['email'] = $this->validate($this->config->business_email);
    $data['is_certified'] = 'N' ; // STATIC
    $data['engine'] = $this->validate($line['engine']);
    $data['is_new'] = 'N';      // STATIC
    $data['phone'] = $this->validate($this->config->business_phone);
    $data['website'] = "https://www.".$this->config->domain_name;

    return $data;
  }

  public function uploadToFtp()
  {
      $csvfile = SYNDICATION_EXPORT . $this->config->client_id . '/' . $this->config->file_name;
      $remoteFile = $this->config->file_name;
      $uploadResult = 0;

      $login = Sftp::test($this->config->ftp_host, $this->config->ftp_user, $this->config->ftp_pass, $this->config->ftp_port, $timeout = 14);
      if ($login) {
          $uploadResult = Sftp::upload($this->config->ftp_host, $this->config->ftp_user, $this->config->ftp_pass, $csvfile, $remoteFile, $this->config->ftp_port, $timeout = 14);
      }

      return $uploadResult;
  }


  public function validate($data) {
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
