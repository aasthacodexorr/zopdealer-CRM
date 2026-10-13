<?php

namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Repositories\SyndicationRepository;
use Hug\Sftp\Sftp as Sftp;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\LocationRepository;

class Turnovercrm extends SyndicationAbstract
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
      'installed_options'
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
      if (!empty($line['image_with_overlay_urls'])) {
        $this->imageUrlLink = $this->formatImages($line['image_with_overlay_urls']);
      } elseif (!empty($line['image_urls'])) {
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
    $data['dealer_id'] = $this->validate($this->config->identity_id);
    $data['dealer_name'] = $this->validate($this->config->business_business_name);
    $data['address'] = $this->validate($this->config->business_street) . ', ' . $this->validate($this->config->business_city) . ', ' . $this->validate($this->config->business_state);
    $data['phone'] = $this->validate($this->config->business_phone);
    $data['postalcode'] = $this->validate($this->config->business_zipcode);
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
    $data['installed_options'] = SyndicationUtility::parseSyncdicationOptions($line['standard'], ';');

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

  function validate($data)
  {
    $proData = ($data == 'null') ? "" : $data;
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

  /**
   * Get business location by name and set config values
   *
   * @param string $locationName
   * @throws \Exception
   */
  public function getBusinessLocation(string $location_name)
  {
    $business = (new LocationRepository())->getLocationByName($location_name);

    if (!is_array($business)) {
      throw new \Exception('No business data provided');
    }

    $fields = ['name', 'business_name', 'street', 'state', 'city', 'zipcode', 'country', 'phone', 'email'];

    $this->setBusinessFields($business, $fields);
  }

  /**
   * Set business config values
   *
   * @param array $business
   * @param array $fields 
   * @throws \Exception
   */  
  private function setBusinessFields(array $business, array $fields) : void
  {
    foreach ($fields as $field) {
      $this->config->{'business_' . $field} = $business[$field] ?? null;
    }
  }
}