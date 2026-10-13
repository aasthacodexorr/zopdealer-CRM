<?php

namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Repositories\SyndicationRepository;
use App\Libraries\FtpClient\FtpClient;
use App\Libraries\Syndication\SyndicationAbstract;

class Carmedia extends SyndicationAbstract
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
      'VendorDealerID',
      'StockNumber',
      'VIN',
      'FuelType',
      'Year',
      'Make',
      'Model',
      'Trim',
      'Transmission',
      'DriveTrain',
      'Mileage',
      'VehicleType',
      'Description',
      'Price',
      'Condition',
      'ExteriorColour',
      'InteriorColour',
      'Images',
      'LastEdit'
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
    $records = (new SyndicationRepository())->inventoryListWithoutSyndication();
    $this->setHeader();

    $this->config->icount = count($records);

    foreach ($records as $key => $line) {
      if (!empty($line['image_urls'])) {
        $this->imageUrlLink = $this->formatImages($line['image_urls']);
      }else {
        $this->imageUrlLink = "";
      }      

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

    $data['VendorDealerID'] = $this->validate($this->config->identity_id); 
	  $data['StockNumber'] = $this->validate($line['stock_no']);
    $data['VIN'] = $this->validate($line['vin']);
	  $data['FuelType'] = $this->validate($line['fuel_type']); 
    $data['Year'] = $this->validate($line['year']);
    $data['Make'] = $this->validate($line['make']);
    $data['Model'] =  $this->validate($line['model']);
    $data['Trim'] = $this->validate($line['trim']);
	  $data['Transmission'] = $this->validate($line['transmission']);
	  $data['DriveTrain'] = $this->validate($line['drivetrain']);
	  $data['Mileage'] = $this->validate($line['odometer']);
	  $data['VehicleType'] = $this->validate($line['body_type']);
	  $comments = (new SyndicationRepository())->getInventoryDescParse($line); 
	  $data['Description'] = SyndicationUtility::parseComment($comments);    
	  $data['Price'] = ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
	  $data['Condition'] = "USED";
	  $data['ExteriorColour'] = $this->validate($line['exterior_color']);
	  $data['InteriorColour'] = $this->validate($line['interior_color']);
	  $data['Images'] =  $this->imageUrlLink;
	  $data['LastEdit'] = date("m/d/Y H:i:s"); 

    return $data;
  }

  public function uploadToFtp()
  {
    $csvfile = ROOTPATH . $this->imgPath . $this->config->client_id . '/' . $this->config->file_name;
    $status = 0;

    $ftp = new FtpClient();
    //$ftp->connect($host, $ssl = false, $port = 21, $timeout = 90)
    $ftp->connect($this->config->ftp_host,  $this->config->ssl, $this->config->ftp_port);
    $ftp->login($this->config->ftp_user, $this->config->ftp_pass);
    $ftp->pasv(1);
    $ftp->chdir($this->config->folder);
    $status = $ftp->put($this->config->file_name, $csvfile, FTP_BINARY);
    $ftp->close();

    return $status;
    // @TODO : Add case for error handling
  }

  public function validate($data)
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

}
