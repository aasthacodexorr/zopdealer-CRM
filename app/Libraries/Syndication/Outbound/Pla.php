<?php

namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Repositories\SyndicationRepository;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\LocationRepository;

class Pla extends SyndicationAbstract
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
      'vin',
      'stock_no',
      'year',
      'make',
      'model',
      'price',
      'images',
      'vehicle_description',
      'body_type',
      'odometer',
      'km_miles',
      'transmission',
      'fuel_type',
      'drivetrain',
      'exterior_color',
      'interior_color',
      'trim'
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

    $this->config->icount = count($records);

    foreach ($records as $key => $line) {
      if (!empty($line['image_urls'])) {
        $this->imageUrlLink = $this->formatImages($line['image_urls']);
      } else {
        $this->imageUrlLink = "";
      }
      
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
    $data['vin'] = $this->validate($line['vin']);
    $data['stock_no'] = $this->validate($line['stock_no']);
    $data['year'] = $this->validate($line['year']);
    $data['make'] = $this->validate($line['make']);
    $data['model'] = $this->validate($line['model']);
    $data['Price'] = ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
    $data['images']  = $this->imageUrlLink;
    $comments = (new SyndicationRepository())->getInventoryDescParse($line). "<br><br>" .$this->config->business_name;
    $data['vehicle_description'] =   SyndicationUtility::parseComment($comments);
    $data['body_type'] = $this->validate($line['body_type']);
    $data['odometer'] = $this->validate($line['odometer']);
    $data['km_miles'] = $this->validate($line['km_miles']);
    $data['transmission'] = $this->validate($line['transmission']);
    $data['fuel_type'] = $this->validate($line['fuel_type']);
    $data['drivetrain'] = $this->validate($line['drivetrain']);
    $data['exterior_color'] = $this->validate($line['exterior_color']);
    $data['interior_color'] = $this->validate($line['interior_color']);
    $data['trim'] = $this->validate($line['trim']);

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
