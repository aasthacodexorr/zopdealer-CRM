<?php

namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Helpers\Utility;
use App\Repositories\SyndicationRepository;
use App\Libraries\FtpClient\FtpClient;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\LocationRepository;
use Tenant\app\Libraries\Mapper\Msn as MSNMapper;

use Carbon\Carbon;

class Microsoft extends SyndicationAbstract
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
      'Vehicle Id',
      'Title',
      'Description',
      'Final Url',
      'Image urls',
      'Video urls',
      'Dealer Name',
      'Address',
      'City',
      'State',
      'Zip Code',
      'Phone Number',
      'Email',
      'Country',
      'Latitude',
      'Longitude',
      'Price',
      'Sale Price',
      'Dealer Discount',
      'Make',
      'Model',
      'Year',
      'Trim',
      'Mileage Value',
      'Mileage Unit',
      'Fuel Efficiency City',
      'Fuel Efficiency Highway',
      'Fuel Efficiency Average',
      'Fuel Efficiency Unit',
      'Transmission',
      'Fuel Type',
      'Body Style',
      'Drivetrain',
      'Engine',
      'VIN',
      'Condition',
      'Exterior Color',
      'Interior Color',
      'Availability',
      'State of Vehicle',
      'Disclaimer',
      'Down Payment',
      'Due at Signing',
      'Monthly Payment',
      'Daily Payment',
      'Payment Type',
      'Payment Term',
      'Final Mobile url',
      'Carfax_dealership_id',
      'Features',
      'Vehicle registration plate',
      'Sponsor'
    ];
  }

  public function formatImages($images): string
  {
    $p = 0;
    $imageUrl = null;
    $photos = explode(";", $images);
    foreach ($photos as $pkey => $photo) {
      if ($p != 0) {
        $imageUrl .= ";";
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
    $csv->fputcsv($this->header);

    foreach ($syndication as $key => $line) {
      $csv->fputcsv($line);
    }
    return true;
  }

  public function formatLineRecord($line)
  {
    $msnMapper = new MSNMapper();
    $data['Vehicle Id'] = $this->validate($line['stock_no']);
    $title = (new SyndicationRepository())->getInventoryTitle($line);
    $data['Title'] = SyndicationUtility::fixutf(strip_tags($title));
    $comments = (new SyndicationRepository())->getInventoryDescParse($line);
    $data['Description'] =  SyndicationUtility::parseComment($comments);
    $data['Final Url'] =  $this->validate($line['vdp_url']);
    $data['Image urls'] = $this->imageUrlLink;
    $data['Video urls'] = ""; //$this->validate($line['video']); 
    $data['Dealer Name'] = $this->validate($this->config->business_name);
    $data['Address'] = $this->validate($this->config->business_address);
    $data['City'] = $this->validate($this->config->business_city);
    $data['State'] = $this->validate($this->config->business_state);
    $data['Zip Code'] = $this->validate($this->config->business_code);
    $data['Phone Number'] = $this->validate($this->config->business_phone);
    $data['Email'] = $this->validate($this->config->business_email);
    $data['Country'] = $this->validate($this->config->business_country);
    $data['Latitude'] = "";
    $data['Longitude'] = "";
    $data['Price'] = ($line['special_price_on'] == "1") ? $line['special_price'] . ' CAD' : $line['price'] . ' CAD';
    $data['Sale Price'] = "";
    $data['Dealer Discount'] = "";
    $data['Make'] = $this->validate($line['make']);
    $data['Model'] = $this->validate($line['model']);
    $data['Year'] = $this->validate($line['year']);
    $data['Trim'] = $this->validate($line['trim']);
    $data['Mileage Value']  = $this->validate($line['odometer']);
    $data['Mileage Unit'] = $this->validate($line['km_miles']) == 'Miles' ? 'MI' : 'KM';
    $data['Fuel Efficiency City'] = $this->validate($line['fuel_city']);
    $data['Fuel Efficiency Highway'] = $this->validate($line['fuel_hwy']);
    $data['Fuel Efficiency Average'] = "";
    $data['Fuel Efficiency Unit'] = "";
    $data['Transmission'] = $this->validate($line['transmission']) == "Automatic" ? 'Automatic'  : 'Manual';
    $data['Fuel Type'] =  $msnMapper->exactMatch("fuel_types", $line['fuel_type'], "Gasoline");
    $data['Body Style'] =  $msnMapper->exactMatch("body_types", $line['body_type'], "Sedan");
    $data['Drivetrain'] = $msnMapper->exactMatch("drivetrain", $line['drivetrain'], "All wheel drive");
    $data['Engine'] =  $this->validate($line['litres']) . 'L ' . $this->validate($line['engine']);
    $data['VIN'] = $this->validate($line['vin']);
    $data['Condition'] = "";
    $data['Exterior Color'] = $this->validate($line['exterior_color']);
    $data['Interior Color'] = $this->validate($line['interior_color']);
    $data['Availability'] = "Available"; // STATIC
    $data['State of Vehicle'] = "";
    $data['Disclaimer'] = "";
    $data['Down Payment'] = "";
    $data['Due at Signing'] = "";
    $data['Monthly Payment'] = "";
    $data['Daily Payment'] = "";
    $data['Payment Type'] = "";
    $data['Payment Term'] = "";
    $data['Final Mobile url'] = "";
    $data['Carfax_dealership_id'] = "QSQZULZU1Z"; // static @todo Add Real
    $data['Features'] =  SyndicationUtility::parseSyncdicationOptions($line['standard'], ',');
    $data['Vehicle registration plate'] = "";
    $data['Sponsor'] = "Free";
    

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
