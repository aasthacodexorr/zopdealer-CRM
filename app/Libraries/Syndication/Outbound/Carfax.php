<?php namespace App\Libraries\Syndication\Outbound;

use App\Repositories\SyndicationRepository;
use Hug\Sftp\Sftp as Sftp;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\LocationRepository;
use Carbon\Carbon;

class Carfax extends SyndicationAbstract 
{
  public function __construct($dataconfig) {
    $this->config = $dataconfig;
    $this->dbase = \Config\Database::connect();
    $this->dbase->setDatabase($this->config->db_name);
  }

  public function setHeader() {
    return $this->header = [
      'DealerId',
      'DealerName',
      'LotLocation',
      'PostalCode',
      'VIN',
      'StockNumber',
      'InventoryDate',
      'InventoryType',
      'InvoicePrice',
      'Cost',
      'ListPrice',
      'MSRP',
      'Odometer',
      'OdometerUnit',
      'Year',
      'Make',
      'Model',
      'Trim',
      'Engine',
      'Drivetrain',
      'Transmission',
      'FuelType',
      'BodyType',
      'ExteriorColor',
      'IsCertified',
    ];
  }

  public function formatImages($images): string {

    return true;
  }

  public function generateFile() {

    $syndication = [];
    $records = (new SyndicationRepository())->inventoryListBySyndication($this->config->source);
    $this->setHeader();
    
    $this->config->icount = count($records);

    foreach ($records as $key => $line) {
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

  public function formatLineRecord($line) {

    $data['DealerId'] = $this->validate($this->config->identity_id);
    $data['DealerName'] = $this->validate($this->config->business_name);
    $data['LotLocation'] = $this->validate($this->config->business_city);
    $data['PostalCode'] = $this->validate($this->config->business_code);
    $data['VIN'] =  $this->validate($line['vin']);
    $data['StockNumber'] = $this->validate($line['stock_no']);
    $data['InventoryDate'] = Carbon::parse($line['created_at'])->format('m-d-Y');
    $data['InventoryType'] = 'Used';
    $data['InvoicePrice'] = ''; 
    $data['Cost'] = '';
    $data['ListPrice'] = ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
    $data['MSRP'] = '';
    $data['Odometer'] = $this->validate($line['odometer']);
    $data['OdometerUnit'] = $this->validate($line['km_miles']);
    $data['Year'] = $this->validate($line['year']);
    $data['Make'] = $this->validate($line['make']);
    $data['Model'] = $this->validate($line['model']);
    $data['Trim'] = $this->validate($line['trim']);
    $data['Engine'] = $this->validate($line['engine']);
    $data['Drivetrain'] = $this->validate($line['drivetrain']);
    $data['Transmission'] = $this->validate($line['transmission']);
    $data['FuelType']= $this->validate($line['fuel_type']); 
    $data['BodyType'] = $this->validate($line['body_type']);
    $data['ExteriorColor'] = $this->validate($line['exterior_color']);
    $data['IsCertified'] = "0"; //STATIC 

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
