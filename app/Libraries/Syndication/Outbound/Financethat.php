<?php namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Repositories\SyndicationRepository;
use App\Libraries\FtpClient\FtpClient;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\LocationRepository;
use Tenant\app\Repositories\ConfigurationRepository;

class Financethat extends SyndicationAbstract {

  protected $imgPath = '/public/media/export/';
  protected $imageUrlLink;

  public function __construct($dataconfig) {
    $this->config = $dataconfig;
    $this->dbase = \Config\Database::connect();
    $this->dbase->setDatabase($this->config->db_name);
  }

  public function setHeader() {
    return $this->header = [
      'dealer_vehicle_id',
      'category',
      'vin',
      'make',
      'model',
      'year',
      'description',
      'v_condition',
      'kilometer',
      'images',
      'trim',
      'body_type',   
      'seating',
      'transmission',
      'fuel_type',
      'drive_train',
      'cylinder',
      'color',
      'price',
      'location',
      'city',
      'province',
      'country',
      'previous_owners',
      'previous_accidents',
      'features',
      'engine',
      'has_bumpup',
      'bumpup_active',
      'hin',
      'serial_number',
      'steering_type',
      'passengers',
      'hours',
      'length',
      'weight',
      'hull_material'
    ];
  }

  public function formatImages($images): string {
    $p = 0;

    $imageUrl = null;
    $photos = explode(";", $images);
    foreach ($photos as $pkey => $photo) {
      if ($p != 0) {
        $imageUrl .= ",";
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
     //$this->config->category = (new ConfigurationRepository())->findConfigurationColumnsByKey('financethat_category', ['value']);

    foreach ($records as $key => $line) {
       if (!empty($line['image_with_overlay_urls'])) {
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

  public function formatLineRecord($line) {

    $data['dealer_vehicle_id'] = $this->validate($line['vin']);
    $data['category'] =  $this->getCategoryType()  ;
    $data['vin'] = $this->validate($line['vin']);
    $data['make'] = $this->validate($line['make']);
    $data['model'] = $this->validate($line['model']);
    $data['year'] = $this->validate($line['year']);
    $comments = (new SyndicationRepository())->getInventoryDescParse($line); 
    $data['description'] = SyndicationUtility::parseComment($comments); 
    $data['v_condition'] =  "Used";
    $data['kilometer'] = $this->validate($line['odometer']) ;
    $data['images'] = $this->imageUrlLink;
    $data['trim'] = $this->validate($line['trim']);
    $data['body_type'] = $this->validate($line['body_type']);
    $data['seating'] =  "";
    $data['transmission'] = $this->validate($line['transmission']);
    $data['fuel_type'] = $this->validate($line['fuel_type']);
    $data['drive_train'] = $this->validate($line['drivetrain']);
    $data['cylinder'] = $this->validate($line['engine']); 
    $data['color'] = $this->validate($line['exterior_color']);
    $data['price'] =  ($line['special_price_on'] == "1" )? $line['special_price'] : $line['price'];  
    $data['location'] = $this->validate($this->config->business_address);
    $data['city'] = $this->validate($this->config->business_city);
    $data['province'] = $this->validate($this->config->business_state);
    $data['country'] = $this->validate($this->config->business_country);
    $data['previous_owners'] = "";
    $data['previous_accidents'] = "";
    $data['features'] = SyndicationUtility::parseSyncdicationOptions($line['standard'],',') ;
    $data['engine'] = $this->validate($line['engine']);
    $data['has_bumpup'] = "";
    $data['bumpup_active'] = "";
    $data['hin'] = "";
    $data['serial_number'] = "";
    $data['steering_type'] = "";
    $data['passengers'] =  $this->validate( (int) filter_var($line['passengers'], FILTER_SANITIZE_NUMBER_INT) );
    $data['hours'] = "";
    $data['length'] = "";
    $data['weight'] = "";
    $data['hull_material'] = "";

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


  public function getCategoryType($category = "")
  {
    if ($category != "") {
      $this->config->category = $category;
      return true;
    }

    $category = (new ConfigurationRepository())->findConfigurationColumnsByKey('financethat_category', ['value']);
    if (is_object($category)) {
      $business_category = $category->value;
    } else {
      $business_category =  $category;
    }
    $this->config->category = $business_category;
  }
}
