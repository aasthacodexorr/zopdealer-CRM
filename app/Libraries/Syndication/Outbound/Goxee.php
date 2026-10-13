<?php

namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Repositories\SyndicationRepository;
use App\Libraries\FtpClient\FtpClient;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\LocationRepository;
use Tenant\app\Repositories\ConfigurationRepository;

class Goxee extends SyndicationAbstract
{
  protected $imageUrlLink;

  public function __construct($dataconfig)
  {
    $this->config = $dataconfig;
    $this->dbase = \Config\Database::connect();
    $this->dbase->setDatabase($this->config->db_name);
  }

  public function setHeader(): array
  {
    return $this->header = [
      'DealerID',
      'NewUsed',
      'VIN',
      'StockNumber',
      'Make',
      'Model',
      'Year',
      'Trim',
      'Body',
      'Miles',
      'Engine',
      'FuelType',
      'Transmission',
      'Price',
      'ExteriorColor',
      'InteriorColor',
      'Options',
      'Description',
      'PhotoURLs',
      'PhotoTimestamps'
    ];
  }

  public function formatImages($images): string
  {
    $imageUrl = '';
    $photos = explode(";", $images);
    foreach ($photos as $pkey => $photo) {
      $imageUrl .= SyndicationUtility::getImageUrl($photo) . ',';
    }

    return rtrim($imageUrl, ',');
  }

  public function generateFile()
  {
    $syndication = [];
    $records = (new SyndicationRepository())->inventoryListBySyndication($this->config->source);
    $this->setHeader();

    $this->config->icount = count($records);

     // CHECKING FOR IMAGE LAYOUT  
     $imageLayout = '1';
     $layoutCheck = (new ConfigurationRepository())->findConfigurationColumnsByKey('goxee_overlay_enabled', ['value']);
 
     if (is_object($layoutCheck)) {
       if ($layoutCheck->value != "") {
         $imageLayout = $layoutCheck->value;
       }
     } 

    foreach ($records as $key => $line) {
      //$this->imageUrlLink = !empty($line['image_with_overlay_urls']) ? $this->formatImages($line['image_with_overlay_urls']) : $this->formatImages($line['image_urls']);
      
      if (!empty($line['image_with_overlay_urls']) && $imageLayout!='0') {
        $this->imageUrlLink = $this->formatImages($line['image_with_overlay_urls']);
      } elseif (!empty($line['image_urls'])) {
        $this->imageUrlLink = $this->formatImages($line['image_urls']);
      } else {
        $this->imageUrlLink = "";
      }

      $location = is_string($line['location']) ? $line['location'] : "";
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
    $data['DealerID'] = $this->validate($this->config->identity_id);
    $data['NewUsed'] = 'U';
    $data['VIN'] = $this->validate($line['vin']);
    $data['StockNumber'] = $this->validate($line['stock_no']);
    $data['Make'] = $this->validate($line['make']);
    $data['Model'] = $this->validate($line['model']);
    $data['Year'] = $this->validate($line['year']);
    $data['Trim'] = $this->validate($line['trim']);
    $data['Body'] = $this->validate($line['body_type']);
    $data['Miles'] = $this->validate($line['odometer']);
    $data['Engine'] = $this->validate($line['litres']);
    $data['FuelType'] = $this->validate($line['fuel_type']);
    $data['Transmission'] = $this->validate($line['transmission']);
    $data['Price'] = ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
    $data['ExteriorColor'] = $this->validate($line['exterior_color']);
    $data['InteriorColor'] = $this->validate($line['interior_color']);
    $data['Options'] = SyndicationUtility::parseSyncdicationOptions($line['standard'], ',');
    $comments = (new SyndicationRepository())->getInventoryDescParse($line);
    $data['Description'] = SyndicationUtility::parseComment($comments);
    $data['PhotoURLs'] = $this->imageUrlLink;
    $data['PhotoTimestamps'] =  $this->validate($line['image_timestamps']);

    return $data;
  }

  public function uploadToFtp()
  {
    $csvfile = SYNDICATION_EXPORT . $this->config->client_id . '/' . $this->config->file_name;
    $status = 0;

    $ftp = new FtpClient();
    $ftp->connect($this->config->ftp_host, $this->config->ssl, $this->config->ftp_port);
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
    return ($data == 'null') ? "" : $data;
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
  private function getBusinessLocation(string $location_name)
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
  private function setBusinessFields(array $business, array $fields): void
  {
    foreach ($fields as $field) {
      $this->config->{'business_' . $field} = $business[$field] ?? null;
    }
  }
  
}