<?php

namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Repositories\SyndicationRepository;
use App\Libraries\FtpClient\FtpClient;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\LocationRepository;

class Uobo extends SyndicationAbstract
{
  protected $imageUrlLink;
  protected $imageUrlMain = "";
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
      'Stock Number',
      'New/Used',
      'MSRP',
      'Year',
      'Make',
      'Model',
      'Body Style',
      'Series',
      'Exterior Colour',
      'Interior Colour',
      'Trim',
      'Engine Size',
      'Cylinder Count',
      'Door Count',
      'Drive configuration',
      'Additional Options',
      'Current Miles',
      'Date Added to Inventory',
      'Status',
      'Fuel Type',
      'Vehicle Location',
      'Certified Pre-owned',
      'Price',
      'Transmission Description',
      'Internet Description',
      'Vehicle Class',
      'Main Photo',
      'Main Photo Last Modified Date',
      'Extra Photos',
      'Extra Photo Last Modified Date',
      'carFAXLink'
    ];
  }

  public function formatImages($images): string
  {
    $p = 0;
    $imageUrl = null;
    //$picDate = null;

    $photos = explode(";", $images);
    foreach ($photos as $pkey => $photo) {
      if ($p == 0) {
        $this->imageUrlMain = SyndicationUtility::getImageUrl($photo);
      }
      if ($p != 0) {
        $imageUrl .= ";";
        //$picDate .= ';';
      }
      $p++;

      $imageUrl .= SyndicationUtility::getImageUrl($photo);
      //$picDate .= date("m/d/Y H:i:s",strtotime("-1 days"));
    }

    //$this->todayDateData = $picDate;
    return $imageUrl;
  }

  public function generateFile()
  {
    $syndication = [];
    $records = (new SyndicationRepository())->inventoryListBySyndication($this->config->source);
    $this->setHeader();

    $this->config->icount = count($records);

    foreach ($records as $key => $line) {
      $this->imageUrlLink = !empty($line['image_with_overlay_urls']) ? $this->formatImages($line['image_with_overlay_urls']) : (!empty($line['image_urls']) ? $this->formatImages($line['image_urls']) : '');

        $location = is_string($line['location']) ? $line['location'] : "";
      $this->getBusinessLocation($location);

      $line['carfax'] = (new SyndicationRepository())->inventoryGetFile('Vehicle History Report',$line['inventory_id']);      
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
    $data['VIN'] = $this->validate($line['vin']);
    $data['Stock Number'] = $this->validate($line['stock_no']);
    $data['New/Used'] = 'Used';
    $data['MSRP'] = $this->validate($line['price']);
    $data['Year'] = $this->validate($line['year']);
    $data['Make'] = $this->validate($line['make']);
    $data['Model'] = $this->validate($line['model']);
    $data['Body Style'] = $this->validate($line['body_type']);
    $data['Series'] = '';
    $data['Exterior Colour'] = $this->validate($line['exterior_color']);
    $data['Interior Colour'] = $this->validate($line['interior_color']);
    $data['Trim'] = $this->validate($line['trim']);
    $data['Engine Size'] = $this->validate($line['litres']);
    $data['Cylinder Count'] = '';
    $data['Door Count'] = $this->validate($line['door']);
    $data['Drive configuration'] = $this->validate($line['drivetrain']);
    $data['Additional Options'] = SyndicationUtility::parseSyncdicationOptions($line['standard'], ';');
    $data['Current Miles'] = $this->validate($line['odometer']);
    $data['Date Added to Inventory'] = $this->validate($line['stock_date']);
    $data['Status'] = $this->validate($line['status']);
    $data['Fuel Type'] = $this->validate($line['fuel_type']);
    $data['Vehicle Location'] = '';
    $data['Certified Pre-owned'] = '';
    $data['Price'] = ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
    $data['Transmission Description'] = $this->validate($line['transmission']);
    $data['Internet Description'] = '';
    $data['Vehicle Class'] = $this->validate($line['body_type']);
    $data['Main Photo'] = $this->imageUrlMain;
    $data['Main Photo Last Modified Date'] = '';
    $data['Extra Photos'] = $this->imageUrlLink;
    $data['Extra Photo Last Modified Date'] = '';
    $data['carFAXLink'] = $this->validate($line['carfax']);

    return $data;
  }

  public function uploadToFtp()
  {
    $csvfile = SYNDICATION_EXPORT . $this->config->client_id . '/' . $this->config->file_name;
    $status = 0;

    $ftp = new FtpClient();
    $ftp->connect($this->config->ftp_host, $this->config->ssl, $this->config->ftp_port);
    $ftp->login($this->config->ftp_user, $this->config->ftp_pass);
    $ftp->set_option(FTP_USEPASVADDRESS, false);
    $ftp->pasv(1);
    //$ftp->chdir($this->config->folder);
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