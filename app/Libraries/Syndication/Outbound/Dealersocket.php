<?php

namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Repositories\SyndicationRepository;
use App\Libraries\FtpClient\FtpClient;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\ConfigurationRepository;
use Hug\Sftp\Sftp as Sftp;
use Tenant\app\Repositories\LocationRepository;
use Carbon\Carbon;

class Dealersocket extends SyndicationAbstract
{

  protected $imgPath = '/public/media/export/';
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
      'DealerID',
      'VIN',
      'Stock Number',
      'New/Used',
      'MSRP',
      'Year',
      'Make',
      'Model',
      'Body Style',
      'Series',
      'Exterior Color',
      'Interior Color',
      'Trim',
      'Engine Size',
      'Cylinder Count',
      'Door Count',
      'Transmission gear count',
      'Drive configuration',
      'Additional Options',
      'License Plate',
      'Current Miles',
      'Original Warranty Miles',
      'Extended Warranty Miles',
      'Extended Warranty Expiration Date',
      'Inventory Source',
      'Date Added to Inventory',
      'Cost',
      'Status',
      'Engine Description',
      'Fuel Type',
      'Warranty Experation Date',
      'Estimated Arrival Date',
      'Invoice Amount',
      'Holdback Amount',
      'License Fee',
      'License Expiration Date',
      'Weight',
      'Cost Pack Amount',
      'Base MSRP',
      'Base Retail',
      'Open RO Estimate',
      'Projected Cost',
      'Commissionable Cost',
      'GL Balance',
      'Advertisement Name',
      'Advertisement Begin Date',
      'Advertisement End Date',
      'Advertised Price',
      'Vehicle Location',
      'Internet Price',
      'Internet Price Expiration Date',
      'Certified Pre-owned',
      'Commission Pack Percent',
      'Date Received',
      'Retail Price Update Date',
      'Comission Pack Amount',
      'Invoice Pack Amount',
      'Price',
      'Retail Pack',
      'Certified Pre-owned Number',
      'Retail Price Update By',
      'Transmission Description',
      'Wheelbase Measurement',
      'Internet Description',
      'Seating Capacity',
      'Payload Capacity',
      'Vehicle Class',
      'Standard Features',
      'Exterior Description',
      'Interior Description',
      'Vehicle Type Code',
      'Condition',
      'List Price',
      'Chrome Style Id',
      'Image URLs',
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

  public function formatLineRecord($line)
  {
    $data['DealerID'] = $this->validate($this->config->identity_id);
    $data['VIN'] = $this->validate($line['vin']);
    $data['Stock Number'] = $this->validate($line['stock_no']);
    $data['New/Used'] = "Used";
    $data['MSRP'] = ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
    $data['Year'] = $this->validate($line['year']);
    $data['Make'] = $this->validate($line['make']);
    $data['Model'] = $this->validate($line['model']);
    $data['Body Style'] = $this->validate($line['body_type']);
    $data['Series'] = "";
    $data['Exterior Color'] = $this->validate($line['exterior_color']);
    $data['Interior Color'] =  $this->validate($line['interior_color']);
    $data['Trim'] = $this->validate($line['trim']);
    $data['Engine Size'] = $this->validate($line['litres']);
    $data['Cylinder Count'] =  $this->validate($line['engine']);
    $data['Door Count'] =  $this->validate($line['door']);
    $data['Transmission gear count'] = "";
    $data['Drive configuration'] = "";
    $data['Additional Options'] = "";
    $data['License Plate'] = "";
    $data['Current Miles'] = $this->validate($line['odometer']);
    $data['Original Warranty Miles'] = "";
    $data['Extended Warranty Miles'] = "";
    $data['Extended Warranty Expiration Date'] = "";
    $data['Inventory Source'] = "DMS";
    $data['Date Added to Inventory'] = Carbon::parse($line['created_at'])->format('m/d/Y');
    $data['Cost'] = "";
    $data['Status'] = "In Stock";
    $data['Engine Description'] =  $this->validate($line['litres']);
    $data['Fuel Type'] = $this->validate($line['fuel_type']);
    $data['Warranty Experation Date'] = "";
    $data['Estimated Arrival Date'] = "";
    $data['Invoice Amount'] = "";
    $data['Holdback Amount'] = "";
    $data['License Fee'] = "";
    $data['License Expiration Date'] = "";
    $data['Weight'] = "";
    $data['Cost Pack Amount'] = "";
    $data['Base MSRP'] = "";
    $data['Base Retail'] = "";
    $data['Open RO Estimate'] = "";
    $data['Projected Cost'] = "";
    $data['Commissionable Cost'] = "";
    $data['GL Balance'] = "";
    $data['Advertisement Name'] = "";
    $data['Advertisement Begin Date'] = "";
    $data['Advertisement End Date'] = "";
    $data['Advertised Price'] = "";
    $data['Vehicle Location'] = "";
    $data['Internet Price'] = "";
    $data['Internet Price Expiration Date'] = "";
    $data['Certified Pre-owned'] = "";
    $data['Commission Pack Percent'] = "";
    $data['Date Received'] = "";
    $data['Retail Price Update Date'] = "";
    $data['Comission Pack Amount'] = "";
    $data['Invoice Pack Amount'] = "";
    $data['Price'] = ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
    $data['Retail Pack'] = "";
    $data['Certified Pre-owned Number'] = "";
    $data['Retail Price Update By'] = "";
    $data['Transmission Description'] = $this->validate($line['transmission']);
    $data['Wheelbase Measurement'] = "";
    $comments = (new SyndicationRepository())->getInventoryDescParse($line);
    $data['Internet Description'] = SyndicationUtility::parseComment($comments);
    $data['Seating Capacity'] = "";
    $data['Payload Capacity'] = "";
    $data['Vehicle Class'] = "";
    $data['Standard Features'] = SyndicationUtility::parseSyncdicationOptions($line['standard'], ',');
    $data['Exterior Description'] = "";
    $data['Interior Description'] = "";
    $data['Vehicle Type Code'] = "";
    $data['Condition'] = "";
    $data['List Price'] = "";
    $data['Chrome Style Id'] = "";
    $data['Image URLs'] = $this->imageUrlLink;

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
    $proData =  ($data == 'null') ? "" : $data;
    return $proData;
  }

  function archive(): string
  {

    $directory = ROOTPATH . $this->imgPath . $this->config->client_id;
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
