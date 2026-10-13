<?php

namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Repositories\SyndicationRepository;
use App\Libraries\FtpClient\FtpClient;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\LocationRepository;

class Ez360 extends SyndicationAbstract
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
      'UNIQUEID',
      'CLIENTID',
      'MEDIAID',
      'STATUS',
      'VIN',
      'MAKE',
      'MODEL',
      'YEAR',
      'ENGINE',
      'TRANSMISSION',
      'EXTERIOR',
      'INTERIOR',
      'DESCRIPTION',
      'CUSTOMFEAT0',
      'CUSTOMFEAT1',
      'CUSTOMFEAT2',
      'CUSTOMFEAT3',
      'CUSTOMFEAT4',
      'CUSTOMFEAT5',
      'CUSTOMFEAT6',
      'CUSTOMFEAT7',
      'CUSTOMFEAT8',
      'CUSTOMFEAT9',
      'MILEAGE',
      'PRICEWEB',
      'TTSTEXT',
      'BATCHID',
      'DATERENDERED',
      'DELETEDATE',
      'ERRORCODE',
      'ERRORREMARKS',
      'BOOKINDATE',
      'NEWUSED',
      'STATUSFOB',
      'PRICEINVOICE',
      'PRICEPACK',
      'PRICECOST',
      'PRICELIST',
      'PRICEMSRP',
      'CONDITION',
      'TAGLINE',
      'CERTIFICATION',
      'CERTIFICATIONID',
      'MODELID',
      'TRIM',
      'SUBTRIM',
      'CODE',
      'PAYLOADCAPACITY',
      'SEATINGCAPACITY',
      'WHEELBASE',
      'BODY',
      'NUMDOORS',
      'DRIVETRAIN',
      'NUMCYLINDERS',
      'DESCTRANSMISSION',
      'EXTERIORBASE',
      'INTERIORDESC',
      'CREATEDDATE',
      'DEALERNAME',
      'DEALERLOCATION',
      'DEALERADDRESS',
      'DEALERCITY',
      'DEALERSTATE',
      'DEALERZIP',
      'DEALERPHONE',
      'SALESFULLNAME',
      'SALESPHONE',
      'SALESEMAIL',
      'CUSTOMTXT.0',
      'CUSTOMTXT.1',
      'CUSTOMTXT.2',
      'STOCKNO',
      'CARFAX',
      'HIGHWAYMPG',
      'CITYMPG',
      'CUSTOMDATE.0',
      'CUSTOMDATE.1',
      'CUSTOMBOOL.0',
      'CUSTOMBOOL.1',
      'NOPICS',
      'PIC.0',
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
      $p++;

      $imageUrl .= SyndicationUtility::getImageUrl($photo);
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
      $this->imageUrlLink = !empty($line['image_with_overlay_urls']) ? $this->formatImages($line['image_with_overlay_urls']) : (!empty($line['image_urls']) ? $this->formatImages($line['image_urls']) : '');

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
    $data['UNIQUEID'] = $this->validate($line['vin']);
    $data['CLIENTID'] = $this->validate($this->config->identity_id);
    $data['MEDIAID'] = '';
    $data['STATUS'] = '';
    $data['VIN'] = $this->validate($line['vin']);
    $data['MAKE'] = $this->validate($line['make']);
    $data['MODEL'] = $this->validate($line['model']);
    $data['YEAR'] = $this->validate($line['year']);
    $data['ENGINE'] = $this->validate($line['engine']);
    $data['TRANSMISSION'] = $this->validate($line['transmission']);
    $data['EXTERIOR'] = $this->validate($line['exterior_color']);
    $data['INTERIOR'] = $this->validate($line['interior_color']);
    $comments = (new SyndicationRepository())->getInventoryDescParse($line);
    $data['DESCRIPTION'] = SyndicationUtility::parseComment($comments);
    $data['CUSTOMFEAT0'] = '';
    $data['CUSTOMFEAT1'] = '';
    $data['CUSTOMFEAT2'] = '';
    $data['CUSTOMFEAT3'] = '';
    $data['CUSTOMFEAT4'] = '';
    $data['CUSTOMFEAT5'] = '';
    $data['CUSTOMFEAT6'] = '';
    $data['CUSTOMFEAT7'] = '';
    $data['CUSTOMFEAT8'] = '';
    $data['CUSTOMFEAT9'] = '';
    $data['MILEAGE'] = $this->validate($line['odometer']);
    $data['PRICEWEB'] = '';
    $data['TTSTEXT'] = '';
    $data['BATCHID'] = '';
    $data['DATERENDERED'] = '';
    $data['DELETEDATE'] = '';
    $data['ERRORCODE'] = '';
    $data['ERRORREMARKS'] = '';
    $data['BOOKINDATE'] = '';
    $data['NEWUSED'] = 'Used';
    $data['STATUSFOB'] = '';
    $data['PRICEINVOICE'] = '';
    $data['PRICEPACK'] = '';
    $data['PRICECOST'] = '';
    $data['PRICELIST'] = ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
    $data['PRICEMSRP'] = $line['price'];
    $data['CONDITION'] = '';
    $data['TAGLINE'] = '';
    $data['CERTIFICATION'] = '';
    $data['CERTIFICATIONID'] = '';
    $data['MODELID'] = '';
    $data['TRIM'] = $this->validate($line['trim']);
    $data['SUBTRIM'] = '';
    $data['CODE'] = '';
    $data['PAYLOADCAPACITY'] = '';
    $data['SEATINGCAPACITY'] = '';
    $data['WHEELBASE'] = '';
    $data['BODY'] = $this->validate($line['body_type']);
    $data['NUMDOORS'] = $this->validate($line['door']);
    $data['DRIVETRAIN'] = $this->validate($line['drivetrain']);
    $data['NUMCYLINDERS'] = '';
    $data['DESCTRANSMISSION'] = '';
    $data['EXTERIORBASE'] = '';
    $data['INTERIORDESC'] = '';
    $data['CREATEDDATE'] = '';
    $data['DEALERNAME'] = $this->validate($this->config->business_name);
    $data['DEALERLOCATION'] = '';
    $data['DEALERADDRESS'] = $this->validate($this->config->business_street);
    $data['DEALERCITY'] = $this->validate($this->config->business_city) ;
    $data['DEALERSTATE'] = $this->validate($this->config->business_state);
    $data['DEALERZIP'] = $this->validate($this->config->business_zipcode);
    $data['DEALERPHONE'] = $this->validate($this->config->business_phone);
    $data['SALESFULLNAME'] = '';
    $data['SALESPHONE'] = '';
    $data['SALESEMAIL'] = '';
    $data['CUSTOMTXT.0'] = '';
    $data['CUSTOMTXT.1'] = '';
    $data['CUSTOMTXT.2'] = '';
    $data['STOCKNO'] = $this->validate($line['stock_no']);
    $data['CARFAX'] = '';
    $data['HIGHWAYMPG'] = '';
    $data['CITYMPG'] = '';
    $data['CUSTOMDATE.0'] = '';
    $data['CUSTOMDATE.1'] = '';
    $data['CUSTOMBOOL.0'] = '';
    $data['CUSTOMBOOL.1'] = '';
    $data['NOPICS'] = $this->imageUrlLink;
    $data['PIC.0'] = '';

    #$data['DATE'] = $this->validate($line['image_timestamps']);

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
  private function setBusinessFields(array $business, array $fields): void
  {
    foreach ($fields as $field) {
      $this->config->{'business_' . $field} = $business[$field] ?? null;
    }
  }
}