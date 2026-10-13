<?php
namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Helpers\Utility;
use App\Repositories\SyndicationRepository;
use Hug\Sftp\Sftp as Sftp;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\LocationRepository;


class Glo extends SyndicationAbstract 
{
  protected $imageUrlLink;
  protected $imageUrlMain = "";


  public function __construct($dataconfig) {
    $this->config = $dataconfig;
    $this->dbase = \Config\Database::connect();
    $this->dbase->setDatabase($this->config->db_name);
  }

  public function setHeader() {
    return $this->header = [
      'dealer_id',
      'vin',
      'stock_number',
      'image_urls',
      'exterior_images',
      'interior_images',
      'condition_images',	
      'gallery_images',
      'spin_embed_url',
      'spin_embed_code',
      'last_edit_timestamp',
      'thumbnail',
      'safety_features',
      'entertainment_features',
      'luxury_features',
      'car_type',
      'certificate',
      'doors',
      'engine',
      'exterior_color',
      'features',
      'fuel_type',
      'interior_color',
      'make',
      'mileage',
      'model',
      'title',
      'transmission',
      'trim',
      'type',
      'year',
      'landscape_video_tour',
      'portrait_video_tour',
      'price',
      'comments',
       'vdp_url',
    ];
  }

  public function formatImages($images): string
  {
    $p = 0;
    $imageUrl = null;

    $photos = explode(";", $images);
    foreach ($photos as $pkey => $photo) {
      if ($p == 0) {
        $this->imageUrlMain = SyndicationUtility::getImageUrl($photo);
      }
      if ($p != 0) {
        $imageUrl .= ",";
      }
      $p++;

      $imageUrl .= SyndicationUtility::getImageUrl($photo);
    }

    return $imageUrl;
  }

  public function generateFile() {

    $syndication = [];
    $records = (new SyndicationRepository())->inventoryListBySyndication($this->config->source);
    $this->setHeader();
      $bWebsite = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_website', ['value']);
      $iType = (new ConfigurationRepository())->findConfigurationColumnsByKey('inventory_type', ['value']);
      $wordPressType = (new ConfigurationRepository())->findConfigurationColumnsByKey('wordpress_type', ['value']);

      $this->config->icount = count($records);

    foreach ($records as $key => $line) {
      $this->imageUrlMain = '';
      if (!empty($line['image_urls'])) {
        $this->imageUrlLink = $this->formatImages($line['image_urls']);
      } else {
        $this->imageUrlLink = "";
      }

        $line['vdp_url'] = Utility::urlCreation($bWebsite->value, $iType->value, $line, $wordPressType->value);

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

    $data['dealer_id'] = $this->validate($this->config->identity_id);
    $data['vin'] =  $this->validate($line['vin']);
    $data['stock_number'] = $this->validate($line['stock_no']);
    $data['image_urls'] = $this->imageUrlLink;
    $data['exterior_images'] = "";
    $data['interior_images'] = "";
    $data['condition_images'] = "";
    $data['gallery_images'] = "";
    $data['spin_embed_url'] = "";
    $data['spin_embed_code'] = "";
    $data['last_edit_timestamp'] = strtotime($line['created_at']);
    $data['thumbnail'] = $this->imageUrlMain;
    $data['safety_features'] = "";
    $data['entertainment_features'] = "";
    $data['luxury_features'] = "";
    $data['car_type'] =  $this->validate($line['body_type']);
    $data['certificate'] = "";
   // $data['doors'] = $this->validate($line['door']);
   // $data['engine'] = $this->validate($line['engine']);
   // $data['exterior_color'] = $this->validate($line['exterior_color']);
    //$data['fuel_type'] = $this->validate($line['fuel_type']); 
    //$data['interior_color'] = $this->validate($line['interior_color']);
    //$data['mileage'] = $this->validate($line['odometer']);
    $data['doors'] = "";
    $data['engine'] = "";    
    $data['exterior_color'] = $this->validate($line['exterior_color']);
    $data['features'] = SyndicationUtility::parseSyncdicationOptions($line['standard'], ';');
    $data['fuel_type'] = ""; 
    $data['interior_color'] = $this->validate($line['interior_color']);
    $data['make'] = $this->validate($line['make']);    
    //$data['make'] = "";
    $data['mileage'] = $this->validate($line['odometer']) ;
    $data['model'] = $this->validate($line['model']);
    //$data['model'] = "";
    $title = (new SyndicationRepository())->getInventoryTitle($line);
    $data['title'] = SyndicationUtility::fixutf(strip_tags($title)); 
    //$data['transmission'] =  $this->validate($line['transmission']);
    $data['transmission'] =  "";
    $data['trim'] =  $this->validate($line['trim']);    
    //$data['trim'] =  "";        
    $data['type'] = "";
    $data['year'] =  $this->validate($line['year']);
    //$data['year'] =  "";
    $data['landscape_video_tour'] = "";
    $data['portrait_video_tour'] = "";
    $data['price'] = ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
    $comments = (new SyndicationRepository())->getInventoryDescParse($line);
    $data['comments'] = SyndicationUtility::parseComment($comments);
    $data['vdp_url'] = $this->validate($line['vdp_url']);

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
