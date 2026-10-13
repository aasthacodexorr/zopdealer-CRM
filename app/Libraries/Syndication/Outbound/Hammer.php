<?php

namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Helpers\Utility;
use App\Repositories\SyndicationRepository;
use App\Libraries\FtpClient\FtpClient;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\LocationRepository;
use Tenant\app\Repositories\ConfigurationRepository;


class Hammer extends SyndicationAbstract
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
      'VIN',
      'Condition',
      'Year',
      'Make',
      'Model',
      'Trim',
      'Price',
      'Mileage',
      'PhotoURLs',
      'ExteriorColor',
      'InteriorColor',
      'Description',
      'VDPLink',
      'StockNumber',
      'Engine',
      'Transmission',
      'Options'
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
    $bWebsite = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_website', ['value']);
    $iType = (new ConfigurationRepository())->findConfigurationColumnsByKey('inventory_type', ['value']);
    $wordPressType = (new ConfigurationRepository())->findConfigurationColumnsByKey('wordpress_type', ['value']);
    $this->config->icount = count($records);

    foreach ($records as $key => $line) {
      // find video links from file attributes
      $line['video'] = $video = '';
      if ($line['files'] != null) {
        $video = explode(';', $line['files']);
        foreach ($video as $video_data) {
          $video_data = explode('          ', $video_data);
          if ($video_data[0] == "Video Url") // static label name , will effect if changed in future
          {
            $line['video'] = $video_data[1];
          }
        }
      }
      $this->imageUrlLink = !empty($line['image_with_overlay_urls']) ? $this->formatImages($line['image_with_overlay_urls']) : (!empty($line['image_urls']) ? $this->formatImages($line['image_urls']) : '');

      $line['vdp_url'] = Utility::urlCreation($bWebsite->value, $iType->value, $line, $wordPressType->value);
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

  public function formatLineRecord($line): array
  {
    $data['VIN'] = $this->validate($line['vin']);
    $data['Condition'] = 'U';
    $data['Year'] = $this->validate($line['year']);
    $data['Make'] = $this->validate($line['make']);
    $data['Model'] = $this->validate($line['model']);
    $data['Trim'] = $this->validate($line['trim']);
    $data['Price'] = ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
    $data['Mileage'] = $this->validate($line['odometer']);
    $data['PhotoURLs'] = $this->imageUrlLink;
    $data['ExteriorColor'] = $this->validate($line['exterior_color']);
    $data['InteriorColor'] = $this->validate($line['interior_color']);
    $comments = (new SyndicationRepository())->getInventoryDescParse($line);
    $data['Description'] = SyndicationUtility::parseComment($comments);
    $data['VDPLink'] = $this->validate($line['vdp_url']);
    $data['StockNumber'] = $this->validate($line['stock_no']);
    $data['Engine'] = $this->validate($line['litres']);
    $data['Transmission'] = $this->validate($line['transmission']);
    $data['Options'] = SyndicationUtility::parseSyncdicationOptions($line['standard'], ',');

    return $data;
  }

  public function uploadToFtp(): bool
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