<?php

namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Helpers\Utility;
use App\Libraries\FtpClient\FtpClient;
use App\Libraries\Syndication\SyndicationAbstract;
use App\Repositories\SyndicationRepository;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\LocationRepository;
use Tenant\app\Repositories\InventoryReportRepository;


class Inventorycc extends SyndicationAbstract
{
    protected $imageUrlLink;
    private $cost;  


    public function __construct($dataconfig)
    {
        $this->config = $dataconfig;
        $this->dbase = \Config\Database::connect();
        $this->dbase->setDatabase($this->config->db_name);
    }

    public function setHeader()
    {
        return $this->header = [
            'Dealer ID',
            'Dealer Name',
            'Is New',
            'Status',
            'VIN',
            'Stock',
            'Year',
            'Make',
            'Model',
            'Trim',
            'Body Style',
            'Body Type',
            'Factory Certified',
            'Dealer Certified',
            'Model Code',
            'List Price',
            'Special Price',
            'Cost Price',
            'ExtraPrice1',
            'ExtraPrice2',
            'ExtraPrice3',
            'Mileage',
            'Chrome Style ID',
            'Fuel Type',
            'Exterior color',
            'Exterior Color Code',
            'Interior Color',
            'Interior Color Code',
            'Interior Material',
            'Wheelbase',
            'DoorCount',
            'EngineDisplacement',
            'Cylinders',
            'Engine',
            'Drivetrain',
            'Transmission',
            'Transmission Speed',
            'Option Description',
            'Option code',
            'Photo URLs',
            'Photo Timestamp',
            'Dealer comments on vehicle',
            'In Stock Date',
            'Last Modified Date',
            'Video URL',
            'City MPG',
            'Hwy MPG',
            'VDP Link',
            'Extra Field 1',
            'Extra Field 2',
            'Extra Field 3',
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

            // Gettign value of cost from Purchase section
            $InventoryPurchase = new InventoryReportRepository();
            $response = $InventoryPurchase->findByInventoryId($line['inventory_id']);
            $this->cost = ''; // setting cost value
            if (!empty($response)) {        
                if($response[0]['net_purchase_total'] != 0){
                   $this->cost = $response[0]['net_purchase_total'];
                }
            }

            $syndication[] = $this->formatLineRecord($line);
        }

        $directory = SYNDICATION_EXPORT . $this->config->client_id;
        $csvfile = $directory . '/' . $this->config->file_name;

        // directory creation
        (is_dir($directory)) ?: mkdir($directory, 0777);

        //File creation
         $fp = fopen($csvfile, 'w');
         fputs($fp, implode(",",array_map([$this,'encodeFunc'], $this->header))."\r\n");

        foreach ($syndication as $key => $line) {
            fputs($fp, implode(",",array_map([$this,'encodeFunc'], $line))."\r\n");
        }
        fclose($fp);
        return true;
    }

    public  function encodeFunc($value) : string
    {
        ///remove any ESCAPED double quotes within string.
        $value = str_replace('\\"','"',$value);
        //then force escape these same double quotes And Any UNESCAPED Ones.
        $value = str_replace('"','\"',$value);
        //force wrap value in quotes and return
        return '"'.$value.'"';
    }

    public function formatLineRecord($line)
    {
        $data['Dealer ID'] = $this->validate($this->config->identity_id);
        $data['Dealer Name'] = $this->validate($this->config->business_name);
        $data['Is New'] = 'USED';
        $data['Status'] = 'Instock';
        $data['VIN'] = $this->validate($line['vin']);
        $data['Stock'] = $this->validate($line['stock_no']);
        $data['Year'] = $this->validate($line['year']);
        $data['Make'] = $this->validate($line['make']);
        $data['Model'] = $this->validate($line['model']);
        $data['Trim'] = $this->validate($line['trim']);
        $data['Body Style'] = '';
        $data['Body Type'] = $this->validate($line['body_type']);
        $data['Factory Certified'] = 'N';
        $data['Dealer Certified'] = 'N';
        $data['Model Code'] = "";
        $data['List Price'] =  $line['price'];
        $data['Special Price'] = ($line['special_price_on'] == "1") ? $line['special_price'] : "";
        $data['Cost Price'] = $this->cost;
        $data['ExtraPrice1'] = "";
        $data['ExtraPrice2'] = "";
        $data['ExtraPrice3'] = "";
        $data['Mileage'] = $this->validate($line['odometer']);
        $data['Chrome Style ID'] = "";
        $data['Fuel Type'] = $this->validate($line['fuel_type']);
        $data['Exterior color'] = $this->validate($line['exterior_color']);
        $data['Exterior Color Code'] = "";
        $data['Interior Color'] = $this->validate($line['interior_color']);
        $data['Interior Color Code'] = "";
        $data['Interior Material'] = "";
        $data['Wheelbase'] = "";
        $data['DoorCount'] = "";
        $data['EngineDisplacement'] = $this->validate($line['litres']);
        $data['Cylinders'] = "";
        $data['Engine'] = $this->validate($line['engine']);
        $data['Drivetrain'] = $this->validate($line['drivetrain']);
        $data['Transmission'] = $this->validate($line['transmission']);
        $data['Transmission Speed'] = "";
        $data['Option Description'] = SyndicationUtility::parseSyncdicationOptions($line['standard'], ';');
        $data['Option code'] = "";
        $data['Photo URLs'] = $this->imageUrlLink;
        $data['Photo Timestamp'] = "";
        $data['Dealer comments on vehicle'] = "";
        $data['In Stock Date'] = "";
        $data['Last Modified Date'] = "";
        $data['Video URL'] = $this->validate($line['video']);
        $data['City MPG'] = "";
        $data['Hwy MPG'] = "";
        $data['VDP Link'] = $this->validate($line['vdp_url']);
        $data['Extra Field 1'] = "";
        $data['Extra Field 2'] = "";
        $data['Extra Field 3'] = "";

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

    public function archive(): string
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
