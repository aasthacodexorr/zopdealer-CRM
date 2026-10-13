<?php

namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Helpers\Utility;
use App\Repositories\SyndicationRepository;
use App\Libraries\FtpClient\FtpClient;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\LocationRepository;


/**
 *  Vast class for syndication export
 */
class Vast extends SyndicationAbstract
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
      "record_id", 
      "vin", 
      "stock_number", 
      "title", 
      "url", 
      "category", 
      "images", 
      "address", 
      "city", 
      "state", 
      "zip", 
      "country", 
      "seller_type", 
      "dealer_name", 
      "dealer_ID", 
      "dealer_email", 
      "dealer_phone", 
      "dealer_website", 
      "dealer_fee", 
      "make", 
      "model", 
      "trim", 
      "body", 
      "mileage", 
      "year", 
      "currency", 
      "price", 
      "MSRP", 
      "internet_price", 
      "selling_price", 
      "retail_price", 
      "invoice_price", 
      "exterior_color", 
      "interior_color", 
      "interior_material", 
      "doors", 
      "cylinders", 
      "engine_size", 
      "drive_type", 
      "transmission", 
      "cpo", 
      "description", 
      "standard_features", 
      "optional_features", 
      "seller_comments", 
      "vehicle_condition", 
      "listing_time", 
      "expire_time"     
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
    $csv = $file->openFile('w');
    $csv->fputcsv($this->header,",");

    foreach ($syndication as $key => $line) {
      $quotedRow = array_map(function($item) {
         // return '"'. str_replace('"', '""', $item) . '"';
        return str_replace('"', '""', $item);
      }, $line);

      $csv->fputcsv($quotedRow,",");
    }
    return true;
  }

  public function formatLineRecord($line)
  {
    $data['record_id'] = $this->validate($line['vin']);
    $data['vin'] = $this->validate($line['vin']);
    $data['stock_number'] = $this->validate($line['stock_no']);
    $title = (new SyndicationRepository())->getInventoryTitle($line);
    $data['title'] = SyndicationUtility::fixutf(strip_tags($title));    
    $data['url'] = $this->validate($line['vdp_url']);
    $data['category'] = $this->validate($line['vehicle_type']);
    $data['images'] = $this->imageUrlLink;
    $data['address'] = $this->validate($this->config->business_address);
    $data['city'] = $this->validate($this->config->business_city);
    $data['state'] = $this->validate($this->config->business_state);
    $data['zip'] = $this->validate($this->config->business_code);
    $data['country'] = "";
    $data['seller_type'] = "";
    $data['dealer_name'] = $this->validate($this->config->business_name);
    $data['dealer_ID'] = $this->validate($this->config->identity_id);
    $data['dealer_email'] = $this->validate($this->config->business_email);
    $data['dealer_phone'] = $this->validate($this->config->business_phone);
    $data['dealer_website'] = "https://www.".$this->config->domain_name;
    $data['dealer_fee'] = "";
    $data['make'] = $this->validate($line['make']);
    $data['model'] = $this->validate($line['model']);
    $data['trim'] = $this->validate($line['trim']);
    $data['body'] = $this->validate($line['body_type']);
    $data['mileage'] = $this->validate($line['odometer']);
    $data['year'] = $this->validate($line['year']);
    $data['currency'] = "";
    $data['price'] = "";
    $data['MSRP'] = "";
    $data['internet_price'] = "";
    $data['selling_price'] = ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
    $data['retail_price'] = "";
    $data['invoice_price'] = "";
    $data['exterior_color'] = $this->validate($line['exterior_color']);
    $data['interior_color'] = $this->validate($line['interior_color']);
    $data['interior_material'] = "";
    $data['doors'] = $this->validate($line['door']);
    $data['cylinders'] = $this->validate($line['engine']);
    $data['engine_size'] = $this->validate($line['litres']);
    $data['drive_type'] = $this->validate($line['drivetrain']);
    $data['transmission'] = $this->validate($line['transmission']);
    $data['cpo'] = "";
    $comments = (new SyndicationRepository())->getInventoryDescParse($line);
    $data['description'] = SyndicationUtility::parseComment($comments);
    $data['standard_features'] = SyndicationUtility::parseSyncdicationOptions($line['standard'],';') ;
    $data['optional_features'] = "";
    $data['seller_comments'] = "";
    $data['vehicle_condition'] = "Used";
    $data['listing_time'] = "";
    $data['expire_time'] = "";           

    return $data;
  }

  public function uploadToFtp()
  {
    $csvfile = SYNDICATION_EXPORT . $this->config->client_id . '/' . $this->config->file_name;
    $status = 0;

    $ftp = new FtpClient();
    $ftp->connect($this->config->ftp_host,  $this->config->ssl, $this->config->ftp_port);
    $ftp->login($this->config->ftp_user, $this->config->ftp_pass);
    $ftp->pasv(1);
    $ftp->chdir($this->config->folder);
    $status = $ftp->put($this->config->file_name, $csvfile, FTP_BINARY);
    $ftp->close();

    return $status;
    // @TODO : Add case for error handling
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

}