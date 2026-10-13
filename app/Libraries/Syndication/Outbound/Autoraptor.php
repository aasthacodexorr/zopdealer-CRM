<?php namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Helpers\Utility;
use App\Repositories\SyndicationRepository;
use App\Libraries\FtpClient\FtpClient;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\ConfigurationRepository;


class Autoraptor extends SyndicationAbstract
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
            'vin',
            'stock_number',
            'year',
            'make',
            'model',
            'trim',
            'model_number',
            'mileage',
            'exterior_color',
            'interior_color',
            'options',
            'list_price',
            'balance',
            'base_retail_price',
            'invoice_price',
            'destination_charge',
            'new_or_used',
            'is_new',
            'is_used',
            'description',
            'transmission',
            'entry_date',
            'days_in_inventory',
            'status',
            'lot',
            'photo_urls',
            'public_site_url'
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
        $data['vin'] = $this->validate($line['vin']);
        $data['stock_number'] = $this->validate($line['stock_no']);
        $data['year'] = $this->validate($line['year']);
        $data['make'] = $this->validate($line['make']);
        $data['model'] = $this->validate($line['model']);
        $data['trim'] = $this->validate($line['trim']);
        $data['model_number'] = '';
        $data['mileage'] = $this->validate($line['odometer']);
        $data['exterior_color'] = $this->validate($line['exterior_color']);
        $data['interior_color'] = $this->validate($line['interior_color']);       
        $data['options'] = SyndicationUtility::parseSyncdicationOptions($line['standard'],',') ;
        $data['list_price'] = ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
        $data['balance'] = '';
        $data['base_retail_price'] = '';
        $data['invoice_price'] = '';
        $data['destination_charge'] = '';              
        $data['new_or_used'] = 'used';
        $data['is_new'] = '0';
        $data['is_used'] = '1';
        $comments = (new SyndicationRepository())->getInventoryDescParse($line);
        $data['description'] = SyndicationUtility::parseComment($comments);        
        $data['transmission'] = $this->validate($line['transmission']);
        $data['entry_date'] = '';
        $data['days_in_inventory'] = '';
        $data['status'] = '';
        $data['lot'] = '';
        $data['photo_urls'] = $this->imageUrlLink;
        $data['public_site_url'] = $this->validate($line['vdp_url']);

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

}
