<?php namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Helpers\Utility;
use App\Repositories\SyndicationRepository;
use App\Libraries\FtpClient\FtpClient;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\LocationRepository;


class Finalgear extends SyndicationAbstract
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
            'dealer_id',
            'stock',
            'vin',
            'year',
            'make',
            'model',
            'door',
            'body',
            'trim',
            'mileage',
            'exterior_color',
            'interior_color',
            'engine_cylinders',
            'engine_displacement',
            'transmission',
            'drivetrain',
            'highway_mpg',
            'city_mpg',
            'fuel_type',
            'selling_price',
            'msrp',
            'description',
            'options',
            'photo_urls',
            'vdp_url',
            'type',
            'carFAXLink'
        ];
    }

    public function formatImages($images): string
    {
        $p = 0;

        $imageUrl = null;
        $photos = explode(";", $images);
        foreach ($photos as $pkey => $photo) {
            if ($p != 0) {
                $imageUrl .= " ";
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

        // CHECKING FOR IMAGE LAYOUT
        $imageLayout = '1';
        $layoutCheck = (new ConfigurationRepository())->findConfigurationColumnsByKey('autorapter_overlay_enabled', ['value']);

        if (is_object($layoutCheck)) {
            if ($layoutCheck->value != "") {
                $imageLayout = $layoutCheck->value;
            }
        }

        foreach ($records as $key => $line) {
            if (!empty($line['image_with_overlay_urls']) && $imageLayout != '0') {
                $this->imageUrlLink = $this->formatImages($line['image_with_overlay_urls']);
            } elseif (!empty($line['image_urls'])) {
                $this->imageUrlLink = $this->formatImages($line['image_urls']);
            } else {
                $this->imageUrlLink = "";
            }

            $line['vdp_url'] = Utility::urlCreation($bWebsite->value, $iType->value, $line, $wordPressType->value);
            $location = gettype($line['location']) == 'string' ? $line['location'] : "";
            $this->getBusinessLocation($location);
            $carfax = (new SyndicationRepository())->inventoryGetFile('Vehicle History Report',$line['inventory_id']);
            $line['carfax'] = Utility::getImageUrl($carfax);
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
        $data['dealer_id'] = $this->validate($this->config->identity_id);
        $data['stock'] = $this->validate($line['stock_no']);
        $data['vin'] = $this->validate($line['vin']);
        $data['year'] = $this->validate($line['year']);
        $data['make'] = $this->validate($line['make']);
        $data['model'] = $this->validate($line['model']);
        $data['door'] = $this->validate($line['door']);
        $data['body'] = $this->validate($line['body_type']);
        $data['trim'] = $this->validate($line['trim']);
        $data['mileage'] = $this->validate($line['odometer']);
        $data['exterior_color'] = $this->validate($line['exterior_color']);
        $data['interior_color'] = $this->validate($line['interior_color']);
        $data['engine_cylinders'] = $this->validate($line['engine']);
        $data['engine_displacement'] = $this->validate($line['litres']);
        $data['transmission'] = $this->validate($line['transmission']);
        $data['drivetrain'] = $this->validate($line['drivetrain']);
        $data['highway_mpg'] = $this->validate($line['fuel_hwy']);
        $data['city_mpg'] = $this->validate($line['fuel_city']);
        $data['fuel_type'] = $this->validate($line['fuel_type']);
        $data['selling_price'] = ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
        $data['msrp'] = $line['price'];
        $comments = (new SyndicationRepository())->getInventoryDescParse($line);
        $data['description'] = SyndicationUtility::parseComment($comments);
        $data['options'] = SyndicationUtility::parseSyncdicationOptions($line['standard'],',') ;
        $data['photo_urls'] = $this->imageUrlLink;
        $data['vdp_url'] = $this->validate($line['vdp_url']);
        $data['type'] = 'Used';
        $data['carFAXLink'] = $this->validate($line['carfax']);


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
            // throws error  @TODO catch/handle no data errors
        }
    }
}
