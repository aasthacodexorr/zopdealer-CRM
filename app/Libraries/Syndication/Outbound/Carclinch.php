<?php
namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Repositories\SyndicationRepository;
use App\Libraries\FtpClient\FtpClient;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\LocationRepository;
use Tenant\app\Repositories\ConfigurationRepository;

class Carclinch extends SyndicationAbstract
{
    protected $imageUrlLink;
  protected $imageUrlMain = "";

  public function __construct($dataconfig)
  {
    $this->config = $dataconfig;
    $this->dbase = \Config\Database::connect();
    $this->dbase->setDatabase($this->config->db_name);
  }

  public function setHeader()
  {
    return $this->header = [
      'Dealer_ID',
      'VIN',
      'Make',
      'Model',
      'Year',
      'Trim',
      'Price',
      'Mileage',
      'IsKM',
      'Image_URLs',
      'Main_Image',
      'Exterior_Color',
      'Dealer_comments_on_vehicle',
      'Stock_Number',
      'Transmission_Type',
      'Installed_Options',
      'Fuel_Type',
      'Number_Seats',
      'Dealer_Name',
      'Dealer_Street_Address',
      'Dealer_City',
      'Dealer_State',
      'Dealer_ZIP',
      'Dealer_CRM_Email',
      'Interior_Color',
      'Certified',
      'IsNew',
      'Engine',
      'Dealer_Phone_Number',
      'Dealer_Website_URL',
      'Dealer_Latitude_and_Longitude',
      'Wholesale_Price',
      'Body_Type',
      'Drivetrain'
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
      $this->imageUrlMain = '';
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

    $directory = SYNDICATION_EXPORT .  $this->config->client_id;
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

    $data['Dealer_ID'] = $this->validate($this->config->identity_id);
    $data['VIN'] = $this->validate($line['vin']);
    $data['Make'] = $this->validate($line['make']);
    $data['Model'] = $this->validate($line['model']);
    $data['Year'] = $this->validate($line['year']);
    $data['Trim'] = $this->validate($line['trim']);
    $data['Price'] = ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
    $data['Mileage'] = $this->validate($line['odometer']);
    $data['IsKM'] = $this->validate($line['km_miles']) == 'Miles' ? 'N' : 'Y';
    $data['Image_URLs'] = $this->imageUrlLink;
    $data['Main_Image'] = $this->imageUrlMain;
    $data['Exterior_Color'] = $this->validate($line['exterior_color']);
    $comments = (new SyndicationRepository())->getInventoryDescParse($line);
    $data['Dealer_comments_on_vehicle'] = SyndicationUtility::parseComment($comments);
    $data['Stock_Number'] = $this->validate($line['stock_no']);
    $data['Transmission_Type'] = $this->validate($line['transmission']);
    $data['Installed_Options'] = SyndicationUtility::parseSyncdicationOptions($line['standard'], ';');
    $data['Fuel_Type'] = $this->validate($line['fuel_type']);
    $data['Number_Seats'] = $this->validate((int) filter_var($line['passengers'], FILTER_SANITIZE_NUMBER_INT));
    $data['Dealer_Name'] = $this->validate($this->config->business_name);
    $data['Dealer_Street_Address'] = $this->validate($this->config->business_address);
    $data['Dealer_City'] = $this->validate($this->config->business_city);
    $data['Dealer_State'] = $this->validate($this->config->business_state);
    $data['Dealer_ZIP'] = $this->validate($this->config->business_country);
    $data['Dealer_CRM_Email'] = $this->validate($this->config->business_email);
    $data['Interior_Color'] = $this->validate($line['interior_color']);
    $data['Certified'] = 'N';
    $data['IsNew'] = 'N';
    $data['Engine'] = $this->validate($line['engine']);
    $data['Dealer_Phone_Number'] = $this->validate($this->config->business_phone);
    $data['Dealer_Website_URL'] = "https://www." . $this->config->domain_name;
    $data['Dealer_Latitude_and_Longitude'] = '';
    $data['Wholesale_Price'] = '';
    $data['Body_Type'] = $this->validate($line['body_type']);
    $data['Drivetrain'] = $this->validate($line['drivetrain']);

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
    //$ftp->chdir($this->config->folder);
    $status = $ftp->put($this->config->file_name, $csvfile, FTP_BINARY);
    $ftp->close();

    return $status;
    // @TODO : Add case for error handling
  }

  public function validate($data)
  {
    $proData = ($data == 'null') ? "" : $data;
    return $proData;

  }

  public function archive(): string
  {

    $directory = SYNDICATION_EXPORT .  $this->config->client_id;
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
