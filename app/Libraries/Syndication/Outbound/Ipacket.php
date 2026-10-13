<?php

namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Repositories\SyndicationRepository;
use Hug\Sftp\Sftp as Sftp;
use App\Libraries\Syndication\SyndicationAbstract;

class Ipacket extends SyndicationAbstract
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
            'Dealer ID',
            'Stock',
            'VIN',
            'Stock Type',
            'Inventory Date',
            'Invoice Amount',
            'MSRP',
            'Status',
            'Certified',
            'Odometer',
            'Year',
            'Make',
            'Model',
            'Body Style',
            'Exterior Color',
            'Interior Color',
            'Model Code',
            'Trim',
            'Drivetrain',
            'Engine Description',
            'Transmission Description',
            'Internet Price',
            'Sale Price',
            'In Service',
            'Engine Cylinders',
            'Description',
            'City MPG',
            'Highway MPG',
            'Dealer Certification',
            'Image URL',
            'Image last modified',
            'Video URL',
            'Video last modified'
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
            if (!empty($line['image_with_overlay_urls'])) {
                $this->imageUrlLink = $this->formatImages($line['image_with_overlay_urls']);
            } elseif (!empty($line['image_urls'])) {
                $this->imageUrlLink = $this->formatImages($line['image_urls']);
            } else {
                $this->imageUrlLink = "";
            }

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
        $data['Dealer ID'] = $this->validate($this->config->identity_id);
        $data['Stock'] =  $this->validate($line['stock_no']);
        $data['VIN'] = $this->validate($line['vin']);
        $data['Stock Type'] = 'U';
        $data['Inventory Date'] =$this->validate($line['stock_date']);
        $data['Invoice Amount'] ='';
        $data['MSRP'] = '';
        $data['Status'] = '0' ;
        $data['Certified'] = 'No';
        $data['Odometer'] = $this->validate($line['odometer']);
        $data['Year'] = $this->validate($line['year']);
        $data['Make'] = $this->validate($line['make']);
        $data['Model'] = $this->validate($line['model']);
        $data['Body Style'] = $this->validate($line['body_type']);
        $data['Exterior Color'] = $this->validate($line['exterior_color']);
        $data['Interior Color'] = $this->validate($line['interior_color']);
        $data['Model Code'] = '';
        $data['Trim'] = $this->validate($line['trim']);
        $data['Drivetrain'] = $this->validate($line['drivetrain']);
        $data['Engine Description'] = $this->validate($line['litres']).'L '.  $this->validate($line['engine']);
        $data['Transmission Description'] = $this->validate($this->config->business_name);
        $data['Internet Price'] = $line['price'];
        $data['Sale Price'] = ($line['special_price_on'] == "1") ? $line['special_price'] : '';
        $data['In Service'] = '';
        $data['Engine Cylinders'] = '';
        $comments = (new SyndicationRepository())->getInventoryDescParse($line);
        $data['Description'] = SyndicationUtility::parseComment($comments);
        $data['City MPG'] =  $this->validate($line['fuel_city']);
        $data['Highway MPG'] = $this->validate($line['fuel_hwy']);
        $data['Dealer Certification'] = '';
        $data['Image URL'] = $this->imageUrlLink;
        $data['Image last modified'] = '';
        $data['Video URL'] = $this->validate($line['video']);
        $data['Video last modified'] = '';

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
