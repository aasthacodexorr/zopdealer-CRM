<?php

namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Repositories\SyndicationRepository;
use App\Libraries\FtpClient\FtpClient;
use App\Libraries\Syndication\SyndicationAbstract;
use CodeIgniter\Files\File;
use Config\Database;

/**
 * Ecarlist
 */
class Ecarlist extends SyndicationAbstract
{
  protected $imageUrlLink;

  public function __construct($dataconfig)
  {
    $this->config = $dataconfig;
    $this->dbase = Database::connect();
    $this->dbase->setDatabase($this->config->db_name);
  }

  public function setHeader()
  {
    return $this->header = [
      'DealerID',
      'StockNumber',
      'VIN',
      'Description',
      'Price',
      'MSRP',
      'Images',
    ];
  }

  public function formatImages($images): string
  {
    $p = 0;

    $imageUrl = null;
    $photos = explode(";", $images);
    foreach ($photos as $pkey => $photo) {
      if ($p != 0) {
        $imageUrl .= ",";
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
      $this->imageUrlLink = !empty($line['image_urls']) ? $this->formatImages($line['image_urls']) : "";
      $syndication[] = $this->formatLineRecord($line);
    }

    $directory = SYNDICATION_EXPORT . $this->config->client_id;
    $csvfile = $directory . '/' . $this->config->file_name;

    // directory creation
    (is_dir($directory)) ?: mkdir($directory, 0777);

    //File creation
    $file = new File($csvfile);
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
    $data['StockNumber'] = $this->validate($line['stock_no']);
    $data['VIN'] = $this->validate($line['vin']);
    $comments = (new SyndicationRepository())->getInventoryDescParse($line);
    $data['Description'] = SyndicationUtility::parseComment($comments);
    $data['Price'] = ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
    $data['MSRP'] = '';
    $data['Images'] = $this->imageUrlLink;

    return $data;
  }

  public function uploadToFtp()
  {
    $csvfile = SYNDICATION_EXPORT . $this->config->client_id . '/' . $this->config->file_name;
    $status = 0;

    $ftp = new FtpClient();
    //$ftp->connect($host, $ssl = false, $port = 21, $timeout = 90)
    $ftp->connect($this->config->ftp_host, $this->config->ssl, $this->config->ftp_port);
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
    $proData = ($data == 'null') ? "" : $data;
    return $proData;
  }

  function archive(): string
  {
    $directory = SYNDICATION_EXPORT . $this->config->client_id;
    $csvfile = $directory . '/' . $this->config->file_name;
    $file = new File($csvfile);
    $newFilename = mt_rand() . "_" . $this->config->file_name;
    $newFile = $file->move($directory, $newFilename);
    return $newFile->getBasename();
  }

}